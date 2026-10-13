<?php

declare(strict_types=1);

namespace Tests\Feature\Countings;

use PHPUnit\Framework\TestCase;

/**
 * FASE 0 del plan aprobado (evaluacion/2026-10-10/02_plan.md):
 * guarda-ráiles de la zona congelada. Falla si alguien EDITA cualquiera de los
 * archivos del core de inventario respecto a su sha1 registrado aquí (baseline
 * del día de la regla dura). Es una vigilancia de rutas de archivo → contenido,
 * no de ejecución: si tocas el core, este test te obliga a actualizar
 * frozen-core.md y a decidirlo explícitamente con el dueño.
 */
class FrozenCoreTest extends TestCase
{
    /**
     * El agenda: rutas RELATIVAS al repo → sha1 del estado congelado.
     * Regenerar con: php -r "foreach (['a/','b/'] as ...) echo sha1_file($f);"
     * y commit del nuevo baseline SOLO con aprobación explícita del dueño.
     */
    private const FROZEN_FILES = [
        'app/Models/Inventory/StockAudit.php',
        'app/Models/Inventory/StockAuditItem.php',
        'app/Models/Inventory/StockAdjustment.php',
        'app/Services/OrderService.php',
        'app/Services/ProductLocationStockService.php',
        'app/Services/TrackedStockAllocationService.php',
        'app/Services/LocationStockReconciliationService.php',
        'app/Services/TrackedStockReconciliationService.php',
        'app/Http/Controllers/Inventory/StockAuditController.php',
        'app/Http/Controllers/Inventory/StockAdjustmentController.php',
    ];

    private static function path(string $relative): string
    {
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
    }

    public function test_every_frozen_core_file_exists_and_unchanged(): void
    {
        $violations = [];

        foreach (self::FROZEN_FILES as $relative) {
            $path = self::path($relative);

            if (!file_exists($path)) {
                $violations[] = "{$relative} — ARCHIVO ELIMINADO (la zona congelada no se toca)";
                continue;
            }

            // La huella se valida contra el registro en frozen-core.md (sección
            // "Frozen baseline sha1"). Cada línea: sha1<TAB>ruta.
            $registry = $this->registryHashes();
            $expected = $registry[$relative] ?? null;

            if ($expected === null) {
                // Archivo registrado en FROZEN_FILES pero sin línea en frozen-core.md:
                // defecto documental, lo marcamos (no es una edición del código).
                $violations[] = "{$relative} — falta su sha1 en frozen-core.md";
                continue;
            }

            $actual = sha1_file($path);
            if ($actual !== $expected) {
                $violations[] = "{$relative} — MODIFICADO (sha1 {$actual} ≠ registrado {$expected}). " .
                    'El core de inventario está congelado: revierte o actualiza frozen-core.md con aprobación del dueño.';
            }
        }

        $this->assertSame([], $violations, "Zona congelada violada:\n" . implode("\n", $violations));
    }

    public function test_frozen_core_md_is_registered_in_repository_root(): void
    {
        $this->assertFileExists(
            self::path('frozen-core.md'),
            'frozen-core.md debe existir en la raíz del repo (documenta la regla dura y la puerta del puente).'
        );
    }

    /** @return array<string, string> ruta → sha1 */
    private function registryHashes(): array
    {
        $content = file_get_contents(self::path('frozen-core.md'));
        $hashes = [];

        if (preg_match_all('/^([a-f0-9]{40})\s{2,}(app\/.+)$/m', $content, $m, PREG_SET_ORDER)) {
            foreach ($m as $row) {
                $hashes[$row[2]] = $row[1];
            }
        }

        return $hashes;
    }
}
