# Zellia Design System

**Foundations · Token Reference**
Versión 1.1 · 2026-09-16

Documento único del sistema de diseño Zellia. Contiene el inventario completo de tokens, las especificaciones de uso de cada categoría y los exports listos para implementación.

---

## Índice

0. [Arquitectura y convenciones](#0-arquitectura-y-convenciones)
1. [Color](#1-color)
2. [Tipografía](#2-tipografía)
3. [Spacing](#3-spacing)
4. [Border Radius](#4-border-radius)
5. [Elevation / Shadows](#5-elevation--shadows)
6. [Buttons — Specs](#6-buttons--specs)
7. [Grids](#7-grids)
8. [Apéndice A — CSS completo](#8-apéndice-a--css-completo)
9. [Apéndice B — JSON completo](#9-apéndice-b--json-completo)
10. [Inventario de tokens](#10-inventario-de-tokens)

---

## 0. Arquitectura y convenciones

### 0.1 Arquitectura de tokens

Zellia usa una arquitectura de **tres capas**. Este documento define la **capa 1**.

```
Capa 1 — PRIMITIVOS      zellia.color.primary.800 = #023562
   ↓                     Valores crudos. No tienen significado de uso.

Capa 2 — SEMÁNTICOS      zellia.color.text.brand = {zellia.color.primary.800}
   ↓                     Asignan intención. Es la capa que cambia entre
                         light mode y dark mode.

Capa 3 — COMPONENTE      zellia.button.primary.background = {zellia.color.text.brand}
                         Overrides puntuales por componente.
```

**Regla fundamental:** un componente nunca consume un token primitivo directamente. Si no existe el token semántico necesario, se crea en la capa 2.

### 0.2 Nomenclatura

| Formato | Contexto de uso |
|---|---|
| `zellia.color.primary.800` | Documentación, Figma Variables, JSON |
| `--zellia-color-primary-800` | CSS Custom Properties |
| `zelliaColorPrimary800` | JavaScript / TypeScript |

### 0.3 Unidades

- **px es el valor canónico** del sistema. Es lo que se dibuja en Figma y lo que se valida en QA.
- `rem` se incluye como referencia de implementación web, calculado sobre base **16px**.
- Line-height se expresa en **px absolutos**, no unitless, para garantizar ritmo vertical predecible y compatibilidad con el grid de 4px.

### 0.4 Estructura de la escala de color

Escala **100 → 900**, donde `100` es el tono más claro y `900` el más oscuro. Todos los valores documentados corresponden a **Lightmode**.

---

## 1. Color

### 1.1 Primario

Color troncal de la marca. **Brand Color:** `800`

| Token | Hex | RGB | Uso |
|---|---|---|---|
| `zellia.color.primary.100` | `#F5F9FE` | 245, 249, 254 | Fondo de sección, superficie sutil |
| `zellia.color.primary.200` | `#E6EBEF` | 230, 235, 239 | Fondo hover, borde suave |
| `zellia.color.primary.300` | `#AABFD6` | 170, 191, 214 | Borde, estado deshabilitado |
| `zellia.color.primary.400` | `#86A3C2` | 134, 163, 194 | Iconografía secundaria |
| `zellia.color.primary.500` | `#6488AD` | 100, 136, 173 | Elementos de apoyo |
| `zellia.color.primary.600` | `#436C96` | 67, 108, 150 | Estado hover de acciones |
| `zellia.color.primary.700` | `#23527F` | 35, 82, 127 | Estado hover del brand |
| `zellia.color.primary.800` | `#023562` | 2, 53, 98 | **Brand Color** · Acción primaria, texto de marca |
| `zellia.color.primary.900` | `#002041` | 0, 32, 65 | Estado pressed, texto de máximo contraste |

### 1.2 Secundario

Paleta de apoyo. Aporta variación cromática sin competir con el primario. **Brand Color:** `500`

| Token | Hex | RGB | Uso |
|---|---|---|---|
| `zellia.color.secondary.100` | `#EEF5F8` | 238, 245, 248 | Fondo de sección, superficie sutil |
| `zellia.color.secondary.200` | `#C8DEEA` | 200, 222, 234 | Fondo hover, borde suave |
| `zellia.color.secondary.300` | `#A4C7DA` | 164, 199, 218 | Borde, estado deshabilitado |
| `zellia.color.secondary.400` | `#7FB1CA` | 127, 177, 202 | Iconografía secundaria |
| `zellia.color.secondary.500` | `#5799BA` | 87, 153, 186 | **Brand Color** · Elementos de apoyo |
| `zellia.color.secondary.600` | `#4A829E` | 74, 130, 158 | Estado hover |
| `zellia.color.secondary.700` | `#3D6B82` | 61, 107, 130 | Estado pressed |
| `zellia.color.secondary.800` | `#305466` | 48, 84, 102 | Texto sobre superficie clara |
| `zellia.color.secondary.900` | `#233D4A` | 35, 61, 74 | Máximo contraste |

### 1.3 Acento

Color de énfasis. Alta saturación, uso restringido. **Base:** `500`

| Token | Hex | RGB | Uso |
|---|---|---|---|
| `zellia.color.accent.100` | `#FFF6F2` | 255, 246, 242 | Fondo de destacado |
| `zellia.color.accent.200` | `#FFD2C5` | 255, 210, 197 | Fondo hover, borde suave |
| `zellia.color.accent.300` | `#FFAC95` | 255, 172, 149 | Borde, estado deshabilitado |
| `zellia.color.accent.400` | `#FD8C62` | 253, 140, 98 | Iconografía de énfasis |
| `zellia.color.accent.500` | `#FC4501` | 252, 69, 1 | **Base** · Acción de máxima prioridad |
| `zellia.color.accent.600` | `#D63B01` | 214, 59, 1 | Estado hover |
| `zellia.color.accent.700` | `#B03001` | 176, 48, 1 | Estado pressed |
| `zellia.color.accent.800` | `#8B2601` | 139, 38, 1 | Texto sobre superficie clara |
| `zellia.color.accent.900` | `#651C00` | 101, 28, 0 | Máximo contraste |

Máximo **una** acción accent por pantalla. El accent marca la acción más importante del flujo.

### 1.4 Monocromático

Base estructural del sistema: texto, bordes, superficies, separadores y estados deshabilitados.

| Token | Hex | RGB | Uso |
|---|---|---|---|
| `zellia.color.neutral.100` | `#F8F8F8` | 248, 248, 248 | Fondo de página |
| `zellia.color.neutral.200` | `#E9E9E9` | 233, 233, 233 | Superficie hover, separador |
| `zellia.color.neutral.300` | `#CECECE` | 206, 206, 206 | Borde por defecto |
| `zellia.color.neutral.400` | `#ABABAB` | 171, 171, 171 | Texto deshabilitado, placeholder |
| `zellia.color.neutral.500` | `#7F7F7E` | 127, 127, 126 | Texto terciario, iconografía inactiva |
| `zellia.color.neutral.600` | `#595958` | 89, 89, 88 | Texto secundario |
| `zellia.color.neutral.700` | `#3D3D3B` | 61, 61, 59 | Texto de cuerpo |
| `zellia.color.neutral.800` | `#2D2D2B` | 45, 45, 43 | Texto de énfasis |
| `zellia.color.neutral.900` | `#222220` | 34, 34, 32 | Titulares, máximo contraste |

### 1.5 Alert System

Colores funcionales de estado. Cada familia tiene tres variantes con un rol fijo:

| Variante | Función |
|---|---|
| `light` | Fondo del contenedor |
| `base` | Color principal, ícono, borde de acento |
| `dark` | Texto sobre el fondo `light` |

#### Absolutos

| Token | Hex | RGB |
|---|---|---|
| `zellia.color.common.white` | `#FFFFFF` | 255, 255, 255 |
| `zellia.color.common.black` | `#000000` | 0, 0, 0 |

#### Success — confirmación, operación completada

| Token | Hex | RGB |
|---|---|---|
| `zellia.color.success.light` | `#EAFFF1` | 234, 255, 241 |
| `zellia.color.success.base` | `#22C55E` | 34, 197, 94 |
| `zellia.color.success.dark` | `#15803D` | 21, 128, 61 |

#### Warning — advertencia, acción reversible con consecuencia

| Token | Hex | RGB |
|---|---|---|
| `zellia.color.warning.light` | `#FFF7D7` | 255, 247, 215 |
| `zellia.color.warning.base` | `#F59E0B` | 245, 158, 11 |
| `zellia.color.warning.dark` | `#92400E` | 146, 64, 14 |

#### Error — fallo, validación inválida, acción destructiva

| Token | Hex | RGB |
|---|---|---|
| `zellia.color.error.light` | `#FFD7D7` | 255, 215, 215 |
| `zellia.color.error.base` | `#EF4444` | 239, 68, 68 |
| `zellia.color.error.dark` | `#991B1B` | 153, 27, 27 |

#### Info — información neutra, contexto adicional

| Token | Hex | RGB |
|---|---|---|
| `zellia.color.info.light` | `#E6F2FF` | 230, 242, 255 |
| `zellia.color.info.base` | `#2196F3` | 33, 150, 243 |
| `zellia.color.info.dark` | `#0D47A1` | 13, 71, 161 |

### 1.6 Reglas de uso del color

1. **El color nunca es el único portador de significado.** Un campo en error necesita además ícono y mensaje de texto.
2. **Texto sobre `light` usa siempre `dark` de la misma familia.** `success.dark` sobre `success.light` es la única pareja válida de esa familia.
3. **`base` no es color de texto.** Es para íconos, bordes y rellenos sólidos.
4. **Accent y Error nunca van adyacentes.** Son cromáticamente cercanos y generan ambigüedad sobre qué acción es destructiva.
5. **Escalas 100–300 son superficies. 600–900 son texto.** Las intermedias (400–500) son bordes e iconografía.

---

## 2. Tipografía

### 2.1 Font Family

| Token | Valor |
|---|---|
| `zellia.font.family.base` | `Inter` |
| `zellia.font.family.fallback` | `'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif` |

**Inter es la única familia del sistema.** Cubre display, UI y body.

**Pesos a cargar:** `300`, `400`, `500`, `600`, `700`. Únicamente estos cinco.

### 2.2 Font Weight

| Token | Nombre | Valor | Función |
|---|---|---|---|
| `zellia.font.weight.light` | Light | `300` | Titulares grandes de tono editorial |
| `zellia.font.weight.regular` | Regular | `400` | Peso por defecto de todo texto de lectura |
| `zellia.font.weight.medium` | Medium | `500` | Énfasis suave, labels, texto denso de UI |
| `zellia.font.weight.semibold` | SemiBold | `600` | Énfasis fuerte, titulares, texto de botón |
| `zellia.font.weight.bold` | Bold | `700` | Máximo énfasis, display, titulares de marca |

### 2.3 Font Size & Line Height

| Token de tamaño | px | rem | Line Height | Ratio |
|---|---|---|---|---|
| `zellia.font.size.display` | 64 | 4.000 | 72 | 1.125 |
| `zellia.font.size.headlineMarketing1` | 48 | 3.000 | 56 | 1.167 |
| `zellia.font.size.headlineMarketing2` | 40 | 2.500 | 48 | 1.200 |
| `zellia.font.size.headlineMarketing3` | 32 | 2.000 | 40 | 1.250 |
| `zellia.font.size.headline1` | 28 | 1.750 | 36 | 1.286 |
| `zellia.font.size.headline2` | 24 | 1.500 | 32 | 1.333 |
| `zellia.font.size.subtitle1` | 20 | 1.250 | 28 | 1.400 |
| `zellia.font.size.subtitle2` | 18 | 1.125 | 24 | 1.333 |
| `zellia.font.size.body1` | 16 | 1.000 | 24 | 1.500 |
| `zellia.font.size.body2` | 14 | 0.875 | 20 | 1.429 |
| `zellia.font.size.caption1` | 12 | 0.750 | 16 | 1.333 |
| `zellia.font.size.caption2` | 10 | 0.625 | 14 | 1.400 |
| `zellia.font.size.label` | 12 | 0.750 | 16 | 1.333 |
| `zellia.font.size.buttonGiant` | 18 | 1.125 | 22 | 1.222 |
| `zellia.font.size.buttonLarge` | 16 | 1.000 | 18 | 1.125 |
| `zellia.font.size.buttonMedium` | 14 | 0.875 | 16 | 1.143 |
| `zellia.font.size.buttonSmall` | 12 | 0.750 | 14 | 1.167 |
| `zellia.font.size.buttonTiny` | 10 | 0.625 | 12 | 1.200 |

El token de line-height comparte el nombre del rol: `zellia.font.lineHeight.display`, `zellia.font.lineHeight.body1`, etc.

El ratio decrece conforme crece el tamaño (1.50 en `body1` → 1.125 en `display`): el texto grande necesita proporcionalmente menos aire entre líneas para mantenerse como bloque, mientras el texto pequeño de lectura necesita más. Los tokens de botón usan ratios apretados (1.125–1.22) porque son de una sola línea.

### 2.4 Matriz de estilos compuestos

Las combinaciones marcadas son las únicas válidas del sistema. **Total: 66 estilos compuestos.**

| Rol | Size / LH | Light 300 | Regular 400 | Medium 500 | SemiBold 600 | Bold 700 |
|---|---|:--:|:--:|:--:|:--:|:--:|
| Display | 64 / 72 | • | • | — | — | • |
| Headline 1 Marketing | 48 / 56 | • | • | — | • | • |
| Headline 2 Marketing | 40 / 48 | • | • | — | • | • |
| Headline 3 Marketing | 32 / 40 | • | • | — | • | • |
| Headline 1 | 28 / 36 | • | • | — | • | • |
| Headline 2 | 24 / 32 | • | • | — | • | • |
| Subtitle 1 | 20 / 28 | • | • | — | • | • |
| Subtitle 2 | 18 / 24 | • | • | — | • | • |
| Body 1 | 16 / 24 | • | • | — | • | • |
| Body 2 | 14 / 20 | • | • | • | — | — |
| Caption 1 | 12 / 16 | • | • | • | — | — |
| Caption 2 | 10 / 14 | • | • | • | — | — |
| Label | 12 / 16 | • | — | • | — | — |
| Button Giant | 18 / 22 | • | • | • | • | — |
| Button Large | 16 / 18 | • | • | • | • | — |
| Button Medium | 14 / 16 | • | • | • | • | — |
| Button Small | 12 / 14 | • | • | • | • | — |
| Button Tiny | 10 / 12 | • | • | • | • | — |

Nomenclatura del token compuesto: `zellia.typography.<rol>.<peso>`

```
zellia.typography.display.bold
zellia.typography.headlineMarketing1.semibold
zellia.typography.body2.medium
zellia.typography.buttonGiant.semibold
```

**Criterios de la matriz:**

- **Display no tiene SemiBold.** A 64px la diferencia entre 600 y 700 es imperceptible.
- **Body 2, Caption 1 y Caption 2 no tienen SemiBold ni Bold.** Su énfasis máximo es Medium.
- **Label no tiene Regular.** Su peso base es Medium: un label debe distinguirse del valor que etiqueta.
- **Los botones no tienen Bold.** SemiBold es su techo; Bold competiría con los titulares de la pantalla.

### 2.5 Función de cada rol

| Rol | Para qué existe | Dónde se usa | Dónde no |
|---|---|---|---|
| **Display** | Impacto máximo, momento de marca | Hero de landing, splash, bienvenida | Dentro de la app; nunca más de 1 por pantalla |
| **Headline 1–3 Marketing** | Jerarquía de páginas comerciales | Landings, páginas públicas, campañas | Interfaces de producto |
| **Headline 1–2** | Jerarquía dentro del producto | Título de pantalla, encabezado de sección | Marketing |
| **Subtitle 1–2** | Contexto que acompaña a un headline | Bajada de título, encabezado de card | Como título principal |
| **Body 1** | Texto de lectura por defecto | Párrafos, descripciones, contenido largo | Tablas densas |
| **Body 2** | Texto secundario y denso | Filas de tabla, listas, texto de apoyo | Lectura larga |
| **Caption 1–2** | Metadatos y anotaciones | Timestamps, helper text, contadores | Contenido de lectura obligatoria |
| **Label** | Etiqueta de control de formulario | Label de input, encabezado de columna, tag | Texto corrido |
| **Button (5 tamaños)** | Texto dentro de acciones | Botones, links de acción, tabs | Elementos no interactivos |

### 2.6 Reglas del sistema

1. **La jerarquía se construye con tamaño; el énfasis, con peso.** Para destacar algo dentro de un párrafo se sube el peso, no el tamaño.
2. **No se saltan niveles.** Headline 1 → Headline 2 → Subtitle 1 → Body 1.
3. **Un solo Display por pantalla.**
4. **Longitud de línea: 60–75 caracteres** en Body 1 y Body 2. Se controla con `max-width`.
5. **El rol tipográfico no es la etiqueta HTML.** `zellia-headline-1` puede aplicarse a un `<h2>`. La semántica del DOM la define la estructura del documento.
6. **Button Font solo en elementos interactivos.**
7. **Marketing y Producto no se mezclan.** Los roles Marketing viven en páginas públicas; los de producto, dentro de la aplicación.
8. **El peso nunca comunica estado por sí solo.** Un item no leído en Bold necesita además otro indicador.

### 2.7 Restricciones de uso

| Restricción | Alcance |
|---|---|
| **Peso `Light` (300)** | Uso restringido a tamaños de **24px o superiores**: Display, Headline 1–3 Marketing, Headline 1, Headline 2 y Subtitle 1. A tamaños menores el grosor de trazo reduce la legibilidad. |
| **`Caption 2` (10px)** | Válido para timestamps, superíndices, contadores y anotaciones prescindibles. No debe contener información necesaria para completar una tarea, mensajes de error ni instrucciones. |
| **Zoom** | El sistema soporta zoom hasta 200% sin pérdida de contenido. Por eso los tamaños se implementan en `rem` en web. |
| **Texto como imagen** | Ningún titular se exporta rasterizado. |

### 2.8 Comportamiento responsive

Los roles **Marketing** y **Display** escalan hacia abajo. Los roles de **producto** no cambian: su tamaño está calibrado para densidad de interfaz.

| Rol | ≥1024px | 768–1023px | <768px |
|---|---|---|---|
| Display | 64 / 72 | 48 / 56 | 40 / 48 |
| Headline 1 Marketing | 48 / 56 | 40 / 48 | 32 / 40 |
| Headline 2 Marketing | 40 / 48 | 32 / 40 | 28 / 36 |
| Headline 3 Marketing | 32 / 40 | 28 / 36 | 24 / 32 |
| Headline 1 · Headline 2 | sin cambio | sin cambio | sin cambio |
| Subtitle 1 · Subtitle 2 | sin cambio | sin cambio | sin cambio |
| Body 1 · Body 2 | sin cambio | sin cambio | sin cambio |
| Caption 1 · Caption 2 · Label | sin cambio | sin cambio | sin cambio |
| Button (los 5) | sin cambio | sin cambio | sin cambio |

Todos los valores de escalado reutilizan tokens que ya existen en la escala. El sistema no crea tamaños nuevos para responsive.

### 2.9 Clases de utilidad

El rol define familia, tamaño y line-height. El peso se compone como modificador independiente.

```html
<h1 class="zellia-headline-marketing-1 zellia-w-bold">Zellia</h1>
<p  class="zellia-body-1 zellia-w-regular">Texto de lectura por defecto.</p>
<span class="zellia-button-medium zellia-w-semibold">Continuar</span>
```

| Clases de rol | Clases de peso |
|---|---|
| `.zellia-display` | `.zellia-w-light` |
| `.zellia-headline-marketing-1` · `-2` · `-3` | `.zellia-w-regular` |
| `.zellia-headline-1` · `-2` | `.zellia-w-medium` |
| `.zellia-subtitle-1` · `-2` | `.zellia-w-semibold` |
| `.zellia-body-1` · `-2` | `.zellia-w-bold` |
| `.zellia-caption-1` · `-2` | |
| `.zellia-label` | |
| `.zellia-button-giant` · `-large` · `-medium` · `-small` · `-tiny` | |

---

## 3. Spacing

### 3.1 Base grid: 4px

**Todo valor de spacing del sistema es múltiplo de 4.** La escala se divide en tres tramos con propósitos distintos:

| Tramo | Rango | Propósito |
|---|---|---|
| **Micro** | 0–4px | Relaciones internas muy apretadas dentro de un mismo elemento |
| **Componente** | 8–20px | Padding y gaps dentro de un componente. Tramo de trabajo diario |
| **Layout** | 24–96px | Separación entre componentes, grupos y secciones de página |

### 3.2 Escala

| Token | px | rem | Tramo | Uso |
|---|---|---|---|---|
| `zellia.space.0` | 0 | 0 | — | Reset, colapsar gaps heredados |
| `zellia.space.2` | 2 | 0.125 | Micro | Gap ícono–texto apretado, offset de focus ring |
| `zellia.space.4` | 4 | 0.25 | Micro | Gap ícono–label, padding de badges |
| `zellia.space.8` | 8 | 0.5 | **Componente** | Padding interno chico, gap entre elementos relacionados |
| `zellia.space.12` | 12 | 0.75 | Componente | Padding de inputs, gap en listas densas |
| `zellia.space.16` | 16 | 1.0 | **Componente** | Padding estándar de card, gap entre campos de formulario |
| `zellia.space.20` | 20 | 1.25 | Componente | Padding horizontal de botones grandes |
| `zellia.space.24` | 24 | 1.5 | Layout | Padding de card grande, gap entre grupos |
| `zellia.space.32` | 32 | 2.0 | Layout | Separación entre bloques |
| `zellia.space.40` | 40 | 2.5 | Layout | Separación entre subsecciones |
| `zellia.space.48` | 48 | 3.0 | Layout | Separación entre secciones |
| `zellia.space.64` | 64 | 4.0 | Layout | Padding vertical de sección |
| `zellia.space.80` | 80 | 5.0 | Layout | Bloques de landing |
| `zellia.space.96` | 96 | 6.0 | Layout | Hero, respiros de máxima amplitud |

`zellia.space.2` es el único valor del sistema que no es múltiplo de 4. Es una excepción deliberada para gaps de ícono a texto y offsets de foco, donde 4px se percibe suelto.

### 3.3 Reglas del sistema

1. **8 y 16 son los valores por defecto.** El resto de la escala son excepciones justificadas: Micro para lo apretado, Layout para lo macro. Ante la duda entre 12 y 16, la respuesta es 16.
2. **Si se necesita un valor fuera de la tabla, se rediseña — no se agrega el token.** La escala cubre de 0 a 96px.
3. **El spacing se aplica en una sola dirección.** `margin-bottom` o `gap`, nunca `margin-top` y `margin-bottom` simultáneos en el mismo eje.
4. **Se prefiere `gap` sobre `margin`.** En flex y grid, `gap` expresa la relación entre hermanos sin que cada hijo cargue con conocimiento de su contexto.
5. **La proximidad comunica relación.** Dos elementos separados por `space.8` se leen como un grupo; separados por `space.32`, como cosas distintas.
6. **El spacing entre secciones siempre es mayor que el spacing interno.** Si el padding interno de una card es `space.16`, la separación entre cards es `space.24` o más.
7. **No se usan valores negativos** salvo en overlaps intencionales documentados (avatares apilados, badges superpuestos).

---

## 4. Border Radius

### 4.1 Escala

| Token | px | Para qué existe | Componentes |
|---|---|---|---|
| `zellia.radius.none` | 0 | Reset y elementos a sangre | Tablas, divisores, barras full-width, imágenes edge-to-edge |
| `zellia.radius.sm` | 4 | Controles pequeños de formulario | Input, select, checkbox, tag, badge, tooltip |
| `zellia.radius.md` | 8 | **Default del sistema** | Botón, card, dropdown, alert, popover |
| `zellia.radius.lg` | 12 | Contenedores que agrupan otros componentes | Card grande, modal, panel, sección elevada |
| `zellia.radius.xl` | 16 | Superficies que entran desde el borde de pantalla | Bottom sheet, drawer mobile, modal full-width |
| `zellia.radius.full` | 9999 | Formas totalmente redondeadas | Avatar, pill, switch, badge numérico, FAB |

La escala progresa en múltiplos de 4, igual que el spacing, para mantener coherencia geométrica cuando radius y padding conviven en el mismo componente.

`full` usa `9999px` para garantizar el redondeo completo sea cual sea el tamaño del elemento.

### 4.2 Reglas del sistema

**1. Regla de anidamiento**

```
radius_interno = radius_externo − padding
```

Una card con `radius.lg` (12) y padding `space.8` lleva su imagen interna en `radius.sm` (4). Si se igualan los radios, las curvas interna y externa no son concéntricas y el borde se percibe torcido.

**2. Un solo radius por componente.** No se mezclan `md` arriba y `sm` abajo dentro de la misma pieza, salvo radius parcial intencional (regla 6).

**3. El radius escala con el contenedor.** El radius no debe superar el 25% de la dimensión menor del elemento, excepto cuando se busca deliberadamente una pill.

**4. `full` solo produce un círculo si `width === height`.** Con anchos variables genera una pill — correcto para tags, badges de texto y botones pill; no para avatares, que requieren proporción cuadrada forzada.

**5. Elementos agrupados.** En un grupo de botones, un segmented control o un input con addon, el radius va solo en los extremos exteriores; las uniones internas van en `none`.

**6. Radius parcial permitido en tres casos:**

| Caso | Aplicación |
|---|---|
| Bottom sheets y drawers | `xl` en las esquinas que miran al contenido, `none` en las que tocan el borde de pantalla |
| Tabs activos | `md` arriba, `none` abajo, para fundirse con el panel |
| Acordeones | Primer y último item redondeados, intermedios en `none` |

**7. El radius es una decisión de sistema, no de pantalla.** Todos los botones del producto usan `radius.md`.

---

## 5. Elevation / Shadows

### 5.1 Principio

Las sombras de Zellia usan el azul profundo de la marca (`primary.900` → `rgb(0, 32, 65)`) con opacidad variable, no negro puro. Esto integra cromáticamente la profundidad con el resto del sistema.

Cada nivel se compone de **dos capas**: una sombra tensa y cercana que define el borde del objeto, y una difusa y lejana que define su distancia al fondo.

Los offsets y blurs siguen la progresión 1, 2, 4, 8, 16, 32, 64 — compatible con el grid de 4px. La opacidad crece con la distancia (0.06 → 0.18).

### 5.2 Escala de elevación

| Token | Nivel | Valor | Significado |
|---|---|---|---|
| `zellia.shadow.none` | 0 | `none` | Al ras de la superficie |
| `zellia.shadow.xs` | 1 | `0 1px 2px 0 rgba(0,32,65,0.06)` | Separación mínima |
| `zellia.shadow.sm` | 2 | `0 1px 2px 0 rgba(0,32,65,0.06), 0 2px 4px -1px rgba(0,32,65,0.08)` | Reposo de una card |
| `zellia.shadow.md` | 3 | `0 2px 4px -1px rgba(0,32,65,0.06), 0 4px 8px -2px rgba(0,32,65,0.10)` | Hover, dropdown |
| `zellia.shadow.lg` | 4 | `0 4px 8px -2px rgba(0,32,65,0.08), 0 8px 16px -4px rgba(0,32,65,0.12)` | Flota sobre el contenido |
| `zellia.shadow.xl` | 5 | `0 8px 16px -4px rgba(0,32,65,0.10), 0 16px 32px -8px rgba(0,32,65,0.14)` | Modal |
| `zellia.shadow.2xl` | 6 | `0 16px 32px -8px rgba(0,32,65,0.12), 0 32px 64px -16px rgba(0,32,65,0.18)` | Máxima jerarquía |
| `zellia.shadow.inner` | — | `inset 0 2px 4px 0 rgba(0,32,65,0.06)` | Hundido: input presionado, track de slider |

### 5.3 Focus rings

Sombras funcionales. Se implementan con `box-shadow` en lugar de `outline` para respetar el `border-radius` del elemento.

| Token | Valor | Uso | Deriva de |
|---|---|---|---|
| `zellia.shadow.focus.primary` | `0 0 0 4px rgba(2,53,98,0.20)` | Foco por defecto | `primary.800` |
| `zellia.shadow.focus.accent` | `0 0 0 4px rgba(252,69,1,0.20)` | Foco en acciones de énfasis | `accent.500` |
| `zellia.shadow.focus.error` | `0 0 0 4px rgba(239,68,68,0.20)` | Campo inválido | `error.base` |
| `zellia.shadow.focus.success` | `0 0 0 4px rgba(34,197,94,0.20)` | Confirmación | `success.base` |

**Reglas del focus ring:**

- El ring es de **4px**, alineado al grid del sistema.
- Siempre va acompañado de un cambio de `border-color`, nunca solo.
- El foco nunca se elimina. Si molesta con el mouse, se usa `:focus-visible`, no `:focus`.
- El ring no ocupa espacio de layout: el contenedor necesita al menos `space.4` de holgura para que no se recorte.

### 5.4 Mapa de elevación por componente

| Nivel | Token | Componentes |
|---|---|---|
| 0 | `none` | Fondo de página, contenido inline, tablas, cards planas con borde |
| 1 | `xs` | Card en reposo, header sticky, celda seleccionada |
| 2 | `sm` | Card interactiva, botón elevado, chip arrastrable |
| 3 | `md` | Dropdown, select abierto, tooltip, hover de card, autocomplete |
| 4 | `lg` | Popover, menú contextual, FAB, toast / snackbar |
| 5 | `xl` | Modal, dialog, drawer, bottom sheet |
| 6 | `2xl` | Command palette, overlay de máxima prioridad |

### 5.5 Reglas del sistema

1. **La elevación comunica distancia, no importancia.** Un elemento importante lleva mejor posición, color o tamaño — no más sombra.
2. **Un salto por interacción.** Reposo `sm` → hover `md`. Nunca `sm` → `xl`.
3. **Transición obligatoria:** `box-shadow 150ms ease-out` en cualquier cambio de elevación.
4. **Sombra o borde, no ambos.** La excepción es `xs`, donde el borde hace el trabajo estructural.
5. **Nunca dos elementos del mismo nivel superpuestos.** Si se solapan, uno sube de nivel.
6. **La elevación es relativa a la superficie contenedora.** Dentro de un modal (`xl`), sus hijos vuelven a empezar desde nivel 0.
7. **Las sombras acompañan al `z-index`, no lo reemplazan.** Ambos sistemas deben contar la misma historia.
8. **La sombra nunca es el único indicador de estado.** Un botón en hover cambia además de color.

### 5.6 Dark mode

Sobre fondos oscuros la elevación se comunica con superficies progresivamente más claras (`neutral.900` → `neutral.800` → `neutral.700`), y la sombra pasa a ser un refuerzo sutil con opacidad más alta. Cada token de sombra tendrá un valor distinto por modo, sin cambiar de nombre.

---

## 6. Buttons — Specs

### 6.1 Especificación por tamaño

Todos los valores son múltiplos de 4 y se componen de tokens existentes del sistema.

| Tamaño | Token tipográfico | Altura | Padding H | Gap ícono–texto | Ícono | Radius |
|---|---|---|---|---|---|---|
| **Giant** | `buttonGiant` (18 / 22) | `56px` | `space.24` (24) | `space.8` (8) | `24px` | `radius.md` |
| **Large** | `buttonLarge` (16 / 18) | `48px` | `space.20` (20) | `space.8` (8) | `20px` | `radius.md` |
| **Medium** | `buttonMedium` (14 / 16) | `40px` | `space.16` (16) | `space.8` (8) | `16px` | `radius.md` |
| **Small** | `buttonSmall` (12 / 14) | `32px` | `space.12` (12) | `space.4` (4) | `16px` | `radius.sm` |
| **Tiny** | `buttonTiny` (10 / 12) | `24px` | `space.8` (8) | `space.4` (4) | `12px` | `radius.sm` |

**Medium es el tamaño por defecto del sistema.** Si no hay una razón explícita para elegir otro, se usa Medium.

**Peso tipográfico por defecto:** `semibold` (600) en los cinco tamaños. `medium` (500) para botones de baja prioridad (terciarios, ghost) y `regular` (400) para links de acción inline.

### 6.2 Derivación de las alturas

Cada altura sale de `line-height del texto + padding vertical`, redondeado al múltiplo de 4 más cercano:

| Tamaño | Line-height | Padding V implícito | Altura total |
|---|---|---|---|
| Giant | 22 | 17 + 17 | 56 |
| Large | 18 | 15 + 15 | 48 |
| Medium | 16 | 12 + 12 | 40 |
| Small | 14 | 9 + 9 | 32 |
| Tiny | 12 | 6 + 6 | 24 |

**El padding vertical no se tokeniza.** Se implementa con `height` fija y centrado vertical (`display: inline-flex; align-items: center`), lo que garantiza que dos botones del mismo tamaño midan exactamente lo mismo aunque uno tenga ícono y el otro no.

La progresión de alturas es **24 → 32 → 40 → 48 → 56**: incrementos constantes de 8px.

### 6.3 Área táctil

| Tamaño | Dónde se permite | Requisito |
|---|---|---|
| Giant, Large, Medium | Cualquier contexto | Ninguno adicional |
| **Small** (32px) | Escritorio con mouse; tablas y toolbars densas | En touch, debe expandirse el área táctil a 44px |
| **Tiny** (24px) | Solo escritorio; contextos muy densos (celdas de tabla, chips) | No se usa como acción principal ni destructiva. En touch, debe expandirse el área táctil a 44px |

El área táctil se expande con un pseudo-elemento, no con padding, para no alterar el layout:

```css
.zellia-button-tiny::after,
.zellia-button-small::after {
  content: '';
  position: absolute;
  inset: 50% 50% 50% 50%;
  min-width: 44px;
  min-height: 44px;
  transform: translate(-50%, -50%);
  /* El botón debe tener position: relative */
}
```

### 6.4 Estados y elevación

| Estado | Elevación | Notas |
|---|---|---|
| Default | `shadow.none` o `shadow.sm` | `sm` solo en botones elevados sobre superficie |
| Hover | Un nivel arriba del default | Con `--zellia-shadow-transition` |
| Active / Pressed | Vuelve al nivel del default | El botón se hunde de nuevo |
| Focus | Default + `shadow.focus.*` | El ring se suma, no reemplaza |
| Disabled | `shadow.none` | Sin sombra, sin transición, `cursor: not-allowed` |
| Loading | Mantiene el nivel actual | El tamaño del botón no cambia al entrar en loading |

**Regla de ancho:** un botón nunca cambia de ancho al cambiar de estado. Si el texto de loading es más largo que el original, se reserva el ancho con `min-width` desde el inicio.

---

## 7. Grids

### 7.1 Breakpoints

El sistema tiene tres breakpoints. Coinciden con los usados en el escalado tipográfico (§2.8), de modo que tipografía y layout cambian en el mismo punto y nunca se desincronizan.

| Token | Valor | Rango | Grid |
|---|---|---|---|
| `zellia.breakpoint.mobile` | `0px` | 0 – 767px | 4 columnas |
| `zellia.breakpoint.tablet` | `768px` | 768 – 1023px | 8 columnas |
| `zellia.breakpoint.desktop` | `1024px` | 1024px en adelante | 12 columnas |

El sistema es **mobile-first**: el grid de 4 columnas es el estado base y los otros dos se aplican con `min-width`.

### 7.2 Especificación de los tres grids

| Propiedad | Mobile | Tablet | Desktop |
|---|---|---|---|
| **Recuento** (columnas) | `4` | `8` | `12` |
| **Margen** (lateral) | `16px` | `32px` | `48px` |
| **Gutter** (canal) | `16px` | `24px` | `24px` |
| **Tipo** | Estirar | Estirar | Estirar |
| **Ancho** | Automático | Automático | Automático |
| **Ancho máximo de contenido** | — | — | `1440px` |

Todos los valores son **múltiplos de 4** y existen ya en la escala de spacing: `space.16`, `space.24`, `space.32`, `space.48`. El grid no introduce medidas nuevas al sistema.

Tokens correspondientes:

```
zellia.grid.mobile.columns   = 4     zellia.grid.mobile.margin  = 16px
zellia.grid.tablet.columns   = 8     zellia.grid.tablet.margin  = 32px
zellia.grid.desktop.columns  = 12    zellia.grid.desktop.margin = 48px

zellia.grid.mobile.gutter    = 16px
zellia.grid.tablet.gutter    = 24px
zellia.grid.desktop.gutter   = 24px
zellia.grid.desktop.maxWidth = 1440px
```

### 7.3 Criterio del grid de Tablet

El grid de Tablet se deriva de los otros dos. Estas son las razones de cada valor:

**8 columnas.** Es el único recuento que mantiene la divisibilidad del sistema en los tres breakpoints. 12, 8 y 4 comparten los divisores 2 y 4, así que un layout de dos o de cuatro columnas funciona en cualquier pantalla sin recalcular nada. Además 8 es exactamente ⅔ de 12: un bloque que ocupa 4 columnas en Desktop (un tercio) ocupa 4 en Tablet (la mitad), lo que convierte una rejilla de 3 elementos en una de 2 sin intervención manual. Con 6 columnas se pierde la división en cuartos; con 10 se pierde la división en tercios.

**Margen de 32px.** Es el punto medio exacto entre los 16px de Mobile y los 48px de Desktop, y corresponde a `space.32` de la escala. El margen debe crecer con el ancho de pantalla porque su función es impedir que el contenido toque el borde físico del dispositivo: en una tableta sujeta con las manos, 16px deja el contenido bajo los pulgares, y 48px desperdicia ancho útil en un canvas que todavía es estrecho.

**Gutter de 24px, igual que Desktop.** El gutter no escala con la pantalla sino con la **densidad de contenido**. En Tablet se muestran tarjetas y tablas de complejidad equivalente a Desktop, no la lista de una sola columna de Mobile: mantener 24px preserva la separación visual entre elementos que el ojo necesita para leerlos como piezas distintas. Bajarlo a 16px haría que dos tarjetas contiguas se leyeran como un solo bloque.

**Sin ancho máximo.** El tope de 1440px solo aplica a Desktop, porque ninguna tableta en orientación horizontal supera ese ancho. Imponerlo en Tablet no tendría efecto.

### 7.4 Ancho de columna calculado

Anchos resultantes en viewports de referencia. El valor es informativo: las columnas se calculan con `1fr`, nunca se codifican en px.

| Viewport | Breakpoint | Contenido | Columna | Gutter |
|---|---|---|---|---|
| 320px | Mobile | 288px | 60.0px | 16px |
| 375px | Mobile | 343px | 73.75px | 16px |
| 768px | Tablet | 704px | 67.0px | 24px |
| 1024px | Tablet | 960px | 99.0px | 24px |
| 1280px | Desktop | 1184px | 76.67px | 24px |
| 1440px | Desktop | 1344px | 90.0px | 24px |

En 768px la columna es más estrecha que en 375px. Es correcto y esperado: en Tablet el contenido se reparte entre 8 columnas en lugar de 4, y los elementos ocupan varias columnas a la vez, no una.

### 7.5 Mapeo de columnas entre breakpoints

Traducción directa de los patrones de layout más frecuentes. Esta tabla es la referencia para pasar un diseño de Desktop a Tablet y Mobile sin improvisar.

| Patrón | Desktop (12) | Tablet (8) | Mobile (4) |
|---|---|---|---|
| Ancho completo | 12 | 8 | 4 |
| Dos columnas | 6 + 6 | 4 + 4 | 4 (apilado) |
| Tres columnas | 4 + 4 + 4 | 4 + 4 → 2 filas | 4 (apilado) |
| Cuatro columnas | 3 + 3 + 3 + 3 | 2 + 2 + 2 + 2 | 2 + 2 |
| Contenido + aside | 8 + 4 | 8 (aside baja) | 4 (aside baja) |
| Sidebar + contenido | 3 + 9 | 8 (sidebar off-canvas) | 4 (sidebar off-canvas) |
| Formulario centrado | 4 (offset 4) | 6 (offset 1) | 4 |
| Contenido de lectura | 8 (offset 2) | 8 | 4 |

### 7.6 Reglas del sistema

1. **El grid es para posicionar, el spacing para separar.** El gutter resuelve la separación horizontal entre columnas; la separación vertical se resuelve con tokens de `space`. No se usan columnas vacías como espaciador.
2. **Ningún elemento ocupa fracciones de columna.** Todo bloque abarca un número entero de columnas.
3. **El margen es intocable.** Solo los fondos a sangre (imágenes hero, barras de color, carruseles con scroll horizontal) pueden extenderse hasta el borde; el contenido, nunca.
4. **Los tres grids se verifican en cada diseño.** Un layout no está terminado hasta estar resuelto en los tres breakpoints.
5. **Alinear al grid, no al píxel.** Si un elemento no calza en la rejilla, se reconsidera el layout antes que desalinearlo.
6. **Por encima de 1440px el grid se centra.** El contenido mantiene su ancho máximo y los márgenes laterales crecen. Sin este tope, en pantallas ultra anchas las columnas se estiran y las líneas de texto superan los 75 caracteres que fija §2.6.
7. **Las anidaciones reinician la cuenta.** Un contenedor de 6 columnas que aloja su propia rejilla interna vuelve a contar desde 1; sus hijos no se refieren al grid de la página.
8. **Un cambio de breakpoint nunca oculta contenido.** Reordena, apila o mueve a off-canvas, pero toda la información sigue accesible.

### 7.7 Configuración en Figma

Valores a cargar como Layout Grid en cada frame:

| Campo | Mobile | Tablet | Desktop |
|---|---|---|---|
| Tipo de grid | Columnas | Columnas | Columnas |
| Recuento | 4 | 8 | 12 |
| Tipo | Estirar | Estirar | Estirar |
| Ancho | Automático | Automático | Automático |
| Margen | 16 | 32 | 48 |
| Gutter | 16 | 24 | 24 |
| Color de guía | `#F7B7C5` · 30% | `#F7B7C5` · 30% | `#F7B7C5` · 25% |

`#F7B7C5` es el color de la guía visual de Figma, **no un token del sistema**. No aparece en el CSS ni en el JSON porque no se renderiza en producción: solo existe como overlay de trabajo.

### 7.8 Implementación

```css
/* Mobile-first: el grid base es el de 4 columnas */
:root {
  --zellia-grid-columns:   4;
  --zellia-grid-margin:    var(--zellia-space-16);
  --zellia-grid-gutter:    var(--zellia-space-16);
  --zellia-grid-max-width: none;
}

@media (min-width: 768px) {
  :root {
    --zellia-grid-columns: 8;
    --zellia-grid-margin:  var(--zellia-space-32);
    --zellia-grid-gutter:  var(--zellia-space-24);
  }
}

@media (min-width: 1024px) {
  :root {
    --zellia-grid-columns:   12;
    --zellia-grid-margin:    var(--zellia-space-48);
    --zellia-grid-gutter:    var(--zellia-space-24);
    --zellia-grid-max-width: 1440px;
  }
}

.zellia-grid {
  display: grid;
  grid-template-columns: repeat(var(--zellia-grid-columns), 1fr);
  gap: var(--zellia-grid-gutter);
  padding-inline: var(--zellia-grid-margin);
  max-width: var(--zellia-grid-max-width);
  margin-inline: auto;
  box-sizing: border-box;
}
```

```html
<!-- Tres columnas en Desktop, dos en Tablet, apilado en Mobile -->
<div class="zellia-grid">
  <article style="grid-column: span 4">...</article>
  <article style="grid-column: span 4">...</article>
  <article style="grid-column: span 4">...</article>
</div>
```

---

## 8. Apéndice A — CSS completo

```css
:root {

  /* ============================================================
     COLOR — Lightmode
     ============================================================ */

  /* ---- Primario ---- */
  --zellia-color-primary-100: #F5F9FE;
  --zellia-color-primary-200: #E6EBEF;
  --zellia-color-primary-300: #AABFD6;
  --zellia-color-primary-400: #86A3C2;
  --zellia-color-primary-500: #6488AD;
  --zellia-color-primary-600: #436C96;
  --zellia-color-primary-700: #23527F;
  --zellia-color-primary-800: #023562; /* Brand Color */
  --zellia-color-primary-900: #002041;

  /* ---- Secundario ---- */
  --zellia-color-secondary-100: #EEF5F8;
  --zellia-color-secondary-200: #C8DEEA;
  --zellia-color-secondary-300: #A4C7DA;
  --zellia-color-secondary-400: #7FB1CA;
  --zellia-color-secondary-500: #5799BA; /* Brand Color */
  --zellia-color-secondary-600: #4A829E;
  --zellia-color-secondary-700: #3D6B82;
  --zellia-color-secondary-800: #305466;
  --zellia-color-secondary-900: #233D4A;

  /* ---- Acento ---- */
  --zellia-color-accent-100: #FFF6F2;
  --zellia-color-accent-200: #FFD2C5;
  --zellia-color-accent-300: #FFAC95;
  --zellia-color-accent-400: #FD8C62;
  --zellia-color-accent-500: #FC4501; /* Base */
  --zellia-color-accent-600: #D63B01;
  --zellia-color-accent-700: #B03001;
  --zellia-color-accent-800: #8B2601;
  --zellia-color-accent-900: #651C00;

  /* ---- Monocromático ---- */
  --zellia-color-neutral-100: #F8F8F8;
  --zellia-color-neutral-200: #E9E9E9;
  --zellia-color-neutral-300: #CECECE;
  --zellia-color-neutral-400: #ABABAB;
  --zellia-color-neutral-500: #7F7F7E;
  --zellia-color-neutral-600: #595958;
  --zellia-color-neutral-700: #3D3D3B;
  --zellia-color-neutral-800: #2D2D2B;
  --zellia-color-neutral-900: #222220;

  /* ---- Absolutos ---- */
  --zellia-color-common-white: #FFFFFF;
  --zellia-color-common-black: #000000;

  /* ---- Alert System ---- */
  --zellia-color-success-light: #EAFFF1;
  --zellia-color-success-base:  #22C55E;
  --zellia-color-success-dark:  #15803D;

  --zellia-color-warning-light: #FFF7D7;
  --zellia-color-warning-base:  #F59E0B;
  --zellia-color-warning-dark:  #92400E;

  --zellia-color-error-light:   #FFD7D7;
  --zellia-color-error-base:    #EF4444;
  --zellia-color-error-dark:    #991B1B;

  --zellia-color-info-light:    #E6F2FF;
  --zellia-color-info-base:     #2196F3;
  --zellia-color-info-dark:     #0D47A1;

  /* ============================================================
     TIPOGRAFÍA
     ============================================================ */

  --zellia-font-family-base: 'Inter', -apple-system, BlinkMacSystemFont,
                             'Segoe UI', Roboto, Helvetica, Arial, sans-serif;

  --zellia-font-weight-light:    300;
  --zellia-font-weight-regular:  400;
  --zellia-font-weight-medium:   500;
  --zellia-font-weight-semibold: 600;
  --zellia-font-weight-bold:     700;

  --zellia-font-size-display:              64px;
  --zellia-font-size-headline-marketing-1: 48px;
  --zellia-font-size-headline-marketing-2: 40px;
  --zellia-font-size-headline-marketing-3: 32px;
  --zellia-font-size-headline-1:           28px;
  --zellia-font-size-headline-2:           24px;
  --zellia-font-size-subtitle-1:           20px;
  --zellia-font-size-subtitle-2:           18px;
  --zellia-font-size-body-1:               16px;
  --zellia-font-size-body-2:               14px;
  --zellia-font-size-caption-1:            12px;
  --zellia-font-size-caption-2:            10px;
  --zellia-font-size-label:                12px;
  --zellia-font-size-button-giant:         18px;
  --zellia-font-size-button-large:         16px;
  --zellia-font-size-button-medium:        14px;
  --zellia-font-size-button-small:         12px;
  --zellia-font-size-button-tiny:          10px;

  --zellia-font-line-height-display:              72px;
  --zellia-font-line-height-headline-marketing-1: 56px;
  --zellia-font-line-height-headline-marketing-2: 48px;
  --zellia-font-line-height-headline-marketing-3: 40px;
  --zellia-font-line-height-headline-1:           36px;
  --zellia-font-line-height-headline-2:           32px;
  --zellia-font-line-height-subtitle-1:           28px;
  --zellia-font-line-height-subtitle-2:           24px;
  --zellia-font-line-height-body-1:               24px;
  --zellia-font-line-height-body-2:               20px;
  --zellia-font-line-height-caption-1:            16px;
  --zellia-font-line-height-caption-2:            14px;
  --zellia-font-line-height-label:                16px;
  --zellia-font-line-height-button-giant:         22px;
  --zellia-font-line-height-button-large:         18px;
  --zellia-font-line-height-button-medium:        16px;
  --zellia-font-line-height-button-small:         14px;
  --zellia-font-line-height-button-tiny:          12px;

  /* ============================================================
     SPACING — Grid base 4px
     ============================================================ */

  --zellia-space-0:  0px;
  --zellia-space-2:  2px;
  --zellia-space-4:  4px;
  --zellia-space-8:  8px;
  --zellia-space-12: 12px;
  --zellia-space-16: 16px;
  --zellia-space-20: 20px;
  --zellia-space-24: 24px;
  --zellia-space-32: 32px;
  --zellia-space-40: 40px;
  --zellia-space-48: 48px;
  --zellia-space-64: 64px;
  --zellia-space-80: 80px;
  --zellia-space-96: 96px;

  /* ============================================================
     BORDER RADIUS
     ============================================================ */

  --zellia-radius-none: 0px;
  --zellia-radius-sm:   4px;
  --zellia-radius-md:   8px;   /* Default del sistema */
  --zellia-radius-lg:   12px;
  --zellia-radius-xl:   16px;
  --zellia-radius-full: 9999px;

  /* ============================================================
     ELEVATION / SHADOWS
     ============================================================ */

  --zellia-shadow-none: none;
  --zellia-shadow-xs:   0 1px 2px 0 rgba(0, 32, 65, 0.06);
  --zellia-shadow-sm:   0 1px 2px 0 rgba(0, 32, 65, 0.06),
                        0 2px 4px -1px rgba(0, 32, 65, 0.08);
  --zellia-shadow-md:   0 2px 4px -1px rgba(0, 32, 65, 0.06),
                        0 4px 8px -2px rgba(0, 32, 65, 0.10);
  --zellia-shadow-lg:   0 4px 8px -2px rgba(0, 32, 65, 0.08),
                        0 8px 16px -4px rgba(0, 32, 65, 0.12);
  --zellia-shadow-xl:   0 8px 16px -4px rgba(0, 32, 65, 0.10),
                        0 16px 32px -8px rgba(0, 32, 65, 0.14);
  --zellia-shadow-2xl:  0 16px 32px -8px rgba(0, 32, 65, 0.12),
                        0 32px 64px -16px rgba(0, 32, 65, 0.18);
  --zellia-shadow-inner: inset 0 2px 4px 0 rgba(0, 32, 65, 0.06);

  --zellia-shadow-focus-primary: 0 0 0 4px rgba(2, 53, 98, 0.20);
  --zellia-shadow-focus-accent:  0 0 0 4px rgba(252, 69, 1, 0.20);
  --zellia-shadow-focus-error:   0 0 0 4px rgba(239, 68, 68, 0.20);
  --zellia-shadow-focus-success: 0 0 0 4px rgba(34, 197, 94, 0.20);

  --zellia-shadow-transition: box-shadow 150ms ease-out;

  /* ============================================================
     BUTTONS
     ============================================================ */

  --zellia-button-giant-height:  56px;
  --zellia-button-large-height:  48px;
  --zellia-button-medium-height: 40px;
  --zellia-button-small-height:  32px;
  --zellia-button-tiny-height:   24px;

  --zellia-button-giant-padding-x:  var(--zellia-space-24);
  --zellia-button-large-padding-x:  var(--zellia-space-20);
  --zellia-button-medium-padding-x: var(--zellia-space-16);
  --zellia-button-small-padding-x:  var(--zellia-space-12);
  --zellia-button-tiny-padding-x:   var(--zellia-space-8);

  --zellia-button-giant-gap:  var(--zellia-space-8);
  --zellia-button-large-gap:  var(--zellia-space-8);
  --zellia-button-medium-gap: var(--zellia-space-8);
  --zellia-button-small-gap:  var(--zellia-space-4);
  --zellia-button-tiny-gap:   var(--zellia-space-4);

  --zellia-button-giant-icon:  24px;
  --zellia-button-large-icon:  20px;
  --zellia-button-medium-icon: 16px;
  --zellia-button-small-icon:  16px;
  --zellia-button-tiny-icon:   12px;

  /* ============================================================
     BREAKPOINTS
     ============================================================ */

  --zellia-breakpoint-mobile:  0px;
  --zellia-breakpoint-tablet:  768px;
  --zellia-breakpoint-desktop: 1024px;

  /* ============================================================
     GRID — Mobile-first (base: 4 columnas)
     ============================================================ */

  --zellia-grid-columns:   4;
  --zellia-grid-margin:    var(--zellia-space-16);
  --zellia-grid-gutter:    var(--zellia-space-16);
  --zellia-grid-max-width: none;
}

@media (min-width: 768px) {
  :root {
    --zellia-grid-columns: 8;
    --zellia-grid-margin:  var(--zellia-space-32);
    --zellia-grid-gutter:  var(--zellia-space-24);
  }
}

@media (min-width: 1024px) {
  :root {
    --zellia-grid-columns:   12;
    --zellia-grid-margin:    var(--zellia-space-48);
    --zellia-grid-gutter:    var(--zellia-space-24);
    --zellia-grid-max-width: 1440px;
  }
}
```

### Contenedor de grid

```css
.zellia-grid {
  display: grid;
  grid-template-columns: repeat(var(--zellia-grid-columns), 1fr);
  gap: var(--zellia-grid-gutter);
  padding-inline: var(--zellia-grid-margin);
  max-width: var(--zellia-grid-max-width);
  margin-inline: auto;
  box-sizing: border-box;
}
```

### Clases de utilidad tipográfica

```css
/* ---- Roles ---- */
.zellia-display              { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-display);              line-height: var(--zellia-font-line-height-display); }
.zellia-headline-marketing-1 { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-headline-marketing-1); line-height: var(--zellia-font-line-height-headline-marketing-1); }
.zellia-headline-marketing-2 { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-headline-marketing-2); line-height: var(--zellia-font-line-height-headline-marketing-2); }
.zellia-headline-marketing-3 { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-headline-marketing-3); line-height: var(--zellia-font-line-height-headline-marketing-3); }
.zellia-headline-1           { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-headline-1);           line-height: var(--zellia-font-line-height-headline-1); }
.zellia-headline-2           { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-headline-2);           line-height: var(--zellia-font-line-height-headline-2); }
.zellia-subtitle-1           { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-subtitle-1);           line-height: var(--zellia-font-line-height-subtitle-1); }
.zellia-subtitle-2           { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-subtitle-2);           line-height: var(--zellia-font-line-height-subtitle-2); }
.zellia-body-1               { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-body-1);               line-height: var(--zellia-font-line-height-body-1); }
.zellia-body-2               { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-body-2);               line-height: var(--zellia-font-line-height-body-2); }
.zellia-caption-1            { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-caption-1);            line-height: var(--zellia-font-line-height-caption-1); }
.zellia-caption-2            { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-caption-2);            line-height: var(--zellia-font-line-height-caption-2); }
.zellia-label                { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-label);                line-height: var(--zellia-font-line-height-label); }
.zellia-button-giant         { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-button-giant);         line-height: var(--zellia-font-line-height-button-giant); }
.zellia-button-large         { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-button-large);         line-height: var(--zellia-font-line-height-button-large); }
.zellia-button-medium        { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-button-medium);        line-height: var(--zellia-font-line-height-button-medium); }
.zellia-button-small         { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-button-small);         line-height: var(--zellia-font-line-height-button-small); }
.zellia-button-tiny          { font-family: var(--zellia-font-family-base); font-size: var(--zellia-font-size-button-tiny);          line-height: var(--zellia-font-line-height-button-tiny); }

/* ---- Pesos ---- */
.zellia-w-light    { font-weight: var(--zellia-font-weight-light); }
.zellia-w-regular  { font-weight: var(--zellia-font-weight-regular); }
.zellia-w-medium   { font-weight: var(--zellia-font-weight-medium); }
.zellia-w-semibold { font-weight: var(--zellia-font-weight-semibold); }
.zellia-w-bold     { font-weight: var(--zellia-font-weight-bold); }
```

---

## 9. Apéndice B — JSON completo

Formato W3C Design Tokens. Listo para Style Dictionary, Tokens Studio o Figma Variables.

```json
{
  "zellia": {
    "color": {
      "primary": {
        "100": { "$type": "color", "$value": "#F5F9FE" },
        "200": { "$type": "color", "$value": "#E6EBEF" },
        "300": { "$type": "color", "$value": "#AABFD6" },
        "400": { "$type": "color", "$value": "#86A3C2" },
        "500": { "$type": "color", "$value": "#6488AD" },
        "600": { "$type": "color", "$value": "#436C96" },
        "700": { "$type": "color", "$value": "#23527F" },
        "800": { "$type": "color", "$value": "#023562", "$description": "Brand Color" },
        "900": { "$type": "color", "$value": "#002041" }
      },
      "secondary": {
        "100": { "$type": "color", "$value": "#EEF5F8" },
        "200": { "$type": "color", "$value": "#C8DEEA" },
        "300": { "$type": "color", "$value": "#A4C7DA" },
        "400": { "$type": "color", "$value": "#7FB1CA" },
        "500": { "$type": "color", "$value": "#5799BA", "$description": "Brand Color" },
        "600": { "$type": "color", "$value": "#4A829E" },
        "700": { "$type": "color", "$value": "#3D6B82" },
        "800": { "$type": "color", "$value": "#305466" },
        "900": { "$type": "color", "$value": "#233D4A" }
      },
      "accent": {
        "100": { "$type": "color", "$value": "#FFF6F2" },
        "200": { "$type": "color", "$value": "#FFD2C5" },
        "300": { "$type": "color", "$value": "#FFAC95" },
        "400": { "$type": "color", "$value": "#FD8C62" },
        "500": { "$type": "color", "$value": "#FC4501", "$description": "Base" },
        "600": { "$type": "color", "$value": "#D63B01" },
        "700": { "$type": "color", "$value": "#B03001" },
        "800": { "$type": "color", "$value": "#8B2601" },
        "900": { "$type": "color", "$value": "#651C00" }
      },
      "neutral": {
        "100": { "$type": "color", "$value": "#F8F8F8" },
        "200": { "$type": "color", "$value": "#E9E9E9" },
        "300": { "$type": "color", "$value": "#CECECE" },
        "400": { "$type": "color", "$value": "#ABABAB" },
        "500": { "$type": "color", "$value": "#7F7F7E" },
        "600": { "$type": "color", "$value": "#595958" },
        "700": { "$type": "color", "$value": "#3D3D3B" },
        "800": { "$type": "color", "$value": "#2D2D2B" },
        "900": { "$type": "color", "$value": "#222220" }
      },
      "common": {
        "white": { "$type": "color", "$value": "#FFFFFF" },
        "black": { "$type": "color", "$value": "#000000" }
      },
      "success": {
        "light": { "$type": "color", "$value": "#EAFFF1" },
        "base":  { "$type": "color", "$value": "#22C55E" },
        "dark":  { "$type": "color", "$value": "#15803D" }
      },
      "warning": {
        "light": { "$type": "color", "$value": "#FFF7D7" },
        "base":  { "$type": "color", "$value": "#F59E0B" },
        "dark":  { "$type": "color", "$value": "#92400E" }
      },
      "error": {
        "light": { "$type": "color", "$value": "#FFD7D7" },
        "base":  { "$type": "color", "$value": "#EF4444" },
        "dark":  { "$type": "color", "$value": "#991B1B" }
      },
      "info": {
        "light": { "$type": "color", "$value": "#E6F2FF" },
        "base":  { "$type": "color", "$value": "#2196F3" },
        "dark":  { "$type": "color", "$value": "#0D47A1" }
      }
    },
    "font": {
      "family": {
        "base": {
          "$type": "fontFamily",
          "$value": ["Inter", "-apple-system", "BlinkMacSystemFont", "Segoe UI", "Roboto", "Helvetica", "Arial", "sans-serif"]
        }
      },
      "weight": {
        "light":    { "$type": "fontWeight", "$value": 300 },
        "regular":  { "$type": "fontWeight", "$value": 400 },
        "medium":   { "$type": "fontWeight", "$value": 500 },
        "semibold": { "$type": "fontWeight", "$value": 600 },
        "bold":     { "$type": "fontWeight", "$value": 700 }
      },
      "size": {
        "display":            { "$type": "dimension", "$value": "64px" },
        "headlineMarketing1": { "$type": "dimension", "$value": "48px" },
        "headlineMarketing2": { "$type": "dimension", "$value": "40px" },
        "headlineMarketing3": { "$type": "dimension", "$value": "32px" },
        "headline1":          { "$type": "dimension", "$value": "28px" },
        "headline2":          { "$type": "dimension", "$value": "24px" },
        "subtitle1":          { "$type": "dimension", "$value": "20px" },
        "subtitle2":          { "$type": "dimension", "$value": "18px" },
        "body1":              { "$type": "dimension", "$value": "16px" },
        "body2":              { "$type": "dimension", "$value": "14px" },
        "caption1":           { "$type": "dimension", "$value": "12px" },
        "caption2":           { "$type": "dimension", "$value": "10px" },
        "label":              { "$type": "dimension", "$value": "12px" },
        "buttonGiant":        { "$type": "dimension", "$value": "18px" },
        "buttonLarge":        { "$type": "dimension", "$value": "16px" },
        "buttonMedium":       { "$type": "dimension", "$value": "14px" },
        "buttonSmall":        { "$type": "dimension", "$value": "12px" },
        "buttonTiny":         { "$type": "dimension", "$value": "10px" }
      },
      "lineHeight": {
        "display":            { "$type": "dimension", "$value": "72px" },
        "headlineMarketing1": { "$type": "dimension", "$value": "56px" },
        "headlineMarketing2": { "$type": "dimension", "$value": "48px" },
        "headlineMarketing3": { "$type": "dimension", "$value": "40px" },
        "headline1":          { "$type": "dimension", "$value": "36px" },
        "headline2":          { "$type": "dimension", "$value": "32px" },
        "subtitle1":          { "$type": "dimension", "$value": "28px" },
        "subtitle2":          { "$type": "dimension", "$value": "24px" },
        "body1":              { "$type": "dimension", "$value": "24px" },
        "body2":              { "$type": "dimension", "$value": "20px" },
        "caption1":           { "$type": "dimension", "$value": "16px" },
        "caption2":           { "$type": "dimension", "$value": "14px" },
        "label":              { "$type": "dimension", "$value": "16px" },
        "buttonGiant":        { "$type": "dimension", "$value": "22px" },
        "buttonLarge":        { "$type": "dimension", "$value": "18px" },
        "buttonMedium":       { "$type": "dimension", "$value": "16px" },
        "buttonSmall":        { "$type": "dimension", "$value": "14px" },
        "buttonTiny":         { "$type": "dimension", "$value": "12px" }
      }
    },
    "typography": {
      "display": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.display}", "lineHeight": "{zellia.font.lineHeight.display}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.display}", "lineHeight": "{zellia.font.lineHeight.display}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.display}", "lineHeight": "{zellia.font.lineHeight.display}" } }
      },
      "headlineMarketing1": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.headlineMarketing1}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing1}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.headlineMarketing1}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing1}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.headlineMarketing1}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing1}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.headlineMarketing1}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing1}" } }
      },
      "headlineMarketing2": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.headlineMarketing2}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing2}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.headlineMarketing2}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing2}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.headlineMarketing2}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing2}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.headlineMarketing2}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing2}" } }
      },
      "headlineMarketing3": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.headlineMarketing3}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing3}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.headlineMarketing3}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing3}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.headlineMarketing3}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing3}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.headlineMarketing3}", "lineHeight": "{zellia.font.lineHeight.headlineMarketing3}" } }
      },
      "headline1": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.headline1}", "lineHeight": "{zellia.font.lineHeight.headline1}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.headline1}", "lineHeight": "{zellia.font.lineHeight.headline1}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.headline1}", "lineHeight": "{zellia.font.lineHeight.headline1}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.headline1}", "lineHeight": "{zellia.font.lineHeight.headline1}" } }
      },
      "headline2": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.headline2}", "lineHeight": "{zellia.font.lineHeight.headline2}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.headline2}", "lineHeight": "{zellia.font.lineHeight.headline2}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.headline2}", "lineHeight": "{zellia.font.lineHeight.headline2}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.headline2}", "lineHeight": "{zellia.font.lineHeight.headline2}" } }
      },
      "subtitle1": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.subtitle1}", "lineHeight": "{zellia.font.lineHeight.subtitle1}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.subtitle1}", "lineHeight": "{zellia.font.lineHeight.subtitle1}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.subtitle1}", "lineHeight": "{zellia.font.lineHeight.subtitle1}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.subtitle1}", "lineHeight": "{zellia.font.lineHeight.subtitle1}" } }
      },
      "subtitle2": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.subtitle2}", "lineHeight": "{zellia.font.lineHeight.subtitle2}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.subtitle2}", "lineHeight": "{zellia.font.lineHeight.subtitle2}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.subtitle2}", "lineHeight": "{zellia.font.lineHeight.subtitle2}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.subtitle2}", "lineHeight": "{zellia.font.lineHeight.subtitle2}" } }
      },
      "body1": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.body1}", "lineHeight": "{zellia.font.lineHeight.body1}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.body1}", "lineHeight": "{zellia.font.lineHeight.body1}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.body1}", "lineHeight": "{zellia.font.lineHeight.body1}" } },
        "bold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.bold}", "fontSize": "{zellia.font.size.body1}", "lineHeight": "{zellia.font.lineHeight.body1}" } }
      },
      "body2": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.body2}", "lineHeight": "{zellia.font.lineHeight.body2}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.body2}", "lineHeight": "{zellia.font.lineHeight.body2}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.body2}", "lineHeight": "{zellia.font.lineHeight.body2}" } }
      },
      "caption1": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.caption1}", "lineHeight": "{zellia.font.lineHeight.caption1}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.caption1}", "lineHeight": "{zellia.font.lineHeight.caption1}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.caption1}", "lineHeight": "{zellia.font.lineHeight.caption1}" } }
      },
      "caption2": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.caption2}", "lineHeight": "{zellia.font.lineHeight.caption2}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.caption2}", "lineHeight": "{zellia.font.lineHeight.caption2}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.caption2}", "lineHeight": "{zellia.font.lineHeight.caption2}" } }
      },
      "label": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.label}", "lineHeight": "{zellia.font.lineHeight.label}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.label}", "lineHeight": "{zellia.font.lineHeight.label}" } }
      },
      "buttonGiant": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.buttonGiant}", "lineHeight": "{zellia.font.lineHeight.buttonGiant}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.buttonGiant}", "lineHeight": "{zellia.font.lineHeight.buttonGiant}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.buttonGiant}", "lineHeight": "{zellia.font.lineHeight.buttonGiant}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.buttonGiant}", "lineHeight": "{zellia.font.lineHeight.buttonGiant}" } }
      },
      "buttonLarge": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.buttonLarge}", "lineHeight": "{zellia.font.lineHeight.buttonLarge}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.buttonLarge}", "lineHeight": "{zellia.font.lineHeight.buttonLarge}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.buttonLarge}", "lineHeight": "{zellia.font.lineHeight.buttonLarge}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.buttonLarge}", "lineHeight": "{zellia.font.lineHeight.buttonLarge}" } }
      },
      "buttonMedium": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.buttonMedium}", "lineHeight": "{zellia.font.lineHeight.buttonMedium}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.buttonMedium}", "lineHeight": "{zellia.font.lineHeight.buttonMedium}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.buttonMedium}", "lineHeight": "{zellia.font.lineHeight.buttonMedium}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.buttonMedium}", "lineHeight": "{zellia.font.lineHeight.buttonMedium}" } }
      },
      "buttonSmall": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.buttonSmall}", "lineHeight": "{zellia.font.lineHeight.buttonSmall}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.buttonSmall}", "lineHeight": "{zellia.font.lineHeight.buttonSmall}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.buttonSmall}", "lineHeight": "{zellia.font.lineHeight.buttonSmall}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.buttonSmall}", "lineHeight": "{zellia.font.lineHeight.buttonSmall}" } }
      },
      "buttonTiny": {
        "light": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.light}", "fontSize": "{zellia.font.size.buttonTiny}", "lineHeight": "{zellia.font.lineHeight.buttonTiny}" } },
        "regular": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.regular}", "fontSize": "{zellia.font.size.buttonTiny}", "lineHeight": "{zellia.font.lineHeight.buttonTiny}" } },
        "medium": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.medium}", "fontSize": "{zellia.font.size.buttonTiny}", "lineHeight": "{zellia.font.lineHeight.buttonTiny}" } },
        "semibold": { "$type": "typography", "$value": { "fontFamily": "{zellia.font.family.base}", "fontWeight": "{zellia.font.weight.semibold}", "fontSize": "{zellia.font.size.buttonTiny}", "lineHeight": "{zellia.font.lineHeight.buttonTiny}" } }
      }
    },
    "space": {
      "0":  { "$type": "dimension", "$value": "0px" },
      "2":  { "$type": "dimension", "$value": "2px" },
      "4":  { "$type": "dimension", "$value": "4px" },
      "8":  { "$type": "dimension", "$value": "8px",  "$description": "Valor por defecto" },
      "12": { "$type": "dimension", "$value": "12px" },
      "16": { "$type": "dimension", "$value": "16px", "$description": "Valor por defecto" },
      "20": { "$type": "dimension", "$value": "20px" },
      "24": { "$type": "dimension", "$value": "24px" },
      "32": { "$type": "dimension", "$value": "32px" },
      "40": { "$type": "dimension", "$value": "40px" },
      "48": { "$type": "dimension", "$value": "48px" },
      "64": { "$type": "dimension", "$value": "64px" },
      "80": { "$type": "dimension", "$value": "80px" },
      "96": { "$type": "dimension", "$value": "96px" }
    },
    "radius": {
      "none": { "$type": "dimension", "$value": "0px" },
      "sm":   { "$type": "dimension", "$value": "4px" },
      "md":   { "$type": "dimension", "$value": "8px", "$description": "Default del sistema" },
      "lg":   { "$type": "dimension", "$value": "12px" },
      "xl":   { "$type": "dimension", "$value": "16px" },
      "full": { "$type": "dimension", "$value": "9999px" }
    },
    "shadow": {
      "none":  { "$type": "shadow", "$value": "none" },
      "xs":    { "$type": "shadow", "$value": "0 1px 2px 0 rgba(0, 32, 65, 0.06)" },
      "sm":    { "$type": "shadow", "$value": "0 1px 2px 0 rgba(0, 32, 65, 0.06), 0 2px 4px -1px rgba(0, 32, 65, 0.08)" },
      "md":    { "$type": "shadow", "$value": "0 2px 4px -1px rgba(0, 32, 65, 0.06), 0 4px 8px -2px rgba(0, 32, 65, 0.10)" },
      "lg":    { "$type": "shadow", "$value": "0 4px 8px -2px rgba(0, 32, 65, 0.08), 0 8px 16px -4px rgba(0, 32, 65, 0.12)" },
      "xl":    { "$type": "shadow", "$value": "0 8px 16px -4px rgba(0, 32, 65, 0.10), 0 16px 32px -8px rgba(0, 32, 65, 0.14)" },
      "2xl":   { "$type": "shadow", "$value": "0 16px 32px -8px rgba(0, 32, 65, 0.12), 0 32px 64px -16px rgba(0, 32, 65, 0.18)" },
      "inner": { "$type": "shadow", "$value": "inset 0 2px 4px 0 rgba(0, 32, 65, 0.06)" },
      "focus": {
        "primary": { "$type": "shadow", "$value": "0 0 0 4px rgba(2, 53, 98, 0.20)" },
        "accent":  { "$type": "shadow", "$value": "0 0 0 4px rgba(252, 69, 1, 0.20)" },
        "error":   { "$type": "shadow", "$value": "0 0 0 4px rgba(239, 68, 68, 0.20)" },
        "success": { "$type": "shadow", "$value": "0 0 0 4px rgba(34, 197, 94, 0.20)" }
      }
    },
    "button": {
      "giant": {
        "height":     { "$type": "dimension", "$value": "56px" },
        "paddingX":   { "$type": "dimension", "$value": "{zellia.space.24}" },
        "gap":        { "$type": "dimension", "$value": "{zellia.space.8}" },
        "iconSize":   { "$type": "dimension", "$value": "24px" },
        "radius":     { "$type": "dimension", "$value": "{zellia.radius.md}" },
        "typography": { "$type": "typography", "$value": "{zellia.typography.buttonGiant.semibold}" }
      },
      "large": {
        "height":     { "$type": "dimension", "$value": "48px" },
        "paddingX":   { "$type": "dimension", "$value": "{zellia.space.20}" },
        "gap":        { "$type": "dimension", "$value": "{zellia.space.8}" },
        "iconSize":   { "$type": "dimension", "$value": "20px" },
        "radius":     { "$type": "dimension", "$value": "{zellia.radius.md}" },
        "typography": { "$type": "typography", "$value": "{zellia.typography.buttonLarge.semibold}" }
      },
      "medium": {
        "height":     { "$type": "dimension", "$value": "40px" },
        "paddingX":   { "$type": "dimension", "$value": "{zellia.space.16}" },
        "gap":        { "$type": "dimension", "$value": "{zellia.space.8}" },
        "iconSize":   { "$type": "dimension", "$value": "16px" },
        "radius":     { "$type": "dimension", "$value": "{zellia.radius.md}" },
        "typography": { "$type": "typography", "$value": "{zellia.typography.buttonMedium.semibold}" },
        "$description": "Tamaño por defecto del sistema"
      },
      "small": {
        "height":     { "$type": "dimension", "$value": "32px" },
        "paddingX":   { "$type": "dimension", "$value": "{zellia.space.12}" },
        "gap":        { "$type": "dimension", "$value": "{zellia.space.4}" },
        "iconSize":   { "$type": "dimension", "$value": "16px" },
        "radius":     { "$type": "dimension", "$value": "{zellia.radius.sm}" },
        "typography": { "$type": "typography", "$value": "{zellia.typography.buttonSmall.semibold}" },
        "$description": "Requiere área táctil expandida a 44px en touch"
      },
      "tiny": {
        "height":     { "$type": "dimension", "$value": "24px" },
        "paddingX":   { "$type": "dimension", "$value": "{zellia.space.8}" },
        "gap":        { "$type": "dimension", "$value": "{zellia.space.4}" },
        "iconSize":   { "$type": "dimension", "$value": "12px" },
        "radius":     { "$type": "dimension", "$value": "{zellia.radius.sm}" },
        "typography": { "$type": "typography", "$value": "{zellia.typography.buttonTiny.semibold}" },
        "$description": "Solo escritorio. No se usa como acción principal ni destructiva"
      }
    },
    "breakpoint": {
      "mobile":  { "$type": "dimension", "$value": "0px" },
      "tablet":  { "$type": "dimension", "$value": "768px" },
      "desktop": { "$type": "dimension", "$value": "1024px" }
    },
    "grid": {
      "mobile": {
        "columns": { "$type": "number",    "$value": 4 },
        "margin":  { "$type": "dimension", "$value": "{zellia.space.16}" },
        "gutter":  { "$type": "dimension", "$value": "{zellia.space.16}" }
      },
      "tablet": {
        "columns": { "$type": "number",    "$value": 8 },
        "margin":  { "$type": "dimension", "$value": "{zellia.space.32}" },
        "gutter":  { "$type": "dimension", "$value": "{zellia.space.24}" }
      },
      "desktop": {
        "columns":  { "$type": "number",    "$value": 12 },
        "margin":   { "$type": "dimension", "$value": "{zellia.space.48}" },
        "gutter":   { "$type": "dimension", "$value": "{zellia.space.24}" },
        "maxWidth": { "$type": "dimension", "$value": "1440px" }
      }
    }
  }
}
```

---

## 10. Inventario de tokens

| Categoría | Tokens | Detalle |
|---|---|---|
| **Color** | 50 | Primario 9 · Secundario 9 · Acento 9 · Monocromático 9 · Absolutos 2 · Alert System 12 |
| **Tipografía — primitivos** | 41 | 1 familia · 5 pesos · 18 tamaños · 18 line-heights |
| **Tipografía — compuestos** | 66 | Matriz rol × peso |
| **Spacing** | 14 | Grid base 4px |
| **Border Radius** | 6 | none · sm · md · lg · xl · full |
| **Elevation / Shadows** | 13 | 8 elevación · 4 focus rings · 1 transición |
| **Buttons** | 25 | 5 alturas · 5 padding · 5 gap · 5 ícono · 5 radius |
| **Breakpoints** | 3 | mobile · tablet · desktop |
| **Grid** | 10 | 3 recuentos · 3 márgenes · 3 gutters · 1 ancho máximo |
| **Total** | **228** | |

---

*Zellia Design System · Foundations · Token Reference v1.1*
