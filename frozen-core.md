# Zona congelada del core de inventario y la puerta del puente (tomfic)

> Fuente: decisión del dueño (08-oct-2026), reafirmada en el flujo
> evaluación-proyecto-debate 2026-10-10 (`evaluacion/2026-10-10/05_decision_final.md`).

## REGLA DURA

El core de inventario está **CONGELADO, solo lectura**. Nunca se edita, refactoriza
ni se agregan columnas a sus tablas:

- `StockAudit` / `StockAuditItem` y su controller/servicios
- `StockAdjustment` (el ledger de stock)
- TODOS los services de stock: `OrderService`,
  `ProductLocationStockService`, `TrackedStockAllocationService`,
  `*ReconciliationService`
- Controllers de `app/Http/Controllers/Inventory/`
- Sus migraciones y tests

El centinela `FrozenCoreTest` (Fase 0 del plan) falla el build si alguien rompe esta regla.

## LA PUERTA DEL PUENTE (patrón aprobado)

Lo único que el módulo de tomas (countings) puede ejecutar sobre el core es la
**API pública de ajustes**: `StockAdjustment::adjust(...)` — es un llamada, no una
edición. La llamada la hace exclusivamente `App\Services\Countings\CountingApplyService`
(por construir), desde un Job en cola, con:

- `type = 'recount'` — ya existe como tipo válido (migración del core)
- `reason = 'recount'`
- `reference` = el modelo `Counting` (auditoría: la toma que originó el ajuste)
- `actor` = usuario que cierra la toma (Admin)
- `locationId` = la ubicación de la discrepancia

Reglas exigidas por el debate (condiciones del crítico, ya en el plan):

1. `canClose()` por ubicación estricta (implementado — ver fix A1 abajo) antes
   de que cualquier Job de aplicación corra.
2. El total normalizado usa la fórmula del dueño:
   **total = (cajas × units_per_box) + unidades sueltas** (implementado — A2).
3. La aplicación se ejecuta en **Job en cola** con `StockAdjustment` idempotente
   por `(counting, location, product)` — no dos writers.
4. El snapshot de valores finales se recalcula al momento de aplicar
   (`CountingResolutionService::resolve`) — nunca se confía en datos viejos.

## Fixes del A1/A2 ya aplicados en zona Counting (no congelada)

- `CountingResolutionService::canClose()`: las discrepancias solo se cubren con
  una C3 **cerrada en la misma ubicación** de cada discrepancia — antes un
  `exists()` global permitía cerrar tomas con disputas sin árbitro.
- `CountingResolutionService::totalsByProduct()`: multiplica cajas ×
  `products.units_per_box` (default 1) en vez de sumar caja = 1 unidad.
- `CountingController::products()`: catálogo por ubicación con barcode, búsqueda
  exacta y cursor pagination — reemplaza `limit(500)` org-wide.

## Catálogo core: solo lectura siempre

El módulo de tomas lee `products` y `product_locations` del core (con escopes de
organización) pero no escribe en ellos. `products.units_per_box` vive es
única columna nueva del catálogo y vive en su propia migración aparte (no en la del core); no modifica migraciones ni tests del core.

## Frozen baseline sha1 (regenerar solo con aprobación del dueño)

El centinela `tests/Feature/Countings/FrozenCoreTest.php` valida que estos archivos
no cambien contra los sha1 registrados aquí (baseline del 13-oct-2026, commit de
los fixes A1/A2/B2). Formato: `<sha1>\a<ruta>` con dos espacios.

81521d23cbbe123aee0b37dee3f6e114b21de607  app/Models/Inventory/StockAudit.php
4706d3d671e635ec276d902e1c5daffab79301c6  app/Models/Inventory/StockAuditItem.php
0b18a393a5e4a26c10a6d869dd8fb521d91aefaf  app/Models/Inventory/StockAdjustment.php
ef10b08bee41e94de05b7d67110fe21fc5457103  app/Services/OrderService.php
450d54ec06e9afd2921e7cdd1351ef0e42acc76f  app/Services/ProductLocationStockService.php
0f0c5b4a58f19355af9e61cd587e76cb8bb4b4a3  app/Services/TrackedStockAllocationService.php
9052dd30a9bfa79e90648161b0bdf90815de50a4  app/Services/LocationStockReconciliationService.php
60675ca004da0730d6e5b4cf559090f72cd393e7  app/Services/TrackedStockReconciliationService.php
ca3f55eaf32a6d62c51dbcd39e64287c8988b8b0  app/Http/Controllers/Inventory/StockAuditController.php
514c8f34c4133ac0182c94bb7f412e636d2ea9b9  app/Http/Controllers/Inventory/StockAdjustmentController.php
