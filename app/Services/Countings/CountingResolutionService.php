<?php

declare(strict_types=1);

namespace App\Services\Countings;

use App\Models\Countings\Counting;
use App\Models\Countings\CountingRound;
use Illuminate\Support\Collection;

/**
 * Motor de desempate del doble conteo (reglas duras del negocio tomfic):
 *
 *  - C1 == C2  → ese valor manda
 *  - C1 != C2  → se abre C3 y el valor de C3 SIEMPRE manda
 *
 * Servicio puro de LECTURA y cálculo: no escribe, no toca el core de
 * inventario. Quien llama decide crear las rondas C3 a partir del resultado.
 */
class CountingResolutionService
{
    /**
     * Resuelve el valor final por producto de una toma.
     *
     * @return Collection<int, array{
     *     product_id: int,
     *     product_location_id: int,
     *     final_units: int,
     *     source: 'c1_c2_agree'|'c3_arbitration',
     *     c1_units: int|null,
     *     c2_units: int|null,
     *     c3_units: int|null,
     * }>
     */
    public function resolve(Counting $counting): Collection
    {
        $rounds = $counting->rounds()->with('items')->get();

        // Totales por ronda candidata: product_id => suma de unidades normalizadas.
        $candidatesByLocation = [];

        foreach ($rounds as $round) {
            if ($round->round_type === 'c3') {
                // C3 solo entra cuando ya cerró (el árbitro manda al cerrarse).
                if ($round->status === 'closed') {
                    $candidatesByLocation[$round->product_location_id]['c3'] =
                        $this->totalsByProduct($round);
                }
                continue;
            }

            if ($round->status !== 'closed') {
                continue; // rondas abiertas no cuentan
            }

            $totals = $this->totalsByProduct($round);
            $candidatesByLocation[$round->product_location_id][$round->round_type] = $totals;
        }

        $results = collect();

        foreach ($candidatesByLocation as $locationId => $byType) {
            // C3 ya existe y cerró: manda SIEMPRE, sin importar C1/C2.
            if (isset($byType['c3'])) {
                foreach ($byType['c3'] as $productId => $units) {
                    $results->push([
                        'product_id' => $productId,
                        'product_location_id' => $locationId,
                        'final_units' => $units,
                        'source' => 'c3_arbitration',
                        'c1_units' => $this->candidateUnits($byType, 'c1', $productId),
                        'c2_units' => $this->candidateUnits($byType, 'c2', $productId),
                        'c3_units' => $units,
                    ]);
                }
                continue;
            }

            if (!isset($byType['c1']) || !isset($byType['c2'])) {
                continue; // doble conteo incompleto: nada que resolver aún
            }

            // Union de productos contados por C1 y/o C2.
            $productIds = array_unique(array_merge(
                array_keys($byType['c1']),
                array_keys($byType['c2'])
            ));

            foreach ($productIds as $productId) {
                $c1 = $byType['c1'][$productId] ?? null;
                $c2 = $byType['c2'][$productId] ?? null;

                if ($c1 !== null && $c1 === $c2) {
                    $results->push([
                        'product_id' => $productId,
                        'product_location_id' => $locationId,
                        'final_units' => $c1,
                        'source' => 'c1_c2_agree',
                        'c1_units' => $c1,
                        'c2_units' => $c2,
                        'c3_units' => null,
                    ]);
                }
                // C1 != C2 (o producto contado por solo una ronda): discrepancia.
                // Se reporta SIN valor final; el Admin decide abrir C3.
            }
        }

        return $results;
    }

    /**
     * Productos con discrepancia entre C1 y C2 (o contados por una sola ronda),
     * por ubicación — candidatos a abrir ronda C3.
     *
     * @return Collection<int, array{product_location_id: int, product_id: int, c1_units: int|null, c2_units: int|null}>
     */
    public function discrepancies(Counting $counting): Collection
    {
        $rounds = $counting->rounds()->with('items')->get();

        $candidatesByLocation = [];
        $c3ResolvedLocations = [];
        foreach ($rounds as $round) {
            if ($round->round_type === 'c3') {
                // C3 cerrado en esta ubicación: el árbitro ya decidió, la disputa está resuelta.
                if ($round->status === 'closed') {
                    $c3ResolvedLocations[$round->product_location_id] = true;
                }
                continue;
            }

            if ($round->status !== 'closed') {
                continue;
            }

            $candidatesByLocation[$round->product_location_id][$round->round_type] =
                $this->totalsByProduct($round);
        }

        $disputed = collect();

        foreach ($candidatesByLocation as $locationId => $byType) {
            if (isset($c3ResolvedLocations[$locationId])) {
                continue; // con C3 cerrado ya no hay disputa: manda el árbitro
            }
            if (!isset($byType['c1']) || !isset($byType['c2'])) {
                continue;
            }

            $productIds = array_unique(array_merge(
                array_keys($byType['c1']),
                array_keys($byType['c2'])
            ));

            foreach ($productIds as $productId) {
                $c1 = $byType['c1'][$productId] ?? null;
                $c2 = $byType['c2'][$productId] ?? null;

                if ($c1 !== $c2) {
                    $disputed->push([
                        'product_location_id' => $locationId,
                        'product_id' => $productId,
                        'c1_units' => $c1,
                        'c2_units' => $c2,
                    ]);
                }
            }
        }

        return $disputed;
    }

    /**
     * ¿Puede cerrarse la toma? Todo lo resuelto o disputado está decidido:
     * sin discrepancias abiertas y con doble conteo completo en cada ubicación.
     */
    public function canClose(Counting $counting): bool
    {
        if ($counting->rounds()->where('status', '!=', 'closed')->exists()) {
            return false;
        }

        if ($counting->rounds()->where('round_type', 'c1')->doesntExist()) {
            return false;
        }

        if ($this->discrepancies($counting)->isNotEmpty()) {
            // Discrepancia sin C3 que la arbitre.
            return $counting->rounds()->where('round_type', 'c3')->exists();
        }

        return true;
    }

    /** Unidades normalizadas por producto en una ronda (units + boxes como unidades sueltas: caja=1 unidad contable por ahora). */
    private function totalsByProduct(CountingRound $round): array
    {
        $totals = [];
        foreach ($round->items as $item) {
            $totals[$item->product_id] = ($totals[$item->product_id] ?? 0)
                + (int) $item->units
                + (int) $item->boxes;
        }
        return $totals;
    }

    private function candidateUnits(array $byType, string $type, int $productId): ?int
    {
        return $byType[$type][$productId] ?? null;
    }
}
