# Tasks: Módulo de Tomas con Rondas de Conteo

## 1. Migraciones y modelos

- [ ] 1.1 Migración `create_countings_table` (org FK, name, description, status enum, created_by, softDeletes)
- [ ] 1.2 Migración `create_counting_rounds_table` (counting FK, location FK, round_type enum c1|c2|c3, status enum)
- [ ] 1.3 Migración `create_counting_round_assignments_table` (round FK, user FK, unique par)
- [ ] 1.4 Migración `create_counting_items_table` (round FK, product FK, units, boxes, photo_path nullable, counted_by FK, unique(round, product))
- [ ] 1.5 Modelos Eloquent `Counting`, `CountingRound`, `CountingRoundAssignment`, `CountingItem` con relaciones y casts

## 2. Motor de desempate

- [ ] 2.1 Servicio `CountingResolutionService::resolve(counting)` con normalización units+boxes y reglas C1=C2/C3 arbiter
- [ ] 2.2 Tests: agreement, difiere→C3 gana, rescan reemplaza (upsert), capturador ve solo sus rondas, C1 invisible para C2

## 3. Admin / API

- [ ] 3.1 Controller `CountingController` (create/store/show lifecycle) + políticas de acceso
- [ ] 3.2 Programación de rondas y asignación de capturadores (páginas Inertia con Zellia)
- [ ] 3.3 Acciones del ciclo de vida: abrir/cerrar ronda, abrir C3 cuando detecta discrepancia

## 4. Captura en campo

- [ ] 4.1 Vista ronda del capturador (solo sus rondas) + pantalla un solo input (pistola/manual, cámara opcional)
- [ ] 4.2 Endpoint de captura con reemplazo en re-escaneo + adjunto de foto de evidencia
- [ ] 4.3 Sync offline: guardado local + reenvío sin duplicados (idempotencia por unique round+product)

## 5. Reportes

- [ ] 5.1 Reporte sobrante/faltante × costo por producto/ubicación (usa costo del catálogo del core, solo lectura)
- [ ] 5.2 Export Excel con filtros + evidencia fotográfica en los reportes
