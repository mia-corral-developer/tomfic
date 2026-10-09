# Delta: counting-rounds

## ADDED Requirements

### Requirement: Creación de una toma de inventario

El sistema SHALL permitir al Admin crear una toma de inventario (`counting`) que agrupe todas las rondas de un proceso de conteo, asociada a una organización y con un estado del proceso (`draft`, `in_progress`, `closed`).

#### Scenario: Admin crea una toma nueva
- **WHEN** el Admin crea una toma con nombre y alcance
- **THEN** la toma queda en estado `draft` sin rondas asociadas aún

### Requirement: Rondas C1/C2 a ciegas

El sistema SHALL permitir programar exactamente una ronda C1 y una ronda C2 por ubicación, con la regla de que el conteo de C1 SHALL NOT ser visible para el capturador de C2 (y viceversa) mientras ambas no estén cerradas.

#### Scenario: Se programa el doble conteo de una ubicación
- **WHEN** el Admin programa C1 y C2 para la ubicación "Bodega › Mueble › N°1"
- **THEN** cada ronda tiene su capturador asignado y estado `pending`

#### Scenario: C1 no ve el conteo de C2
- **WHEN** el capturador de C1 consulta su ronda
- **THEN** no recibe ningún dato de lo capturado por C2

### Requirement: Motor de desempate C1=C2 / C3 árbitro

El sistema SHALL determinar el valor final por producto con la regla:
- C1 == C2 → ese valor manda
- C1 != C2 → se abre una ronda C3 (árbitro) y **el valor de C3 SIEMPRE manda**, independientemente de lo que digan C1 y C2

#### Scenario: C1 y C2 coinciden
- **WHEN** C1 y C2 reportan 50 unidades del producto X
- **THEN** el valor final es 50 y no se abre C3

#### Scenario: C1 y C2 difieren
- **WHEN** C1 reporta 50 y C2 reporta 48 del producto X
- **THEN** el sistema abre una ronda C3 para ese producto/ubicación
- **WHEN** C3 reporta 49
- **THEN** el valor final es 49 aunque C1 fuera 50

### Requirement: Re-escaneo reemplaza

El sistema SHALL interpretar el escaneo repetido del mismo producto por el mismo capturador en la misma ronda como un **reemplazo** de la cantidad, nunca como un duplicado.

#### Scenario: Capturador corrige una cantidad
- **WHEN** el capturador escanea el producto X (ya capturado con 50) y digita 30
- **THEN** la cantidad queda en 30 — no se crea un segundo registro

### Requirement: Asignación de capturadores

El sistema SHALL permitir asignar a cada ronda un capturador (usuario), que solo ve SUS rondas asignadas y ningún conteo de otros.

#### Scenario: Capturador consulta sus rondas
- **WHEN** un capturador autenticado lista sus asignaciones
- **THEN** solo ve las rondas a él asignadas, sin datos de capturas de otros capturadores
