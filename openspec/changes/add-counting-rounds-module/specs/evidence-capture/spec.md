# Delta: evidence-capture

## ADDED Requirements

### Requirement: Un solo input de captura

El sistema SHALL ofrecer al capturador un único campo de captura que acepte: pistola de código de barras (que dispara Enter sola), escritura manual del código, o cámara como opción secundaria.

#### Scenario: Capturador escanea con pistola
- **WHEN** la pistola emite el código del producto + Enter en el único input
- **THEN** el sistema identifica el producto y pide la cantidad (unidades y cajas)

### Requirement: Evidencia fotográfica

El sistema SHALL permitir adjuntar una foto de evidencia a cada captura de producto (`counting_items`).

#### Scenario: Capturador agrega foto de evidencia
- **WHEN** el capturador capture una cantidad y adjunte una foto
- **THEN** la foto queda asociada al item de conteo y es visible en los reportes

### Requirement: Guardado y sincronización

El sistema SHALL permitir al capturador guardar sus capturas cuando hay conectividad y sincronizarlas con el servidor sin perder datos ni pisar capturas concurrentes de otros capturadores.

#### Scenario: Captura sin conexión
- **WHEN** el capturador guarda sin conexión
- **THEN** la captura queda en local y se sincroniza al reconectar, sin duplicar items ya sincronizados
