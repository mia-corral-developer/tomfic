# Zellia — Component Specs

**Sidebar · v1.0**
2026-09-16

Especificación de construcción de los componentes del Sidebar de Zellia, extraída del archivo de Figma `Finco`, página `Zellia Mockups`.

---

## Índice

0. [Alcance y convenciones](#0-alcance-y-convenciones)
1. [ButtonSidebar](#1-buttonsidebar)
2. [SiderZellia](#2-siderzellia)
3. [Elementos pendientes de comportamiento](#3-elementos-pendientes-de-comportamiento)
4. [Apéndice A — Normalizaciones aplicadas](#4-apéndice-a--normalizaciones-aplicadas)
5. [Apéndice B — Inventario de tokens usados](#5-apéndice-b--inventario-de-tokens-usados)
6. [Apéndice C — Nomenclatura de capas](#6-apéndice-c--nomenclatura-de-capas)

---

## 0. Alcance y convenciones

### 0.1 Qué cubre este documento

Dos Component Sets y su relación de composición:

| Componente | Tipo | Variantes | Rol |
|---|---|---|---|
| `ButtonSidebar` | Component Set × 2 (Light / Dark) | 3 estados cada uno | Ítem de navegación |
| `SiderZellia` | Component Set | 2 temas | Contenedor del Sidebar |

`SiderZellia` consume 9 instancias de `ButtonSidebar`. Documentar uno sin el otro dejaría la especificación incompleta, por eso van juntos.

### 0.2 Relación con el Design System

Este documento especifica **componentes**. Los valores de color, tipografía, spacing y radius provienen del documento `Zellia Design System — Foundations`. Cuando un valor corresponde a un token, se indica en dos formatos:

| Formato | Ejemplo | Dónde se usa |
|---|---|---|
| Variable de Figma | `Zellia/Mono/100` | Archivo de diseño |
| Token del sistema | `zellia.color.neutral.100` | Código |

### 0.3 Convenciones de lectura

- **Padding** se expresa en orden `arriba / derecha / abajo / izquierda`.
- **Sizing** se expresa como `horizontal / vertical`, con los valores `FIXED`, `HUG` y `FILL`.
- Todas las medidas están en **px**.
- Los valores marcados **normalizado** difieren del archivo actual. El Apéndice A los lista con su justificación.

### 0.4 Temas

El sistema tiene dos temas: **Light** y **Dark**. Ambos comparten estructura, medidas, spacing y comportamiento; difieren únicamente en color.

---

## 1. ButtonSidebar

### 1.1 Identificación

| | Light | Dark |
|---|---|---|
| Nombre | `ButtonSidebar(Light)/Zellia` | `ButtonSidebar(Dark)/Zellia` |
| Tipo | Component Set | Component Set |
| Eje de variantes | `State` | `State` |
| Valores | `Default` · `Hover` · `Active` | `Default` · `Hover` · `Active` |
| Variante por defecto | `Active` | `Active` |

`Active` representa el ítem correspondiente a la ruta actual de la aplicación. No existe variante `Disabled`.

### 1.2 Anatomía

Cuatro capas en orden fijo, idénticas en los seis variantes:

| # | Capa | Tipo | Medidas | Visible por defecto | Contenido |
|---|---|---|---|---|---|
| 1 | `Left Icon` | Instance | `24 × 24` | Sí | Slot de ícono — ver §1.8 |
| 2 | `Mostrar Imagen` | Rectangle | `24 × 24` | No | Relleno tipo imagen (avatar / logo) |
| 3 | `Text` | Text | hug | Sí | Etiqueta del ítem |
| 4 | `Right Icon` | Instance | `24 × 24` | No | Slot de ícono secundario |

### 1.3 Medidas y layout

| Propiedad | Valor | Token |
|---|---|---|
| Auto Layout | Horizontal | — |
| Padding | `8 / 16 / 8 / 8` | `space.8` · `space.16` |
| Gap | `8` | `space.8` |
| Alineación principal | `MIN` (izquierda) | — |
| Alineación transversal | `CENTER` | — |
| Border radius | `8` | `radius.md` |
| **Alto** | `40` | `button.medium.height` |
| Sizing aislado | `HUG / HUG` | — |
| Sizing dentro del Sidebar | `FILL / HUG` → `288 × 40` | — |
| Stroke | ninguno | — |
| Sombra | ninguna | — |

El padding es deliberadamente asimétrico: `8` a la izquierda porque el ícono ya aporta su propio peso visual, y `16` a la derecha para que la etiqueta no quede pegada al borde cuando el botón se estira a `FILL`.

**Composición vertical:** `8 + 24 + 8 = 40`. El alto es consecuencia directa del ícono de 24px más el padding, no un valor arbitrario.

### 1.4 Tipografía

| Propiedad | Valor | Token |
|---|---|---|
| Familia | `Inter` | `font.family.base` |
| Tamaño | `16` | `font.size.buttonLarge` |
| Line height | `18` | `font.lineHeight.buttonLarge` |
| Letter spacing | `0%` | — |
| Text case | `ORIGINAL` | — |
| Alineación | `LEFT` / `TOP` | — |
| Auto resize | `WIDTH_AND_HEIGHT` | — |
| Peso — `Default` y `Hover` | `Regular 400` | `font.weight.regular` |
| Peso — `Active` (Light) | `Medium 500` | `font.weight.medium` |
| Peso — `Active` (Dark) | `Regular 400` | `font.weight.regular` |

El estilo compuesto corresponde a `zellia.typography.buttonLarge.regular` y, en Light/Active, a `zellia.typography.buttonLarge.medium`.

### 1.5 Colores — Light

| Estado | Fondo | Variable Figma | Texto e ícono | Variable Figma |
|---|---|---|---|---|
| `Default` | *sin relleno* | — | `#2D2D2B` | `Zellia/Mono/800` |
| `Hover` | `#C8DEEA` | `Zellia/Secondary/200` | `#2D2D2B` | `Zellia/Mono/800` |
| `Active` | `#5799BA` | `Zellia/Secondary/500 - Brand Color` | `#F8F8F8` | `Zellia/Mono/100` |

### 1.6 Colores — Dark

| Estado | Fondo | Variable Figma | Texto e ícono | Variable Figma |
|---|---|---|---|---|
| `Default` | *sin relleno* | — | `#F8F8F8` | `Zellia/Mono/100` |
| `Hover` | `#86A3C2` al `65%` | *sin variable vinculada* | `#F8F8F8` | `Zellia/Mono/100` **normalizado** |
| `Active` | `#023562` | `Zellia/Primary/800 - Brand Color` | `#F8F8F8` | `Zellia/Mono/100` |

El fondo de `Hover` en Dark es el único color del componente que no está vinculado a una variable. Se documenta tal como está.

### 1.7 Propiedades de componente

**Existentes en el archivo:**

| Propiedad | Tipo | Valor por defecto | Controla |
|---|---|---|---|
| `State` | Variant | `Active` | Estado visual |
| `Text` | Text | `"Dashboard"` | Etiqueta |
| `Left Icon` | Boolean | `true` | Visibilidad de la capa 1 |
| `Right Icon` | Boolean | `false` | Visibilidad de la capa 4 |
| `Mostrar Imagen` | Boolean | `false` | Visibilidad de la capa 2 |

**A implementar:**

| Propiedad | Tipo | Valor por defecto | Capa | Nivel de declaración |
|---|---|---|---|---|
| `Left Icon Swap` | Instance Swap | `nav-arrow-left` | `Left Icon` | **Component Set** |

#### Por qué el Swap debe declararse en el Component Set

Si la propiedad de Instance Swap se define dentro de cada variante por separado, el ícono elegido por el usuario **se pierde al cambiar de estado**: al pasar de `Default` a `Hover` o a `Active`, Figma resuelve la nueva variante con su valor original y descarta el swap.

Declarada a nivel de Component Set, la propiedad es una sola para las tres variantes y el valor persiste durante todo el ciclo de interacción. Este es el requisito que hace que el componente se comporte de forma dinámica y no como tres piezas independientes.

La misma regla aplica a los Booleans ya existentes, que están correctamente declarados a nivel de Set.

### 1.8 El ícono como slot

El chevron que aparece en el `Left Icon` **no es el ícono definitivo del componente**. Es un marcador de posición.

Lo que la especificación fija es el **slot**, no su contenido:

| Aspecto del slot | Valor |
|---|---|
| Caja | `24 × 24`, `FIXED / FIXED` |
| Posición | Primer elemento del Auto Layout |
| Separación respecto al texto | `8` (`space.8`) |
| Color en Light — `Default` y `Hover` | `Zellia/Mono/800` |
| Color en Light — `Active` | `Zellia/Mono/100` |
| Color en Dark — los tres estados | `Zellia/Mono/100` |
| Grosor de trazo del vector | `1.5`, alineación `CENTER` |

El ícono real se asigna por instancia mediante `Left Icon Swap`. Cada ítem de navegación usa el suyo, y el color lo hereda del estado — no se define en el ícono.

### 1.9 Interacción y animación

| Propiedad | Valor |
|---|---|
| Trigger | `While hovering` |
| Acción | `Change to` |
| Origen → destino | `State=Default` → `State=Hover` |
| Tipo de transición | **`Dissolve`** |
| Easing | `Ease Out` |
| Duración | **`300ms`** |

Tres comportamientos derivados:

1. **La transición está declarada solo en la variante `Default`.** Es la única con reacción asignada.
2. **El retorno es automático.** `While hovering` revierte a `Default` al salir el cursor, aplicando la misma animación a la inversa. No requiere una segunda transición.
3. **`Active` no participa de la animación.** No se alcanza por interacción del puntero, sino porque la ruta activa de la aplicación coincide con ese ítem. Lo determina el router, no el hover.

### 1.10 Mapeo completo a tokens

| Medida del componente | Valor | Token del sistema |
|---|---|---|
| Alto | `40` | `zellia.button.medium.height` |
| Border radius | `8` | `zellia.radius.md` |
| Gap interno | `8` | `zellia.space.8` |
| Padding superior / inferior / izquierdo | `8` | `zellia.space.8` |
| Padding derecho | `16` | `zellia.space.16` |
| Tamaño de texto | `16` | `zellia.font.size.buttonLarge` |
| Line height | `18` | `zellia.font.lineHeight.buttonLarge` |
| Caja del ícono | `24` | — |

### 1.11 Implementación

```css
.zellia-sidebar-item {
  display: inline-flex;
  align-items: center;
  gap: var(--zellia-space-8);
  height: 40px;
  padding: var(--zellia-space-8) var(--zellia-space-16)
           var(--zellia-space-8) var(--zellia-space-8);
  border-radius: var(--zellia-radius-md);
  font-family: var(--zellia-font-family-base);
  font-size: var(--zellia-font-size-button-large);
  line-height: var(--zellia-font-line-height-button-large);
  font-weight: var(--zellia-font-weight-regular);
  background: transparent;
  width: 100%;
  box-sizing: border-box;
  cursor: pointer;
  transition: background-color 300ms ease-out, color 300ms ease-out;
}

.zellia-sidebar-item__icon {
  width: 24px;
  height: 24px;
  flex: 0 0 auto;
}

/* ---- Light ---- */
.zellia-theme-light .zellia-sidebar-item {
  color: var(--zellia-color-neutral-800);
}
.zellia-theme-light .zellia-sidebar-item:hover {
  background: var(--zellia-color-secondary-200);
}
.zellia-theme-light .zellia-sidebar-item[aria-current='page'] {
  background: var(--zellia-color-secondary-500);
  color: var(--zellia-color-neutral-100);
  font-weight: var(--zellia-font-weight-medium);
}

/* ---- Dark ---- */
.zellia-theme-dark .zellia-sidebar-item {
  color: var(--zellia-color-neutral-100);
}
.zellia-theme-dark .zellia-sidebar-item:hover {
  background: rgba(134, 163, 194, 0.65);
}
.zellia-theme-dark .zellia-sidebar-item[aria-current='page'] {
  background: var(--zellia-color-primary-800);
  color: var(--zellia-color-neutral-100);
}

@media (prefers-reduced-motion: reduce) {
  .zellia-sidebar-item { transition: none; }
}
```

El estado `Active` se expresa con `aria-current="page"` en lugar de una clase: comunica el estado a los lectores de pantalla y evita duplicar la fuente de verdad, ya que el router es quien determina la ruta activa.

---

## 2. SiderZellia

### 2.1 Identificación

| Propiedad | Valor |
|---|---|
| Nombre | `SiderZellia` |
| Tipo | Component Set |
| Eje de variantes | `Theme` **normalizado** |
| Valores | `Light` · `Dark` **normalizado** |
| Medidas | `320 × 800` |

Ambos temas comparten estructura, medidas, spacing, jerarquía y comportamiento. La única diferencia es el color.

### 2.2 Anatomía

Siete hijos directos en orden fijo, idénticos en los dos temas:

```
SiderZellia                       320 × 800 · V · pad 16 · gap 12 · clip
│
├── Forma 1 (Background)          POLYGON · absoluto · §2.8
│
├── Header                        288 × 45 · H · gap 16 · CENTER
│   ├── Brand Block               248 × 45 · V · gap 0 · FILL/HUG
│   │   ├── Logo                   65 × 29 · IMAGE (CROP)
│   │   └── Tagline               TEXT · "Sales Managment System"
│   └── Collapse Button            24 × 24 · INSTANCE · §3.1
│
├── Input + Send Email            288 × 36 · H · gap 8 · FILL/HUG
│   ├── Search Input              244 × 36 · H · pad 4/12/4/12 · gap 8 · r12
│   │   ├── Search Icon            20 × 20 · INSTANCE
│   │   └── Placeholder           TEXT · "Buscar..."
│   └── Send Email Button          36 × 36 · V · pad 2/1/2/1 · r200
│       └── Vector                 18 × 15
│
├── Principal                     288 × 607 · V · gap 8 · FILL/FILL
│   ├── Divider                   288 × 0 · LINE · 1px
│   └── Title + Buttons           288 × 599 · V · gap 16 · FILL/FILL
│       └── Wrapper               288 × 480 · V · gap 12 · FILL/HUG
│           ├── Sidebar Items     288 × 64 · V · gap 4
│           ├── Workspace List    288 × 284 · V · gap 4
│           └── Analytics List    288 × 108 · V · gap 4
│
├── Divider                       288 × 0 · LINE · 1px
│
├── CTA User Switcher             288 × 32 · H · pad 8/12/8/12 · gap 8 · r8 · §3.2
│   ├── Fire                       24 × 24 · INSTANCE · oculto
│   ├── Text                      TEXT · "Estarter" · FILL/HUG
│   └── Arrow                      24 × 24 · Chevron / Up
│
└── Forma 2 (Background)          POLYGON · absoluto · §2.8
```

### 2.3 Contenedor raíz

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `320 × 800` | — |
| Auto Layout | Vertical | — |
| Padding | `16` en los cuatro lados | `space.16` |
| Gap | `12` | `space.12` |
| Alineación | `MIN` / `MIN` | — |
| Sizing | `FIXED / FIXED` | — |
| Clip content | Sí | — |
| Border radius | `0` | `radius.none` |

**Ancho de contenido:** `320 − 16 − 16 = 288`. Todos los bloques del flujo miden `288` y usan `FILL`.

`clipsContent` activo es estructural, no cosmético: es lo que recorta las formas decorativas de fondo, que exceden el contenedor por diseño.

#### Color del contenedor

| Tema | Fondo | Variable Figma | Token |
|---|---|---|---|
| Light | `#F5F9FE` | `Zellia/Primary/100` | `zellia.color.primary.100` |
| Dark | `#002041` | `Zellia/Primary/900` | `zellia.color.primary.900` |

#### Borde

| Tema | Borde |
|---|---|
| Light | **Solo derecho** · `1px` · `#CECECE` · `Zellia/Mono/300` · alineación `INSIDE` |
| Dark | Ninguno |

Es la única propiedad estructural que legítimamente difiere entre temas: sobre fondo claro el Sidebar necesita una línea que lo separe del área de contenido, mientras que en Dark el contraste del propio fondo ya cumple esa función.

### 2.4 Header

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `288 × 45` **normalizado** | — |
| Auto Layout | Horizontal | — |
| Padding | `0` | — |
| Gap | `16` **normalizado** | `space.16` |
| Alineación | `CENTER` / `CENTER` | — |
| Sizing | `FILL / HUG` **normalizado** | — |

**Brand Block**

| Propiedad | Valor |
|---|---|
| Medidas | `248 × 45` **normalizado** |
| Auto Layout | Vertical · gap `0` |
| Sizing | `FILL / HUG`, grow `1` **normalizado** |

`248 = 288 − 16 (gap) − 24 (botón de colapso)`. Al usar `FILL`, el bloque absorbe el ancho restante: si el ancho del Sidebar cambia, el header se recompone sin intervención.

**Logo**

| Propiedad | Valor |
|---|---|
| Medidas | `65 × 29` |
| Tipo | Rectangle con relleno `IMAGE`, `scaleMode: CROP` |
| Sizing | `FIXED / FIXED` |

**Tagline**

| Propiedad | Valor | Token |
|---|---|---|
| Contenido | `"Sales Managment System"` | — |
| Familia | `Inter` **normalizado** | `font.family.base` |
| Peso | `Regular 400` | `font.weight.regular` |
| Tamaño / line height | `12 / 16` **normalizado** | `font.size.caption1` |
| Letter spacing | `0%` | — |
| Color — Light | `#595958` · `Zellia/Mono/600` | `zellia.color.neutral.600` |
| Color — Dark | `#E9E9E9` · `Zellia/Mono/200` | `zellia.color.neutral.200` |

**Composición vertical del header:** `29 (logo) + 16 (tagline) = 45`. El botón de colapso mide `24` y se centra verticalmente en esos `45`.

### 2.5 Input + Send Email

Contenedor controlado por la propiedad booleana `Search + Send`.

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `288 × 36` | — |
| Auto Layout | Horizontal · gap `8` | `space.8` |
| Alineación | `MIN` / `CENTER` | — |
| Sizing | `FILL / HUG` | — |

**Search Input**

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `244 × 36` | — |
| Auto Layout | Horizontal · padding `4 / 12 / 4 / 12` · gap `8` | `space.4` · `space.12` · `space.8` |
| Sizing | `FILL / FIXED`, grow `1` | — |
| Border radius | `12` | `radius.lg` |
| Ícono de búsqueda | `20 × 20` · `FIXED / FIXED` | — |

| Tema | Fondo | Variable | Texto | Variable |
|---|---|---|---|---|
| Light | `#E6EBEF` | `Zellia/Primary/200` | `#222220` | `Zellia/Mono/900` |
| Dark | `#3D3D3B` | `Zellia/Mono/700` | `#F8F8F8` | `Zellia/Mono/100` |

**Placeholder**

| Propiedad | Valor | Token |
|---|---|---|
| Contenido | `"Buscar..."` | — |
| Familia | `Inter` **normalizado** | `font.family.base` |
| Peso | `Regular 400` | `font.weight.regular` |
| Tamaño / line height | `14 / 20` | `font.size.body2` |

**Send Email Button**

| Propiedad | Valor |
|---|---|
| Medidas | `36 × 36` |
| Auto Layout | Vertical · padding `2 / 1 / 2 / 1` · gap `10` |
| Alineación | `CENTER` / `CENTER` |
| Sizing | `FIXED / FILL` |
| Border radius | `200` — produce un círculo completo; equivale a `radius.full` |
| Clip content | Sí |
| Vector interno | `18 × 15` |

| Tema | Fondo | Variable | Ícono | Variable |
|---|---|---|---|---|
| Light | `#E6EBEF` | `Zellia/Primary/200` | `#222220` | `Zellia/Mono/900` |
| Dark | `#3D3D3B` | `Zellia/Mono/700` | `#F8F8F8` | `Zellia/Mono/100` |

El botón tiene un segundo relleno `#FFFFFF` desactivado bajo el color activo. No se renderiza.

### 2.6 Principal — área de navegación

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `288 × 607` **normalizado** | — |
| Auto Layout | Vertical · gap `8` | `space.8` |
| Sizing | `FILL / FILL`, grow `1` | — |

Es el único bloque que crece: absorbe el espacio vertical sobrante, lo que mantiene el CTA anclado al fondo independientemente de cuántos ítems tenga la navegación.

**Divider superior**

| Propiedad | Valor | Token |
|---|---|---|
| Tipo | `LINE` · `288 × 0` | — |
| Stroke | `1px` · alineación `CENTER` | — |
| Color | `#ABABAB` · `Zellia/Mono/400` | `zellia.color.neutral.400` |

**Title + Buttons**

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `288 × 599` **normalizado** | — |
| Auto Layout | Vertical · gap `16` | `space.16` |
| Sizing | `FILL / FILL`, grow `1` | — |

**Wrapper**

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `288 × 480` | — |
| Auto Layout | Vertical · gap `12` | `space.12` |
| Sizing | `FILL / HUG` | — |

Al ser `HUG` dentro de un padre `FILL`, el bloque de navegación se ancla arriba y el espacio libre queda debajo. Con la composición actual sobran `119px`.

#### Grupos de navegación

Los tres grupos comparten la misma estructura: un título seguido de una lista de ítems con gap `4` (`space.4`).

| Grupo | Título | Ítems | Alto |
|---|---|---|---|
| `Sidebar Items` | "Embudos" | 1 | `64` |
| `Workspace List` | "Espacio de trabajo" | 6 | `284` |
| `Analytics List` **normalizado** | "Analítica" | 2 | `108` |

**Aritmética de cada grupo**

| Grupo | Cálculo |
|---|---|
| Sidebar Items | `20 (título) + 4 + 40 = 64` |
| Workspace List | `20 + 4 + [6 × 40 + 5 × 4] = 20 + 4 + 260 = 284` |
| Analytics List | `20 + 4 + [2 × 40 + 1 × 4] = 20 + 4 + 84 = 108` |
| Wrapper | `64 + 12 + 284 + 12 + 108 = 480` |

**Títulos de sección**

| Propiedad | Valor | Token |
|---|---|---|
| Familia | `Inter` **normalizado** | `font.family.base` |
| Peso | `Medium 500` | `font.weight.medium` |
| Tamaño / line height | `14 / 20` | `font.size.body2` |
| Sizing | `FILL / HUG` | — |
| Color — Light | `#222220` · `Zellia/Mono/900` | `zellia.color.neutral.900` |
| Color — Dark | `#F8F8F8` · `Zellia/Mono/100` | `zellia.color.neutral.100` |

**Ítems de navegación**

9 instancias de `ButtonSidebar`, todas `288 × 40` con sizing `FILL / HUG`, y las mismas propiedades: `Left Icon: true`, `Right Icon: false`, `Mostrar Imagen: false`.

| Grupo | Ítem | `State` |
|---|---|---|
| Embudos | Todos los embudos | `Active` |
| Espacio de trabajo | Bandeja de entrada | `Default` |
| Espacio de trabajo | Agente IA | `Default` |
| Espacio de trabajo | Llamadas | `Default` |
| Espacio de trabajo | Contactos | `Default` |
| Espacio de trabajo | Empresas | `Default` |
| Espacio de trabajo | Ajustes | `Default` |
| Analítica | Informes | `Default` |
| Analítica | Campañas | `Default` |

Exactamente un ítem está en `Active` en todo el Sidebar. Es una invariante del componente: el estado activo refleja la ruta actual, y solo puede haber una.

### 2.7 Divider inferior y CTA

**Divider inferior**

| Propiedad | Valor | Token |
|---|---|---|
| Posición | Hijo directo del contenedor raíz **normalizado** | — |
| Tipo | `LINE` · `288 × 0` | — |
| Stroke | `1px` · `#ABABAB` · `Zellia/Mono/400` | `zellia.color.neutral.400` |

Al vivir en el raíz y no dentro de `Principal`, el divider permanece anclado sobre el CTA: si la navegación crece o hace scroll, la separación del pie no se desplaza.

**CTA User Switcher** **normalizado**

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `288 × 32` | — |
| Auto Layout | Horizontal · padding `8 / 12 / 8 / 12` · gap `8` | `space.8` · `space.12` |
| Alineación | `MIN` / `CENTER` | — |
| Sizing | `FILL / FIXED` | — |
| Border radius | `8` | `radius.md` |
| Relleno | ninguno — contenedor transparente | — |

| Capa | Tipo | Medidas | Visible |
|---|---|---|---|
| `Fire` | Instance | `24 × 24` | No |
| `Text` | Text · `"Estarter"` · `FILL / HUG` | — | Sí |
| `Arrow` | Instance · `Style=Chevron, Direction=Up` | `24 × 24` | Sí |

| Propiedad del texto | Valor | Token |
|---|---|---|
| Familia | `Inter` | `font.family.base` |
| Peso | `Regular 400` | `font.weight.regular` |
| Tamaño / line height | `16 / 18` | `font.size.buttonLarge` |
| Color — Light | `#222220` · `Zellia/Mono/900` | `zellia.color.neutral.900` |
| Color — Dark | `#F8F8F8` · `Zellia/Mono/100` | `zellia.color.neutral.100` |

> **Nota de construcción.** El padding vertical declarado es `8`, pero la altura está fijada en `32` y los íconos miden `24`. Como `8 + 24 + 8 = 40 > 32`, el padding vertical declarado no se respeta: el alineamiento `CENTER` reparte el espacio real, dejando `4px` efectivos arriba y abajo. Se documenta tal como está construido. Si en implementación se quisiera honrar el padding declarado, la altura tendría que ser `40`.

El chevron apunta hacia arriba porque el popover se despliega en esa dirección: el CTA está anclado al borde inferior del Sidebar.

### 2.8 Formas estéticas de fondo

Dos polígonos decorativos, posicionados de forma absoluta y por tanto fuera del flujo del Auto Layout. Geometría idéntica en ambos temas; cambian solo color y opacidad.

| Propiedad | Forma 1 (Background) | Forma 2 (Background) |
|---|---|---|
| Tipo | `POLYGON` — **6 lados** (hexágono) | `POLYGON` — **60 lados** (círculo) |
| Medidas | `377 × 381.1` | `377 × 381.1` |
| Posición | `x 23` · `y 308.4` | `x −187` · `y 944.4` |
| Rotación | `81°` | `81°` |
| Corner radius | `20` | `0` |
| Posicionamiento | Absoluto | Absoluto |
| Blend mode | `Pass through` | `Pass through` |

| Tema | Forma 1 | Opacidad | Forma 2 | Opacidad |
|---|---|---|---|---|
| Light | `#4A829E` · `Zellia/Secondary/600` | `7%` | `#436C96` · `Zellia/Primary/600` | `7%` |
| Dark | `#7FB1CA` · `Zellia/Secondary/400` | `5%` | `#6488AD` · `Zellia/Primary/500` | `5%` |

Tres criterios de construcción:

1. **Ambas exceden el contenedor y quedan recortadas** por el `clipsContent` del raíz. Solo se ve la porción que cae dentro de los `320 × 800`. El recorte es parte del diseño: produce formas abiertas que sugieren continuidad más allá del borde.
2. **La opacidad es mayor en Light** — `7%` frente a `5%`. Sobre fondo claro, una forma al `5%` resulta prácticamente imperceptible; sobre el azul profundo del tema Dark, el mismo `5%` ya es visible.
3. **Ambas usan colores de la paleta**, no grises neutros. Esto mantiene la temperatura cromática del fondo dentro de la identidad de Zellia.

### 2.9 Propiedades de componente

| Propiedad | Tipo | Valor por defecto | Controla |
|---|---|---|---|
| `Theme` **normalizado** | Variant | `Dark` | Tema — opciones `Light` · `Dark` |
| `Search + Send` | Boolean | `true` | Visibilidad del bloque `Input + Send Email` |

El CTA inferior no tiene propiedad de visibilidad: es un elemento fijo del Sidebar.

### 2.10 Interacciones de prototipo

| Elemento | Trigger | Acción | Destino | Transición |
|---|---|---|---|---|
| Todos los embudos | `On click` | Navigate | `…/Pipelines/-Empty` | ninguna |
| Bandeja de entrada | `On click` | Navigate | `…/Inbox` | ninguna |
| Agente IA | `On click` | Navigate | `…/AgentAI` | ninguna |
| Los 8 ítems en `Default` | `While hovering` | Change to `State=Hover` | — | `Dissolve` · `Ease Out` · `300ms` |

Los ítems Llamadas, Contactos, Empresas, Ajustes, Informes y Campañas tienen el hover resuelto pero aún no tienen destino de navegación asignado.

### 2.11 Composición vertical completa

El Sidebar mide exactamente `800px`. Esta es la aritmética que lo sostiene:

| Elemento | Alto | Acumulado |
|---|---|---|
| Padding superior | `16` | `16` |
| Header | `45` | `61` |
| Gap | `12` | `73` |
| Input + Send Email | `36` | `109` |
| Gap | `12` | `121` |
| **Principal** | **`607`** | `728` |
| Gap | `12` | `740` |
| Divider | `0` | `740` |
| Gap | `12` | `752` |
| CTA User Switcher | `32` | `784` |
| Padding inferior | `16` | **`800`** |

`Principal` es el único elemento con `FILL` en el eje vertical, por lo que absorbe cualquier diferencia si el alto del Sidebar cambia. Los demás bloques conservan su altura.

### 2.12 Implementación

```css
.zellia-sidebar {
  display: flex;
  flex-direction: column;
  gap: var(--zellia-space-12);
  width: 320px;
  height: 100vh;
  padding: var(--zellia-space-16);
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
}

.zellia-theme-light .zellia-sidebar {
  background: var(--zellia-color-primary-100);
  border-right: 1px solid var(--zellia-color-neutral-300);
}
.zellia-theme-dark .zellia-sidebar {
  background: var(--zellia-color-primary-900);
}

.zellia-sidebar__header {
  display: flex;
  align-items: center;
  gap: var(--zellia-space-16);
}
.zellia-sidebar__brand { flex: 1 1 auto; }

.zellia-sidebar__search-row {
  display: flex;
  align-items: center;
  gap: var(--zellia-space-8);
}
.zellia-sidebar__search {
  flex: 1 1 auto;
  height: 36px;
  padding: var(--zellia-space-4) var(--zellia-space-12);
  border-radius: var(--zellia-radius-lg);
  display: flex;
  align-items: center;
  gap: var(--zellia-space-8);
}
.zellia-sidebar__send {
  flex: 0 0 auto;
  width: 36px;
  height: 36px;
  border-radius: var(--zellia-radius-full);
  display: grid;
  place-items: center;
}

/* Absorbe el espacio sobrante y ancla el pie */
.zellia-sidebar__main {
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
  gap: var(--zellia-space-8);
  min-height: 0;
}
.zellia-sidebar__nav {
  display: flex;
  flex-direction: column;
  gap: var(--zellia-space-12);
  overflow-y: auto;
}
.zellia-sidebar__group {
  display: flex;
  flex-direction: column;
  gap: var(--zellia-space-4);
}

.zellia-sidebar__divider {
  height: 1px;
  background: var(--zellia-color-neutral-400);
  flex: 0 0 auto;
}

.zellia-sidebar__cta {
  flex: 0 0 auto;
  height: 32px;
  display: flex;
  align-items: center;
  gap: var(--zellia-space-8);
  padding-inline: var(--zellia-space-12);
  border-radius: var(--zellia-radius-md);
  background: transparent;
}

/* Formas decorativas */
.zellia-sidebar__shape {
  position: absolute;
  width: 377px;
  height: 381px;
  transform: rotate(81deg);
  pointer-events: none;
}
.zellia-sidebar__shape--1 { left: 23px; top: 308px; border-radius: 20px; }
.zellia-sidebar__shape--2 { left: -187px; top: 944px; }

.zellia-theme-light .zellia-sidebar__shape--1 { background: var(--zellia-color-secondary-600); opacity: .07; }
.zellia-theme-light .zellia-sidebar__shape--2 { background: var(--zellia-color-primary-600);   opacity: .07; }
.zellia-theme-dark  .zellia-sidebar__shape--1 { background: var(--zellia-color-secondary-400); opacity: .05; }
.zellia-theme-dark  .zellia-sidebar__shape--2 { background: var(--zellia-color-primary-500);   opacity: .05; }
```

Los polígonos se implementan con `clip-path` sobre estos contenedores. Las formas de 6 y 60 lados se generan con `polygon()`; el recorte al borde del Sidebar lo resuelve el `overflow: hidden` del contenedor.

---

## 3. Elementos pendientes de comportamiento

Dos elementos del Sidebar están construidos visualmente pero **no tienen interacción de prototipo asignada**. Se documentan aquí con su especificación completa y el comportamiento esperado, para revisión y posterior implementación.

### 3.1 Botón de colapso

**Ubicación:** `Header`, último hijo, esquina superior derecha.

| Spec | Valor |
|---|---|
| Capa | `Collapse Button` **normalizado** |
| Tipo | `INSTANCE` de `Sider-default` |
| Medidas | `24 × 24` · `FIXED / FIXED` |
| Alineación | Centrado vertical dentro de los `45px` del header |
| Separación del bloque de marca | `16` (`space.8` × 2) |
| Relleno propio | `#FFFFFF` desactivado — el ícono se dibuja por vector |
| Clip content | Sí |
| **Interacción actual** | **Ninguna** |

**Comportamiento esperado:** colapsa y expande el Sidebar hacia la izquierda.

Puntos a definir antes de implementar:

| Aspecto | Consideración |
|---|---|
| Ancho colapsado | El contenido mínimo es el ícono de `24` más el padding de `16` a cada lado: `56px`. Con `ButtonSidebar` en modo solo-ícono, el ítem conserva sus `40px` de alto y su `radius.md`. |
| Estado de los ítems | En colapsado, `Text` se oculta y `Left Icon` permanece. Ambas ya son propiedades del componente, así que no requiere una variante nueva. |
| Etiquetas | Al ocultarse el texto, cada ítem necesita un tooltip con su etiqueta para no perder la información. |
| Persistencia | El estado colapsado debe recordarse entre sesiones. |
| Dirección del ícono | El ícono debe invertirse según el estado, para indicar si la acción colapsa o expande. |
| Accesibilidad | El control necesita `aria-expanded` y una etiqueta accesible. El Sidebar es un `<nav>`. |
| Animación | Coherente con el resto del sistema: `Ease Out`. La duración debe definirse junto con el resto del motion del Design System. |

### 3.2 CTA User Switcher

**Ubicación:** último elemento del contenedor raíz, bajo el divider inferior.

| Spec | Valor |
|---|---|
| Capa | `CTA User Switcher` **normalizado** |
| Medidas | `288 × 32` · `FILL / FIXED` |
| Auto Layout | Horizontal · padding `8 / 12 / 8 / 12` · gap `8` |
| Border radius | `8` (`radius.md`) |
| Relleno | Ninguno — contenedor transparente |
| Contenido | `Fire` (oculto) · `Text "Estarter"` · `Arrow` Chevron Up |
| **Interacción actual** | **Ninguna** |

**Comportamiento esperado:** abre un popover para cambiar de usuario.

Puntos a definir antes de implementar:

| Aspecto | Consideración |
|---|---|
| Dirección del popover | Hacia arriba. El chevron ya apunta en esa dirección y el CTA está anclado al borde inferior. |
| Elevación | Un popover corresponde al nivel 4 del sistema: `zellia.shadow.lg`. |
| Anclaje y ancho | Alineado al borde izquierdo del CTA. Lo natural es que iguale los `288px` del contenedor. |
| Rotación del chevron | Debe girar a `Down` con el popover abierto, para indicar que una nueva pulsación lo cierra. |
| Capa `Fire` | Hoy oculta. Debe definirse si es el avatar del usuario, un indicador de plan o un residuo a eliminar. |
| Estados | El CTA no tiene hover ni pressed definidos. Al ser interactivo, los necesita. |
| Accesibilidad | Requiere `aria-haspopup="menu"` y `aria-expanded`. El foco debe entrar al popover al abrirse y regresar al CTA al cerrarse. |
| Cierre | Debe cerrarse con `Escape` y con clic fuera. |

---

## 4. Apéndice A — Normalizaciones aplicadas

Este documento especifica el estado **normalizado** de los componentes. Las siguientes diferencias respecto al archivo de Figma están pendientes de aplicarse en el diseño.

### 4.1 Simetría entre temas

El archivo tenía cuatro divergencias estructurales entre Dark y Light que no respondían a una decisión de diseño.

| # | Aspecto | Dark | White | Spec | Criterio |
|---|---|---|---|---|---|
| 1 | Gap del header | `28` | `16` | **`16`** | `28` no pertenece a la escala de spacing; `16` es `space.16` |
| 2 | Sizing del bloque de marca | `FIXED` 232px | `FILL` | **`FILL` grow 1** | Escalable: el bloque absorbe el ancho restante si cambia el ancho del Sidebar |
| 3 | Divider inferior | Hijo del raíz | Dentro de `Principal` | **Hijo del raíz** | Queda anclado sobre el CTA y no se desplaza si la navegación crece o hace scroll |
| 4 | Alto de `Principal` | `603` | `615` | **`607`** | Consecuencia de los puntos 3 y 6 |

### 4.2 Alineación a la retícula

| Aspecto | Antes | Spec | Criterio |
|---|---|---|---|
| Ancho del header | `284` | **`288` `FILL`** | Sus hermanos miden `288`. Con `284`, el botón de colapso quedaba `4px` desalineado respecto al botón de email, justo debajo |
| Ancho del bloque de marca | `232` / `244` | **`248`** | `288 − 16 − 24`, consecuencia del ancho anterior |

### 4.3 Tipografía

| Elemento | Antes | Spec | Criterio |
|---|---|---|---|
| Tagline | Poppins Regular `12 / 20` | **Inter Regular `12 / 16`** | Familia unificada a Inter; `12 / 16` corresponde a `font.size.caption1`, mientras `12 / 20` no existía en el sistema |
| Placeholder de búsqueda | Poppins Regular `14 / 20` | **Inter Regular `14 / 20`** | Familia unificada; ya correspondía a `body2.regular` |
| Títulos de sección (×3) | Poppins Medium `14 / 20` | **Inter Medium `14 / 20`** | Familia unificada; ya correspondía a `body2.medium` |

El cambio de line-height del tagline reduce el alto del bloque de marca de `49` a `45`, y en consecuencia `Principal` pasa de `603` a `607`.

### 4.4 Vinculación de variables

| Elemento | Antes | Spec |
|---|---|---|
| `ButtonSidebar(Dark)` · Hover · texto | `Palette - Finco/Mono/10` (`#F9FAFB`) | **`Zellia/Mono/100`** (`#F8F8F8`) |
| `ButtonSidebar(Dark)` · Hover · ícono | `Palette - Finco/Mono/10` (`#F9FAFB`) | **`Zellia/Mono/100`** (`#F8F8F8`) |

Eran las dos únicas referencias a la paleta anterior. Con el cambio, los seis variantes quedan simétricos: todo texto e ícono claro apunta a `Zellia/Mono/100` y todo texto e ícono oscuro a `Zellia/Mono/800`.

### 4.5 Propiedades

| Componente | Cambio |
|---|---|
| `ButtonSidebar` (Light y Dark) | Añadir `Left Icon Swap` — Instance Swap declarada a nivel de Component Set |
| `SiderZellia` | Renombrar el eje `Propiedad 1` a `Theme` y el valor `White` a `Light` |

### 4.6 Lo que no se modificó

Colores, medidas, paddings, gaps, radios, formas de fondo, opacidades, estructura de grupos y contenido permanecen exactamente como están construidos en el archivo. También se conserva sin cambio:

- El fondo de `Hover` en Dark como color crudo `#86A3C2` al `65%`, sin variable vinculada.
- El peso `Medium 500` del texto en Light/Active, frente a `Regular 400` en el resto.
- La altura de `32` del CTA con padding vertical declarado de `8`.

---

## 5. Apéndice B — Inventario de tokens usados

### 5.1 Color

| Variable de Figma | Token del sistema | Hex | Uso en el Sidebar |
|---|---|---|---|
| `Zellia/Primary/100` | `zellia.color.primary.100` | `#F5F9FE` | Fondo del contenedor — Light |
| `Zellia/Primary/200` | `zellia.color.primary.200` | `#E6EBEF` | Buscador y botón de email — Light |
| `Zellia/Primary/500` | `zellia.color.primary.500` | `#6488AD` | Forma 2 de fondo — Dark |
| `Zellia/Primary/600` | `zellia.color.primary.600` | `#436C96` | Forma 2 de fondo — Light |
| `Zellia/Primary/800` | `zellia.color.primary.800` | `#023562` | Ítem `Active` — Dark |
| `Zellia/Primary/900` | `zellia.color.primary.900` | `#002041` | Fondo del contenedor — Dark |
| `Zellia/Secondary/200` | `zellia.color.secondary.200` | `#C8DEEA` | Ítem `Hover` — Light |
| `Zellia/Secondary/400` | `zellia.color.secondary.400` | `#7FB1CA` | Forma 1 de fondo — Dark |
| `Zellia/Secondary/500` | `zellia.color.secondary.500` | `#5799BA` | Ítem `Active` — Light |
| `Zellia/Secondary/600` | `zellia.color.secondary.600` | `#4A829E` | Forma 1 de fondo — Light |
| `Zellia/Mono/100` | `zellia.color.neutral.100` | `#F8F8F8` | Texto e íconos claros |
| `Zellia/Mono/200` | `zellia.color.neutral.200` | `#E9E9E9` | Tagline — Dark |
| `Zellia/Mono/300` | `zellia.color.neutral.300` | `#CECECE` | Borde derecho — Light |
| `Zellia/Mono/400` | `zellia.color.neutral.400` | `#ABABAB` | Dividers |
| `Zellia/Mono/600` | `zellia.color.neutral.600` | `#595958` | Tagline — Light |
| `Zellia/Mono/700` | `zellia.color.neutral.700` | `#3D3D3B` | Buscador y botón de email — Dark |
| `Zellia/Mono/800` | `zellia.color.neutral.800` | `#2D2D2B` | Texto e íconos — Light |
| `Zellia/Mono/900` | `zellia.color.neutral.900` | `#222220` | Títulos y texto de alto contraste — Light |

**18 de las 50 variables de color del sistema** intervienen en el Sidebar. Un solo color queda fuera del sistema: el `#86A3C2` al `65%` del hover en Dark.

### 5.2 Spacing

| Token | Valor | Uso |
|---|---|---|
| `zellia.space.4` | `4` | Gap entre ítems de navegación · padding vertical del buscador |
| `zellia.space.8` | `8` | Gap interno de los ítems · gap del buscador · gap de `Principal` |
| `zellia.space.12` | `12` | Gap del contenedor raíz · gap del `Wrapper` · padding del buscador y del CTA |
| `zellia.space.16` | `16` | Padding del contenedor · gap del header · gap de `Title + Buttons` |

Los cuatro valores pertenecen a la escala. El Sidebar no introduce ninguna medida de spacing fuera del sistema.

### 5.3 Border radius

| Token | Valor | Uso |
|---|---|---|
| `zellia.radius.md` | `8` | Ítems de navegación · CTA |
| `zellia.radius.lg` | `12` | Campo de búsqueda |
| `zellia.radius.full` | `9999` | Botón de email (construido como `200` en Figma) |
| — | `20` | Forma 1 de fondo — elemento decorativo, fuera de la escala |

### 5.4 Tipografía

| Token | Valor | Uso |
|---|---|---|
| `zellia.typography.caption1.regular` | Inter 400 · `12 / 16` | Tagline |
| `zellia.typography.body2.regular` | Inter 400 · `14 / 20` | Placeholder de búsqueda |
| `zellia.typography.body2.medium` | Inter 500 · `14 / 20` | Títulos de sección |
| `zellia.typography.buttonLarge.regular` | Inter 400 · `16 / 18` | Ítems de navegación · CTA |
| `zellia.typography.buttonLarge.medium` | Inter 500 · `16 / 18` | Ítem `Active` — Light |

**Cinco estilos tipográficos**, todos del sistema y todos en Inter.

---

## 6. Apéndice C — Nomenclatura de capas

Los nombres autogenerados por Figma y los heredados del nombre anterior del proyecto se normalizan así:

| Nombre en el archivo | Nombre especificado | Motivo |
|---|---|---|
| `Frame 16207` | `Header` | Autogenerado |
| `Frame 16205` | `Brand Block` | Autogenerado |
| `25976-Photoroom 1` | `Logo` | Nombre de archivo de origen |
| `CTA Outline Mony` | `CTA User Switcher` | "Mony" es el nombre anterior del proyecto; el nuevo describe la función |
| `Anality List` | `Analytics List` | Error ortográfico |
| `Analityc items` | `Analytics Items` | Error ortográfico |
| `List` (título de Analítica) | `Title` | Coherencia con los otros dos grupos |
| `Sider-default` | `Collapse Button` | Describe la función, no el archivo de origen |
| `ButtonSidebar` · capa 4 | `Right Icon` | Estaba nombrada `Left Icon` siendo el ícono derecho, lo que hace ambigua la selección de capa |

La capa 4 del `ButtonSidebar` es la única cuyo renombrado no es cosmético: al existir dos capas con el mismo nombre dentro del componente, la referencia de la propiedad booleana resulta ambigua para quien implemente.

---

*Zellia Design System · Component Specs · Sidebar v1.0*
*Origen: Figma `Finco` — página `Zellia Mockups`*
