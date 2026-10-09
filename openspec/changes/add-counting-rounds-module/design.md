# Diseño: Módulo de Tomas con Rondas de Conteo

## Contexto

El repo ya tiene un motor de inventario sólido (StockAudit + ledger StockAdjustment) que queda **congelado, solo lectura**. El módulo nuevo (tomfic-field) vive en tablas propias y lee catálogos del core. Stack: Laravel 13, migrations sqlite/mysql, PHPUnit, Vue 3/Inertia, Zellia.

## Modelo de datos (4 tablas nuevas)

```
countings
  id, organization_id (FK orgs), name, description
  status: draft | in_progress | closed
  created_by, timestamps, softDeletes

counting_rounds
  id, counting_id (FK countings), location_id (FK locations, lectura core)
  round_type: c1 | c2 | c3        # c3 = árbol de desempate
  status: pending | open | closed
  timestamps

counting_round_assignments
  id, counting_round_id (FK), user_id (FK users)
  unique(counting_round_id, user_id)

counting_items
  id, counting_round_id (FK), product_id (FK products, lectura core)
  units (int), boxes (int) default 0
  photo_path (nullable)
  counted_by (FK users)
  unique(counting_round_id, product_id)   # re-escaneo REEMPLAZA, no duplica
```

## Motor de desempate (servicio puro + testeado)

`CountingResolutionService::resolve(counting)`:
1. Agrupa items por (location, product) de C1 y C2
2. Si amount(C1) == amount(C2) para un producto → final = ese valor
3. Si difieren → requiere ronda C3 para esa ubicación; final = valor de C3 **siempre** (regla dura del negocio)
4. Salida: `final_values[]` con `source: c1_c2_agree | c3_arbitration`

Notas:
- `units + boxes * pack_size` se normaliza ANTES de comparar (usar units normalizadas)
- C3 se crea por ubicación (no por producto) siguiendo el doc: "se abre C3 para esa ubicación"; el spec del producto pide por producto/ubicación → se modela C3 por ubicación y solo se piden los productos en discrepancia
- El servicio no escribe: calcula y devuelve; quien llama decide si crea la ronda C3

## Aislamiento del core

- Las FK a `products` y `locations` son de **lectura** (no añadimos columnas ni relaciones inversas en el core)
- Ningún service de stock se modifica ni se llama desde el módulo (por ahora); la decisión de "lo que resulte del conteo vence al StockAdjustment del core" queda FUERA de este change (decisión pendiente del dueño)
- Controllers nuevos en `app/Http/Controllers/Countings/`, sin tocar `app/Http/Controllers/Inventory/`

## Reglas de negocio implementadas en tests (PHPUnit)

- `test_c1_equals_c2_makes_that_value_final`
- `test_c1_differs_from_c2_opens_c3_and_c3_always_wins`
- `test_rescan_replaces_quantity_not_duplicates` (unique constraint + upsert)
- `test_capturador_only_sees_own_rounds` (policy de asignación)
- `test_c1_count_is_invisible_to_c2_capturador`

## UI (Zellia)

- Páginas de Admin: crear/editar toma (`Countings/Create`, `Countings/Show`), programar rondas, asignar capturadores
- Vista del Capturador: lista de SUS rondas + pantalla de captura con un solo input
- Todo con tokens Zellia existentes (CTA 48px, radius 8, Inter), componentes `ui/*` ya migrados
