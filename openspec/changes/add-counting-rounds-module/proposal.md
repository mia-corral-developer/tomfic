# Propuesta: Módulo de Tomas con Rondas de Conteo (tomfic-field)

## Why

El doc de producto (TOMFIC-flujo-y-logica.md) define el corazón del negocio: tomas de inventario físico sin papel con **doble conteo** (C1 y C2 a ciegas, C3 árbitro cuando no cuadran). El repo actual solo tiene `StockAudit` con una pasada de conteo por usuario — no hay rondas, no hay asignación de capturadores, no hay evidencia fotográfica. Sin este módulo el producto tomfic.com no existe.

## What Changes

- **NUEVAS tablas** (módulo `tomfic-field`, aislado del core):
  - `countings` — la "toma" (agrupador, con estado del proceso)
  - `counting_rounds` — rondas C1/C2/C3 por ubicación, con estado
  - `counting_round_assignments` — capturador asignado a una ronda
  - `counting_items` — captura por producto (unidades/cajas + foto de evidencia)
- **Motor de negocio** con reglas del doble conteo:
  - C1 == C2 → este valor manda
  - C1 != C2 → **C3 árbitro SIEMPRE manda**
  - Re-escanear el mismo producto **reemplaza** la cantidad (nunca duplica)
- Nuevas páginas de Admin: crear toma, programar rondas, asignar capturadores
- Nuevos reportes: sobrante/faltante × costo + export Excel

**NO se toca el core de inventario** (StockAudit, StockAdjustment, services de stock, controllers de Inventory): queda congelado, solo-lectura. Los nuevos módulos leen catálogos (products, locations) del core vía relaciones de lectura.

## Capabilities

- **New Capabilities**:
  - `counting-rounds` — rondas de doble conteo C1/C2/C3, asignación de capturadores y motor de desempate
  - `evidence-capture` — captura de campo (escaneo único, reemplazo en re-escaneo, evidencia fotográfica)

- **Modified Capabilities**: ninguna

## Impact

- **Afectar**: 4 migraciones nuevas + modelos + 1 servicio central de desempate + rutas/controllers nuevos en `app/Http/Controllers/Countings/`
- **No afectar**: ninguna tabla ni service existente de inventario (solo lectura de `products` y `locations`)
- **Riesgo**: bajo — todo es greenfield en tablas propias con checks en CI (PHPUnit)
