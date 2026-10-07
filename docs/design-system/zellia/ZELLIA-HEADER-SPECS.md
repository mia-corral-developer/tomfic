# Zellia — Component Specs: Header

**Header-Dashboard / Desktop · v1.0**
2026-09-16

Especificación de construcción del Header de escritorio de Zellia, extraída del archivo de Figma `Finco`, página `Zellia Mockups`.

---

## Índice

0. [Alcance y convenciones](#0-alcance-y-convenciones)
1. [Identificación y modos](#1-identificación-y-modos)
2. [Look & feel](#2-look--feel)
3. [Contenedor](#3-contenedor)
4. [Forma de fondo](#4-forma-de-fondo)
5. [Anatomía](#5-anatomía)
6. [Elementos](#6-elementos)
7. [Composición modular](#7-composición-modular)
8. [Colores por modo](#8-colores-por-modo)
9. [Tipografía](#9-tipografía)
10. [Interacción](#10-interacción)
11. [Implementación](#11-implementación)
12. [Apéndice A — Normalizaciones aplicadas](#12-apéndice-a--normalizaciones-aplicadas)
13. [Apéndice B — Inventario de tokens](#13-apéndice-b--inventario-de-tokens)
14. [Apéndice C — Nomenclatura de capas](#14-apéndice-c--nomenclatura-de-capas)

---

## 0. Alcance y convenciones

### 0.1 Qué cubre este documento

El Component Set `Header-Dashboard/Desktop` en sus dos modos, con el detalle completo de su construcción: medidas, Auto Layout, colores con sus variables, tipografía, forma decorativa de fondo y propiedades de composición.

### 0.2 Qué no cubre

El `CTA Principal` que aparece dentro del Header es un componente independiente con su propio Component Set y sus propios estados. Aquí se documenta **cómo se integra** en el Header — posición, medidas, color de fondo y sombra — pero no su especificación interna. Ese componente y el resto de los botones del sistema se documentan por separado.

### 0.3 Convenciones de lectura

- **Padding** se expresa en orden `arriba / derecha / abajo / izquierda`.
- **Sizing** se expresa como `horizontal / vertical`, con los valores `FIXED`, `HUG` y `FILL`.
- Todas las medidas están en **px**.
- Cada color se indica con su hex y con la variable de Figma que lo provee.
- Los valores marcados **normalizado** difieren del archivo actual. El Apéndice A los lista con su justificación.

---

## 1. Identificación y modos

| Propiedad | Valor |
|---|---|
| Nombre | `Header-Dashboard/Desktop` |
| Tipo | Component Set |
| Eje de variantes | `Theme` **normalizado** |
| Valores | `Light` · `Dark` |
| Modo por defecto | `Dark` |
| Medidas de cada variante | `1150 × 66` |

Los dos modos comparten **estructura, medidas, spacing, jerarquía y comportamiento**. Difieren únicamente en color, y en un solo caso también en la variable de origen de ese color.

---

## 2. Look & feel

El Header es una barra horizontal de altura fija que corona el área de contenido. Su carácter visual se apoya en cuatro decisiones:

**Una sola línea de separación.** No hay sombra bajo el header ni elevación. La separación respecto al contenido la resuelve un borde inferior de `1px` — y, a diferencia del Sidebar, está presente en **ambos modos**. En Light separa dos superficies claras; en Dark marca el límite entre el azul profundo del header y el área de trabajo.

**Una forma decorativa recortada.** Un círculo grande, teñido con el secundario de marca a muy baja opacidad, anclado a la esquina superior derecha y desbordando el contenedor por arriba y por la derecha. El `clipsContent` del header lo recorta, dejando ver solo un arco. Aporta profundidad sin competir con el contenido y sin introducir un elemento con forma reconocible.

**La misma forma en ambos modos.** El círculo conserva color y opacidad idénticos en Light y Dark — `Zellia/Secondary/400` al `8%`. Es una diferencia deliberada respecto al Sidebar, donde la opacidad sí varía entre temas.

**Altura constante.** El header mide `66px` en las ocho combinaciones posibles de sus tres propiedades de visibilidad. Activar o desactivar tabs, breadcrumb o CTA nunca desplaza el contenido de la página. Ver §7.3.

**Composición en tres zonas.** Izquierda para contexto y navegación (breadcrumb, tabs), centro-derecha para la acción principal, extremo derecho para los elementos persistentes de la cuenta (notificaciones y perfil). Solo la tercera zona está siempre presente.

---

## 3. Contenedor

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `1150 × 66` | — |
| Auto Layout | Vertical | — |
| Padding | `12 / 24 / 12 / 24` | `space.12` · `space.24` |
| Gap | `10` | — |
| Alineación principal | `CENTER` | — |
| Alineación transversal | `MAX` | — |
| Sizing | `FIXED / HUG` | — |
| Clip content | **Sí** | — |
| Strokes incluidos en el layout | **Sí** | — |
| Border radius | `0` | `radius.none` |

### 3.1 Color de fondo

| Modo | Hex | Variable de Figma | Token |
|---|---|---|---|
| Light | `#F5F9FE` | `Zellia/Primary/100` | `zellia.color.primary.100` |
| Dark | `#002041` | `Zellia/Primary/900` | `zellia.color.primary.900` |

Son los mismos dos valores que usa el contenedor del Sidebar, lo que mantiene continuidad entre ambas superficies de chrome.

### 3.2 Borde

| Propiedad | Valor |
|---|---|
| Lados | **Solo inferior** — pesos `0 / 0 / 1 / 0` |
| Grosor | `1` |
| Alineación | `INSIDE` |
| Color | `#CECECE` · `Zellia/Mono/300` |
| Modos | **Presente en Light y en Dark** |

### 3.3 Aritmética vertical

El alto de `66px` se compone así:

| Elemento | Alto | Acumulado |
|---|---|---|
| Padding superior | `12` | `12` |
| Fila `Wrapper` | `41` | `53` |
| Padding inferior | `12` | `65` |
| Borde inferior | `1` | **`66`** |

El borde suma al alto porque el contenedor tiene activada la opción de incluir los trazos en el layout. Es un detalle relevante para implementación: en CSS equivale a `box-sizing: content-box` sobre el borde, o a declarar `height: 65px` más `border-bottom: 1px`.

### 3.4 Ancho de contenido

`1150 − 24 − 24 = 1102`. La fila `Wrapper` ocupa ese ancho completo con sizing `FILL`.

---

## 4. Forma de fondo

| Propiedad | Valor |
|---|---|
| Capa | `BackgroundForm` |
| Tipo | `RECTANGLE` con `cornerRadius: 200` → **círculo perfecto** |
| Medidas | `153 × 153` |
| Posición | `x 1023` · `y −19` |
| Posicionamiento | **Absoluto** — fuera del flujo del Auto Layout |
| Opacidad | **`8%`** |
| Blend mode | `Pass through` |
| Color | `#7FB1CA` · `Zellia/Secondary/400` |
| **Diferencia entre modos** | **Ninguna** — mismo color y misma opacidad |

### 4.1 Geometría del recorte

La forma excede el contenedor en tres direcciones y el `clipsContent` la recorta:

| Dirección | Cálculo | Desbordamiento |
|---|---|---|
| Superior | `y = −19` | `19px` por encima |
| Derecha | `1023 + 153 = 1176` vs ancho `1150` | `26px` a la derecha |
| Inferior | `−19 + 153 = 134` vs alto `66` | `68px` por debajo |

La porción visible es la franja `x 1023 → 1150`, `y 0 → 66`: aproximadamente el cuadrante inferior izquierdo del círculo, que se lee como un arco suave en la esquina superior derecha.

### 4.2 Criterios de construcción

1. **Radius `200` sobre una caja de `153`** garantiza el círculo completo: cualquier valor mayor o igual a la mitad del lado produce el mismo resultado. Equivale conceptualmente a `radius.full`.
2. **Opacidad al `8%`** es el punto en que la forma aporta profundidad sin llegar a leerse como un elemento. Por encima competiría con el contenido del header; por debajo desaparecería en Light.
3. **El desbordamiento es intencional.** Al recortarse contra los bordes, la forma sugiere continuidad más allá del componente en lugar de leerse como un objeto colocado dentro de él.
4. **Posicionamiento absoluto** la mantiene fija en la esquina superior derecha sin participar del Auto Layout, de modo que no se desplaza cuando cambian los elementos visibles del header.

---

## 5. Anatomía

```
Header  1150 × 66 · V · pad 12/24 · gap 10 · clip · borde inferior 1px
│
├── BackgroundForm          153 × 153 · absoluto · círculo · 8%
│
└── Wrapper                1102 × 41 · H · gap 16 · MIN/CENTER · FILL/HUG
    │
    ├── Breadcrumb          353 × 40 · OCULTO · H · pad 8/12 · gap 8 · r8
    │   ├── Fire             24 × 24 · INSTANCE · oculto
    │   ├── Text            "Ventas"
    │   └── Arrow            24 × 24 · Chevron / Down
    │
    ├── Tabs + CTA         1018 × 41 · H · gap 48 · FILL/HUG · grow 1
    │   ├── Frame 16208    1018 × 41 · V · gap 10 · FILL/HUG · grow 1
    │   │   └── Tabs         257 × 41 · OCULTO · H · pad 4/6 · gap 8 · r12
    │   │       ├── Tab "Mías"          58 × 33 · pill activa
    │   │       ├── Tab "Sin asignar"  103 × 33
    │   │       └── Tab "Todas"         68 × 33
    │   └── CTA Principal   171 × 40 · OCULTO · INSTANCE · r8
    │
    └── Items                68 × 28 · H · gap 16 · HUG/HUG
        ├── bxs:bell         24 × 24
        └── Wrapper Status User  28 × 28 · GROUP
            ├── Profile_Photo    28 × 28 · círculo
            └── Status            9.3 × 9.3 · círculo
```

### 5.1 La fila `Wrapper`

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `1102 × 41` | — |
| Posición | `x 24` · `y 12` | — |
| Auto Layout | Horizontal | — |
| Gap | `16` | `space.16` |
| Alineación | `MIN` / `CENTER` | — |
| Sizing | `FILL / HUG` | — |

Su alto de `41px` lo determina el bloque de Tabs, el elemento más alto de la fila. Ver §7.3.

### 5.2 Reparto horizontal

Con los tres elementos opcionales ocultos — la configuración por defecto:

| Elemento | Ancho | Cálculo |
|---|---|---|
| `Tabs + CTA` | `1018` | `1102 − 16 − 68` |
| Gap | `16` | `space.16` |
| `Items` | `68` | `24 + 16 + 28` |

Con el breadcrumb activo, `Tabs + CTA` se reduce a `649`: `1102 − 353 − 16 − 16 − 68`. El bloque central es el único con `grow`, de modo que absorbe toda variación de ancho.

---

## 6. Elementos

### 6.1 Breadcrumb / Dropdown

Selector de contexto. Muestra la sección o entidad activa y despliega las alternativas. **Oculto por defecto.**

| Propiedad | Valor | Token |
|---|---|---|
| Capa | `Breadcrumb` **normalizado** | — |
| Medidas | `353 × 40` | — |
| Auto Layout | Horizontal · padding `8 / 12 / 8 / 12` · gap `8` | `space.8` · `space.12` |
| Alineación | `MIN` / `CENTER` | — |
| Sizing | `FIXED / HUG` | — |
| Border radius | `8` | `radius.md` |
| Relleno | Ninguno — contenedor transparente | — |

**Capas**

| # | Capa | Tipo | Medidas | Visible |
|---|---|---|---|---|
| 1 | `Fire` | Instance | `24 × 24` | No |
| 2 | `Text` | Text · `"Ventas"` | `HUG / HUG` | Sí |
| 3 | `Arrow` | Instance · `Style=Chevron, Direction=Down` | `24 × 24` | Sí |

**Texto**

| Propiedad | Valor | Token |
|---|---|---|
| Familia | `Inter` | `font.family.base` |
| Peso | `Medium 500` | `font.weight.medium` |
| Tamaño / line height | `16 / 18` | `font.size.buttonLarge` |
| Letter spacing | `0%` | — |
| Color — Light | `#222220` · `Zellia/Mono/900` | `zellia.color.neutral.900` |
| Color — Dark | `#FFFFFF` · `Zellia/System/White` | `zellia.color.common.white` |

**Flecha**

Instancia del set `Arrow` con `Style=Chevron` y `Direction=Down`. Contiene `nav-arrow-down` con un vector de `6 × 12` rotado `90°`, trazo de `1.5` con alineación `CENTER`.

| Modo | Color del trazo | Variable |
|---|---|---|
| Light | `#222220` **normalizado** | `Zellia/Mono/900` |
| Dark | `#FFFFFF` | `Zellia/System/White` |

El chevron apunta hacia abajo: el desplegable se abre hacia el contenido.

### 6.2 Tabs

Conmutador de vistas dentro de una misma sección. **Oculto por defecto.**

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `257 × 41` | — |
| Auto Layout | Horizontal · padding `4 / 6 / 4 / 6` · gap `8` | `space.4` · `space.8` |
| Alineación | `MIN` / `CENTER` | — |
| Sizing | `HUG / HUG` | — |
| Border radius | `12` | `radius.lg` |

**Fondo del contenedor**

| Modo | Hex | Variable | Token |
|---|---|---|---|
| Light | `#E9E9E9` | `Zellia/Mono/200` | `zellia.color.neutral.200` |
| Dark | `#3D6B82` | `Zellia/Secondary/700` | `zellia.color.secondary.700` |

El contenedor actúa como carril: la pestaña activa se dibuja como una pastilla clara sobre él.

**Las tres pestañas**

| Pestaña | Medidas | Sizing | Alineación | Estado |
|---|---|---|---|---|
| `"Mías"` | `58 × 33` | `HUG / HUG` | `CENTER` / `CENTER` | **Activa** |
| `"Sin asignar"` | `103 × 33` | `FIXED / HUG` | `SPACE_BETWEEN` / `CENTER` | Inactiva |
| `"Todas"` | `68 × 33` | `HUG / HUG` | `CENTER` / `CENTER` | Inactiva |

Todas comparten: Auto Layout horizontal, padding `6 / 12 / 6 / 12`, gap `4` y border radius `12`.

**Pestaña activa**

| Propiedad | Valor | Token |
|---|---|---|
| Relleno | `#FFFFFF` · `Zellia/System/White` | `zellia.color.common.white` |
| Sombra | `0 2px 4px 0 rgba(0, 0, 0, 0.15)` | — |
| Modos | **Idéntica en Light y Dark** | — |

La pastilla activa es blanca con sombra en ambos modos. En Dark contrasta contra el carril `Secondary/700`, lo que la vuelve inequívoca sin necesidad de un tratamiento distinto por tema.

Las pestañas inactivas no tienen relleno ni sombra: son solo texto sobre el carril.

**Texto de las pestañas**

| Propiedad | Valor | Token |
|---|---|---|
| Familia | `Inter` **normalizado** | `font.family.base` |
| Peso | `Medium 500` | `font.weight.medium` |
| Tamaño / line height | `14 / 20` **normalizado** | `font.size.body2` |
| Letter spacing | `0%` | — |

| Pestaña | Color Light | Color Dark |
|---|---|---|
| Activa `"Mías"` | `#222220` · `Zellia/Mono/900` | `#222220` · `Zellia/Mono/900` |
| Inactivas | `#222220` · `Zellia/Mono/900` | `#F8F8F8` · `Zellia/Mono/100` |

La pestaña activa conserva el texto oscuro en ambos modos, porque su fondo es blanco en ambos.

### 6.3 CTA Principal

Acción primaria de la sección. **Oculto por defecto.** Componente independiente; aquí se especifica su integración.

| Propiedad | Valor | Token |
|---|---|---|
| Componente | `CTA Principal/Zellia/Light` — instancia | — |
| Variante | `State=Default` | — |
| Medidas | `171 × 40` | — |
| Auto Layout | Horizontal · padding `10 / 12 / 10 / 12` · gap `8` | `space.8` · `space.12` |
| Alineación | `CENTER` / `CENTER` | — |
| Sizing | `HUG / FIXED` | — |
| Border radius | `8` | `radius.md` |
| Clip content | Sí | — |
| Relleno | `#23527F` · `Zellia/Primary/700` | `zellia.color.primary.700` |
| Sombra | `0 1px 2px 0 rgba(16, 24, 40, 0.05)` | — |
| **Modos** | **Idéntico en Light y Dark** | — |

**Propiedades de la instancia**

| Propiedad | Valor |
|---|---|
| `Left Icon` | `true` |
| `Right Icon` | `false` |
| `State` | `Default` |

**Contenido**

| Capa | Tipo | Medidas | Visible | Color |
|---|---|---|---|---|
| `plus` | Instance | `24 × 24` | Sí | `#F8F8F8` · `Zellia/Mono/100` |
| `Label` **normalizado** | Text · `"Nuevo negocio"` | `HUG / HUG` | Sí | `#F8F8F8` · `Zellia/Mono/100` |
| `Fire` | Instance | `24 × 24` | No | `Zellia/Mono/100` **normalizado** |

| Propiedad del texto | Valor | Token |
|---|---|---|
| Familia | `Inter` | `font.family.base` |
| Peso | `Medium 500` | `font.weight.medium` |
| Tamaño / line height | `16 / 18` | `font.size.buttonLarge` |

El CTA usa el mismo relleno en ambos modos. `Primary/700` mantiene contraste suficiente tanto sobre el fondo claro como sobre el azul profundo, lo que evita duplicar el componente por tema.

### 6.4 Items — notificaciones y perfil

Único bloque **siempre visible**. No tiene propiedad de visibilidad asociada.

| Propiedad | Valor | Token |
|---|---|---|
| Medidas | `68 × 28` | — |
| Posición | `x 1034` · `y 6.5` | — |
| Auto Layout | Horizontal · gap `16` | `space.16` |
| Alineación | `MIN` / `CENTER` | — |
| Sizing | `HUG / HUG` | — |

Ancho: `24 (campana) + 16 (gap) + 28 (avatar) = 68`.

#### Campana de notificaciones

| Propiedad | Valor |
|---|---|
| Capa | `bxs:bell` — Frame `24 × 24` con clip |
| Relleno del frame | `#FFFFFF` desactivado |
| Vector | `18 × 20`, posicionado en `x 3` · `y 2` |

| Modo | Color del vector | Variable |
|---|---|---|
| Light | `#0D47A1` | `Zellia/System/Info - Dark` |
| Dark | `#FFFFFF` | `Zellia/System/White` |

Es el único elemento cuyo color proviene de familias distintas según el modo. En Light, el azul informativo sobre el fondo `Primary/100` ofrece contraste alto y aporta un acento de color; en Dark se resuelve en blanco, ya que ese mismo azul sobre `Primary/900` resultaría ilegible.

#### Avatar de usuario

Agrupación `Wrapper Status User` de `28 × 28`, compuesta por dos elementos superpuestos.

**Foto de perfil**

| Propiedad | Valor | Token |
|---|---|---|
| Capa | `Profile_Photo` | — |
| Medidas | `28 × 28` | — |
| Auto Layout | Vertical · padding `10` · gap `10` | — |
| Alineación | `CENTER` / `CENTER` | — |
| Border radius | `200` → círculo | `radius.full` |
| Relleno | `#0F489D` — **sin variable vinculada** | — |

**Inicial**

| Propiedad | Valor | Token |
|---|---|---|
| Contenido | `"W"` | — |
| Familia | `Inter` **normalizado** | `font.family.base` |
| Peso | `Medium 500` | `font.weight.medium` |
| Tamaño / line height | `14 / 20` | `font.size.body2` |
| Sizing | `FILL / HUG` | — |
| Color — Light | `#F8F8F8` · `Zellia/Mono/100` | `zellia.color.neutral.100` |
| Color — Dark | `#FFFFFF` · `Zellia/System/White` | `zellia.color.common.white` |

**Indicador de estado**

| Propiedad | Valor | Token |
|---|---|---|
| Capa | `Status` — Rectangle | — |
| Medidas | `9.3 × 9.3` | — |
| Posición | `x 58.7` · `y 18.7` — esquina inferior derecha del avatar | — |
| Border radius | `200` → círculo | `radius.full` |
| Relleno | `#00C097` — **sin variable vinculada** | — |
| Modos | Idéntico en Light y Dark | — |

---

## 7. Composición modular

### 7.1 Las tres propiedades

| Propiedad | Tipo | Default | Controla |
|---|---|---|---|
| `Theme` **normalizado** | Variant | `Dark` | Modo — `Light` · `Dark` |
| `Mostrar Tabs` | Boolean | **`false`** | Bloque de pestañas |
| `Mostrar Breadcrumb/Dropdown` **normalizado** | Boolean | **`false`** | Selector de contexto |
| `Mostrar CTA Header` | Boolean | **`false`** | Botón de acción principal |

### 7.2 Por qué el header es modular

Los tres elementos opcionales están apagados por defecto, de modo que la configuración base del Header muestra únicamente la campana y el avatar.

La razón es que **no todas las secciones del producto necesitan los mismos controles**. Algunas incorporan un selector de contexto que permite cambiar de entidad — el breadcrumb con su desplegable. Otras dividen su contenido en vistas que conviven en la misma pantalla y requieren pestañas para alternar entre ellas, como el "Mías / Sin asignar / Todas" de este diseño. Y algunas ofrecen una acción primaria que debe estar siempre accesible desde el encabezado.

Exponer cada bloque como un Boolean independiente permite componer el header que cada pantalla necesita **desde una sola instancia**, sin crear una variante por combinación. Con tres propiedades booleanas se cubren ocho configuraciones distintas; resolverlo con variantes habría exigido ocho variantes por modo, dieciséis en total.

Las combinaciones más frecuentes:

| Configuración | Tabs | Breadcrumb | CTA | Caso de uso |
|---|---|---|---|---|
| Base | — | — | — | Pantallas simples, sin subdivisión ni acción de encabezado |
| Con pestañas | ✓ | — | — | Secciones con vistas alternativas sobre el mismo conjunto de datos |
| Con contexto | — | ✓ | — | Secciones que operan sobre una entidad seleccionable |
| Completa | ✓ | ✓ | ✓ | Secciones con contexto, vistas y acción primaria |

### 7.3 Altura constante

El Header mide `66px` en las ocho combinaciones. La razón está en la altura de los elementos de la fila:

| Elemento | Alto |
|---|---|
| Bloque de Tabs | `41` |
| Breadcrumb | `40` |
| CTA Principal | `40` |
| Items | `28` |

El bloque de Tabs es el más alto y su altura queda reservada en la fila `Wrapper` con independencia de su visibilidad. Como ningún otro elemento lo supera, activar o desactivar cualquier combinación **no altera el alto del Header**.

Es una propiedad valiosa: el contenido de la página nunca se desplaza verticalmente al cambiar de sección, aunque el header cambie de configuración.

---

## 8. Colores por modo

### 8.1 Tabla completa

| Elemento | Light | Variable | Dark | Variable |
|---|---|---|---|---|
| Fondo del contenedor | `#F5F9FE` | `Zellia/Primary/100` | `#002041` | `Zellia/Primary/900` |
| Borde inferior | `#CECECE` | `Zellia/Mono/300` | `#CECECE` | `Zellia/Mono/300` |
| Forma de fondo | `#7FB1CA` 8% | `Zellia/Secondary/400` | `#7FB1CA` 8% | `Zellia/Secondary/400` |
| Texto del breadcrumb | `#222220` | `Zellia/Mono/900` | `#FFFFFF` | `Zellia/System/White` |
| Flecha del breadcrumb | `#222220` | `Zellia/Mono/900` | `#FFFFFF` | `Zellia/System/White` |
| Carril de Tabs | `#E9E9E9` | `Zellia/Mono/200` | `#3D6B82` | `Zellia/Secondary/700` |
| Pestaña activa | `#FFFFFF` | `Zellia/System/White` | `#FFFFFF` | `Zellia/System/White` |
| Texto de pestaña activa | `#222220` | `Zellia/Mono/900` | `#222220` | `Zellia/Mono/900` |
| Texto de pestañas inactivas | `#222220` | `Zellia/Mono/900` | `#F8F8F8` | `Zellia/Mono/100` |
| Fondo del CTA | `#23527F` | `Zellia/Primary/700` | `#23527F` | `Zellia/Primary/700` |
| Texto e ícono del CTA | `#F8F8F8` | `Zellia/Mono/100` | `#F8F8F8` | `Zellia/Mono/100` |
| Campana | `#0D47A1` | `Zellia/System/Info - Dark` | `#FFFFFF` | `Zellia/System/White` |
| Fondo del avatar | `#0F489D` | *sin variable* | `#0F489D` | *sin variable* |
| Inicial del avatar | `#F8F8F8` | `Zellia/Mono/100` | `#FFFFFF` | `Zellia/System/White` |
| Indicador de estado | `#00C097` | *sin variable* | `#00C097` | *sin variable* |

### 8.2 Qué cambia y qué no entre modos

**No cambia:** la forma de fondo, el borde inferior, el fondo y el contenido del CTA, la pestaña activa con su sombra, el fondo del avatar y el indicador de estado. Ocho de los quince elementos son idénticos en ambos modos.

**Cambia:** el fondo del contenedor, el carril de Tabs, el texto de las pestañas inactivas, el breadcrumb con su flecha, la campana y la inicial del avatar.

### 8.3 Colores sin variable vinculada

Tres valores del Header no provienen del sistema de variables:

| Elemento | Color | Observación |
|---|---|---|
| Fondo del avatar | `#0F489D` | No corresponde a ningún tono de la escala Primary |
| Indicador de estado | `#00C097` | Distinto del `Success - Base` del sistema (`#22C55E`) |

Se documentan tal como están construidos.

### 8.4 Sombras

| Elemento | Valor |
|---|---|
| Pestaña activa | `0 2px 4px 0 rgba(0, 0, 0, 0.15)` |
| CTA Principal | `0 1px 2px 0 rgba(16, 24, 40, 0.05)` |

Ninguna de las dos corresponde a la escala de elevación de Zellia, que usa sombras de dos capas tintadas con `rgb(0, 32, 65)`. Se documentan tal como están.

El contenedor del Header **no tiene sombra**: su separación del contenido la resuelve exclusivamente el borde inferior.

---

## 9. Tipografía

| Elemento | Familia | Peso | Tamaño / LH | Token |
|---|---|---|---|---|
| Texto del breadcrumb | `Inter` | `Medium 500` | `16 / 18` | `typography.buttonLarge.medium` |
| Texto de las pestañas | `Inter` **normalizado** | `Medium 500` | `14 / 20` **normalizado** | `typography.body2.medium` |
| Texto del CTA | `Inter` | `Medium 500` | `16 / 18` | `typography.buttonLarge.medium` |
| Inicial del avatar | `Inter` **normalizado** | `Medium 500` | `14 / 20` | `typography.body2.medium` |

**Cuatro estilos, todos en `Medium 500`.** El Header no usa Regular ni Bold: todo su texto cumple una función de control o de etiqueta, no de lectura, y el peso intermedio le da presencia sin competir con el contenido de la página.

Los cuatro corresponden a tokens existentes del sistema, y se reducen a dos combinaciones de tamaño: `16 / 18` para los controles con ícono y `14 / 20` para las etiquetas compactas.

---

## 10. Interacción

| Elemento | Trigger | Acción | Destino | Transición |
|---|---|---|---|---|
| `CTA Principal` | `While hovering` | `Change to` | `State=Hover` | `Dissolve` · `Ease In` · `230ms` |

Es la **única interacción definida** en el Header. La campana, el avatar, las pestañas y el breadcrumb no tienen comportamiento asignado, pese a que los cuatro son elementos interactivos por naturaleza.

La transición pertenece al `CTA Principal`, que es un componente independiente. Se documenta aquí por estar presente en el Header, pero su especificación de estados corresponde al documento de ese componente.

---

## 11. Implementación

```css
.zellia-header {
  --zellia-header-height: 65px;

  box-sizing: content-box;
  height: var(--zellia-header-height);
  padding: var(--zellia-space-12) var(--zellia-space-24);
  border-bottom: 1px solid var(--zellia-color-neutral-300);
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.zellia-theme-light .zellia-header { background: var(--zellia-color-primary-100); }
.zellia-theme-dark  .zellia-header { background: var(--zellia-color-primary-900); }

/* Forma decorativa de fondo */
.zellia-header__shape {
  position: absolute;
  width: 153px;
  height: 153px;
  right: -26px;
  top: -19px;
  border-radius: var(--zellia-radius-full);
  background: var(--zellia-color-secondary-400);
  opacity: 0.08;
  pointer-events: none;
}

/* Fila principal — altura reservada por el bloque de Tabs */
.zellia-header__row {
  display: flex;
  align-items: center;
  gap: var(--zellia-space-16);
  min-height: 41px;
}

.zellia-header__center { flex: 1 1 auto; }

.zellia-header__items {
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  gap: var(--zellia-space-16);
}

/* Breadcrumb */
.zellia-header__breadcrumb {
  display: inline-flex;
  align-items: center;
  gap: var(--zellia-space-8);
  height: 40px;
  padding-inline: var(--zellia-space-12);
  border-radius: var(--zellia-radius-md);
  font: var(--zellia-font-weight-medium) var(--zellia-font-size-button-large) /
        var(--zellia-font-line-height-button-large) var(--zellia-font-family-base);
}
.zellia-theme-light .zellia-header__breadcrumb { color: var(--zellia-color-neutral-900); }
.zellia-theme-dark  .zellia-header__breadcrumb { color: var(--zellia-color-common-white); }

/* Tabs */
.zellia-header__tabs {
  display: inline-flex;
  align-items: center;
  gap: var(--zellia-space-8);
  padding: var(--zellia-space-4) 6px;
  border-radius: var(--zellia-radius-lg);
}
.zellia-theme-light .zellia-header__tabs { background: var(--zellia-color-neutral-200); }
.zellia-theme-dark  .zellia-header__tabs { background: var(--zellia-color-secondary-700); }

.zellia-header__tab {
  display: inline-flex;
  align-items: center;
  gap: var(--zellia-space-4);
  padding: 6px var(--zellia-space-12);
  border-radius: var(--zellia-radius-lg);
  background: transparent;
  font: var(--zellia-font-weight-medium) var(--zellia-font-size-body-2) /
        var(--zellia-font-line-height-body-2) var(--zellia-font-family-base);
}
.zellia-theme-light .zellia-header__tab { color: var(--zellia-color-neutral-900); }
.zellia-theme-dark  .zellia-header__tab { color: var(--zellia-color-neutral-100); }

/* La pestaña activa es blanca en ambos modos */
.zellia-header__tab[aria-selected='true'] {
  background: var(--zellia-color-common-white);
  color: var(--zellia-color-neutral-900);
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.15);
}

/* Avatar */
.zellia-header__avatar {
  position: relative;
  width: 28px;
  height: 28px;
  border-radius: var(--zellia-radius-full);
  background: #0F489D;
  display: grid;
  place-items: center;
  font: var(--zellia-font-weight-medium) var(--zellia-font-size-body-2) /
        var(--zellia-font-line-height-body-2) var(--zellia-font-family-base);
  color: var(--zellia-color-neutral-100);
}
.zellia-header__avatar::after {
  content: '';
  position: absolute;
  right: -1px;
  bottom: -1px;
  width: 9.3px;
  height: 9.3px;
  border-radius: var(--zellia-radius-full);
  background: #00C097;
}
```

**Nota sobre la altura.** El contenedor declara `65px` más `1px` de borde con `box-sizing: content-box`, replicando el comportamiento de Figma, donde el trazo se incluye en el layout. El total renderizado es de `66px`.

**Nota sobre la fila.** `min-height: 41px` reproduce la altura reservada por el bloque de Tabs, garantizando que el Header conserve sus `66px` aunque los tres elementos opcionales estén ocultos.

---

## 12. Apéndice A — Normalizaciones aplicadas

Este documento especifica el estado **normalizado** del componente. Las siguientes diferencias respecto al archivo de Figma están pendientes de aplicarse en el diseño.

### 12.1 Tipografía

| Elemento | En el archivo | Spec | Criterio |
|---|---|---|---|
| Texto de las pestañas | Poppins Medium `14` · line-height `AUTO` | **Inter Medium `14 / 20`** | Familia unificada a Inter; `14 / 20` corresponde a `body2.medium`. `AUTO` renderiza a `21px`, el único line-height del sistema sin valor fijo |
| Inicial del avatar | Poppins Medium `14 / 20` | **Inter Medium `14 / 20`** | Familia unificada; el tamaño ya correspondía a `body2` |

### 12.2 Vinculación de variables

| Elemento | En el archivo | Spec | Criterio |
|---|---|---|---|
| Flecha del breadcrumb — Light | `#4D4D4D` sin variable | **`Zellia/Mono/900`** | Simetría con el modo Dark, ya vinculado a `System/White` |
| `Fire` del CTA (capa oculta) | `Palette - Finco/Mono/10` | **`Zellia/Mono/100`** | Última referencia a la paleta anterior; sus capas hermanas ya fueron migradas |

### 12.3 Nomenclatura

| En el archivo | Spec | Criterio |
|---|---|---|
| Eje `Propiedad 1` | **`Theme`** | Descriptivo, no autogenerado |
| `Mostrar Breadcumb/Dropdown` | **`Mostrar Breadcrumb/Dropdown`** | Error ortográfico |
| `CTA Outline Zellia` | **`Breadcrumb`** | Describe la función; no es un CTA sino un selector de contexto |
| `Empezar a administrar tus finanzas` | **`Label`** | El nombre de la capa no corresponde a su contenido, que es "Nuevo negocio" |

### 12.4 Lo que no se modificó

Estructura, medidas, Auto Layout, paddings, gaps, radios, la forma de fondo con su opacidad, las sombras y la interacción permanecen exactamente como están construidos. También se conservan sin cambio:

| Elemento | Estado conservado |
|---|---|
| Fondo del avatar | `#0F489D` sin variable vinculada |
| Indicador de estado | `#00C097` sin variable vinculada |
| Texto del breadcrumb e inicial del avatar | Resuelven a `System/White` en Dark, mientras las pestañas inactivas usan `Mono/100` |
| Sombras de la pestaña activa y del CTA | Fuera de la escala de elevación de Zellia |
| Transición del CTA | `230ms` con `Ease In` |
| Instancia del CTA en modo Dark | Referencia el set `CTA Principal/Zellia/Light` |

---

## 13. Apéndice B — Inventario de tokens

### 13.1 Color

| Variable de Figma | Token del sistema | Hex | Uso en el Header |
|---|---|---|---|
| `Zellia/Primary/100` | `zellia.color.primary.100` | `#F5F9FE` | Fondo del contenedor — Light |
| `Zellia/Primary/700` | `zellia.color.primary.700` | `#23527F` | Fondo del CTA — ambos modos |
| `Zellia/Primary/900` | `zellia.color.primary.900` | `#002041` | Fondo del contenedor — Dark |
| `Zellia/Secondary/400` | `zellia.color.secondary.400` | `#7FB1CA` | Forma de fondo — ambos modos |
| `Zellia/Secondary/700` | `zellia.color.secondary.700` | `#3D6B82` | Carril de Tabs — Dark |
| `Zellia/Mono/100` | `zellia.color.neutral.100` | `#F8F8F8` | Texto del CTA · pestañas inactivas Dark · inicial del avatar Light |
| `Zellia/Mono/200` | `zellia.color.neutral.200` | `#E9E9E9` | Carril de Tabs — Light |
| `Zellia/Mono/300` | `zellia.color.neutral.300` | `#CECECE` | Borde inferior — ambos modos |
| `Zellia/Mono/900` | `zellia.color.neutral.900` | `#222220` | Breadcrumb Light · texto de pestañas |
| `Zellia/System/White` | `zellia.color.common.white` | `#FFFFFF` | Pestaña activa · breadcrumb Dark · campana Dark |
| `Zellia/System/Info - Dark` | `zellia.color.info.dark` | `#0D47A1` | Campana — Light |

**11 variables del sistema** intervienen en el Header. Dos colores quedan fuera: `#0F489D` y `#00C097`.

### 13.2 Spacing

| Token | Valor | Uso |
|---|---|---|
| `zellia.space.4` | `4` | Padding vertical del carril de Tabs · gap interno de cada pestaña |
| `zellia.space.8` | `8` | Gap del breadcrumb · gap entre pestañas · gap interno del CTA |
| `zellia.space.12` | `12` | Padding vertical del contenedor · padding horizontal del breadcrumb, pestañas y CTA |
| `zellia.space.16` | `16` | Gap de la fila principal · gap entre campana y avatar |
| `zellia.space.24` | `24` | Padding horizontal del contenedor |

Cinco valores, todos pertenecientes a la escala.

**Tres medidas quedan fuera del sistema:** el gap de `10` del contenedor, el gap de `48` de `Tabs + CTA` y el padding horizontal de `6` del carril de Tabs. Ninguna pertenece a la escala de spacing. Se documentan tal como están construidas.

### 13.3 Border radius

| Token | Valor | Uso |
|---|---|---|
| `zellia.radius.md` | `8` | Breadcrumb · CTA Principal |
| `zellia.radius.lg` | `12` | Carril de Tabs · cada pestaña |
| `zellia.radius.full` | `9999` | Forma de fondo · avatar · indicador de estado (construidos como `200` en Figma) |

### 13.4 Tipografía

| Token | Valor | Uso |
|---|---|---|
| `zellia.typography.buttonLarge.medium` | Inter 500 · `16 / 18` | Breadcrumb · CTA |
| `zellia.typography.body2.medium` | Inter 500 · `14 / 20` | Pestañas · inicial del avatar |

Dos estilos tipográficos para todo el componente.

---

## 14. Apéndice C — Nomenclatura de capas

| Nombre en el archivo | Nombre especificado | Motivo |
|---|---|---|
| `CTA Outline Zellia` | `Breadcrumb` | No es un CTA sino un selector de contexto con desplegable |
| `Frame 16208` | `Tabs Container` | Autogenerado |
| `Wrapper` (×3, dentro de Tabs) | `Tab` | Tres capas hermanas con el mismo nombre genérico |
| `Status Text` | `Tab Label` | El texto no representa un estado |
| `Empezar a administrar tus finanzas` | `Label` | El nombre no corresponde al contenido |
| `bxs:bell` | `Notifications` | Identificador del set de íconos de origen |
| `Wrapper Status User` | `User Avatar` | Describe la función |
| `Profile_Photo` | `Avatar` | Coherencia de nomenclatura |

Los tres `Wrapper` dentro del bloque de Tabs son el caso que más conviene resolver: al compartir nombre, la selección de capa resulta ambigua para quien implemente o para quien quiera exponerlas como propiedades del componente.

---

*Zellia Design System · Component Specs · Header-Dashboard/Desktop v1.0*
*Origen: Figma `Finco` — página `Zellia Mockups`*
