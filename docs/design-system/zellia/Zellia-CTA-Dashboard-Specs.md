# Zellia · CTA Buttons — Especificación de Componentes

**Sistema de diseño:** Zellia
**Página de diseño:** `Zellia Mockups`
**Alcance:** Botones de acción (CTA) para secciones y entradas del Dashboard
**Colección de tokens:** `Foundation` de Zellia (modos `Lightmode` / `Darkmode`)
**Fecha del documento:** 19 de septiembre de 2026

---

## 1. Alcance

Este documento especifica los tres Component Sets de botones CTA que gobiernan las acciones principales dentro de las secciones y entradas del Dashboard de Zellia:

| Component Set | Node ID | Variantes | Rol |
|---|---|---|---|
| `CTA Principal/Zellia` | `2290:7669` | 4 | Acción primaria de la sección |
| `CTA Secundary/Zellia` | `2290:7686` | 3 | Acción de soporte, alto contraste neutro |
| `CTA Outline/Zellia` | `2290:7707` | 3 | Acción terciaria, contenedor transparente |

Los botones de navegación del Sidebar quedan fuera de este alcance y están documentados por separado.

---

## 2. Arquitectura común

Los tres sets comparten una base estructural que garantiza alineación vertical y ritmo consistente cuando conviven en una misma fila de acciones.

| Atributo | Valor |
|---|---|
| Altura del control | **48 px** |
| Radio de esquina | **8 px** (uniforme en las 4 esquinas) |
| Padding horizontal | **16 px** izquierda / derecha |
| Dirección del Auto Layout | **Horizontal** |
| Familia tipográfica | **Inter** |
| Tamaño de texto | **16 px** |
| Interlineado | **18 px** (fijo) |
| Espaciado entre letras | **0 %** |
| Alineación del texto | Centro horizontal / centro vertical |
| Transformación de texto | `ORIGINAL` (sin mayúsculas forzadas) |
| Tamaño del contenedor de icono | **24 × 24 px** |
| Área visible del glifo | **14 × 18 px** dentro del contenedor de 24 px |
| Separación icono–texto | **8 px** |
| Alineación del Auto Layout | `CENTER` en eje primario y contraeje |

### 2.1 Anatomía

```
┌──────────────────────────────────────────────────────┐
│ ←16→ [Icono 24] ←8→  Etiqueta  ←8→ [Icono 24] ←16→   │  48 px
└──────────────────────────────────────────────────────┘
        ↑ opcional                    ↑ opcional
```

Los tres sets exponen los iconos izquierdo y derecho como propiedades booleanas independientes, de modo que cualquier CTA puede presentarse sin iconos, con uno solo o con ambos sin salir del componente.

### 2.2 Comportamiento de redimensionado

| Component Set | Horizontal | Vertical |
|---|---|---|
| `CTA Principal/Zellia` | Hug contents | Fixed (48 px) |
| `CTA Secundary/Zellia` | Hug contents | Fixed (48 px) |
| `CTA Outline/Zellia` | Hug contents | Hug contents |

El ancho de cada botón se deriva del contenido. Los anchos registrados corresponden a las etiquetas de ejemplo de cada componente y no son medidas fijas.

---

## 3. Foundation Zellia — Tokens de color

Todos los colores de los tres sets se resuelven contra la colección `Foundation` de Zellia, que define dos modos. Al estar vinculados por variable, los componentes responden automáticamente al cambio de modo sin requerir sets duplicados por tema.

| Token | Lightmode | Darkmode | Uso en los CTA |
|---|---|---|---|
| `Zellia/Primary/700` | `#23527F` | `#8CBED9` | Fondo — Principal · Default |
| `Zellia/Primary/600` | `#436C96` | `#5FA2C5` | Fondo — Principal · Hover |
| `Zellia/Primary/300` | `#AABFD6` | `#0D4E78` | Fondo — Principal · Disabled |
| `Zellia/Secondary/900` | `#233D4A` | `#E5F3F8` | Fondo — Principal · Clicked |
| `Zellia/Mono/900` | `#222220` | `#F5F5F4` | Fondo Secundary · Default / Texto Outline · Hover |
| `Zellia/Mono/800` | `#2D2D2B` | `#DEDEDC` | Borde — Outline · Hover |
| `Zellia/Mono/700` | `#3D3D3B` | `#BEBEBC` | Fondo Secundary · Hover / Texto Outline · Default |
| `Zellia/Mono/500` | `#7F7F7E` | `#777774` | Borde — Outline · Default |
| `Zellia/Mono/300` | `#CECECE` | `#3E3E3C` | Fondo Secundary · Disabled / Borde y texto Outline · Disabled |
| `Zellia/Mono/100` | `#F8F8F8` | `#222220` | Texto e icono — Principal y Secundary |

### 3.1 Token de grosor de borde

| Token | Valor | Uso |
|---|---|---|
| `stroke weight/1` | `1 px` | Grosor de borde vinculado por variable |

---

## 4. Elevación

Principal y Secundary comparten una sombra de elevación idéntica en todas sus variantes. Outline no aplica sombra.

| Propiedad | Valor |
|---|---|
| Tipo | Drop shadow |
| Desplazamiento | X `0` · Y `1` |
| Desenfoque | `2` |
| Expansión | `0` |
| Color | `#101828` al `5 %` de opacidad |

---

## 5. `CTA Principal/Zellia`

Acción primaria de la sección. Es el único set con cobertura de cuatro estados y el que utiliza la rampa `Primary` de la marca.

**Node ID:** `2290:7669`

### 5.1 Propiedades

| Propiedad | Tipo | Valor por defecto | Opciones |
|---|---|---|---|
| `State` | Variant | `Default` | `Default` · `Hover` · `Clicked` · `Disabled` |
| `Left Icon#4019:0` | Boolean | `true` | Muestra u oculta el icono izquierdo |
| `Right Icon#4019:5` | Boolean | `true` | Muestra u oculta el icono derecho |

### 5.2 Estructura y espaciado

| Atributo | Valor |
|---|---|
| Dirección del Auto Layout | Horizontal |
| Padding superior / inferior | `10 px` |
| Padding izquierdo / derecho | `16 px` |
| Separación entre elementos | `8 px` |
| Alineación eje primario | `CENTER` |
| Alineación contraeje | `CENTER` |
| Redimensionado | Hug horizontal · Fixed vertical |
| Altura | `48 px` |
| Radio de esquina | `8 px` |

### 5.3 Tipografía

| Atributo | Valor |
|---|---|
| Familia | Inter |
| Peso | Medium |
| Tamaño | `16 px` |
| Interlineado | `18 px` |
| Espaciado entre letras | `0 %` |
| Redimensionado del texto | Auto width and height |

### 5.4 Composición por estado

| Estado | Fondo | Token de fondo | Texto e icono | Token de texto |
|---|---|---|---|---|
| **Default** | `#23527F` | `Zellia/Primary/700` | `#F8F8F8` | `Zellia/Mono/100` |
| **Hover** | `#436C96` | `Zellia/Primary/600` | `#F8F8F8` | `Zellia/Mono/100` |
| **Clicked** | `#233D4A` | `Zellia/Secondary/900` | `#F8F8F8` | `Zellia/Mono/100` |
| **Disabled** | `#AABFD6` | `Zellia/Primary/300` | `#E9E9E9` | — |

La progresión Default → Hover aclara el fondo un paso dentro de la rampa `Primary`. El estado Clicked cruza a la rampa `Secondary` para producir el oscurecimiento de presión.

---

## 6. `CTA Secundary/Zellia`

Acción de soporte. Construido sobre la rampa neutra `Mono`, mantiene alto contraste sin competir con el CTA primario.

**Node ID:** `2290:7686`

### 6.1 Propiedades

| Propiedad | Tipo | Valor por defecto | Opciones |
|---|---|---|---|
| `State` | Variant | `Default` | `Default` · `Hover` · `Disabled` |
| `Left Icon#2495:0` | Boolean | `true` | Muestra u oculta el icono izquierdo |
| `Right Icon#2495:4` | Boolean | `true` | Muestra u oculta el icono derecho |

### 6.2 Estructura y espaciado

| Atributo | Valor |
|---|---|
| Dirección del Auto Layout | Horizontal |
| Padding superior / inferior | `12 px` |
| Padding izquierdo / derecho | `16 px` |
| Separación entre elementos | `8 px` |
| Alineación eje primario | `CENTER` |
| Alineación contraeje | `CENTER` |
| Redimensionado | Hug horizontal · Fixed vertical |
| Altura | `48 px` |
| Radio de esquina | `8 px` |

Con iconos de `24 px` y padding vertical de `12 px`, el control resuelve `12 + 24 + 12` y alcanza exactamente los `48 px` de altura compartidos por los tres sets.

### 6.3 Tipografía

| Atributo | Valor |
|---|---|
| Estilo de texto vinculado | `Button Font/Large/Semibold` |
| Familia | Inter |
| Peso | Semi Bold |
| Tamaño | `16 px` |
| Interlineado | `18 px` |
| Espaciado entre letras | `0 %` |
| Redimensionado del texto | Auto width and height |

### 6.4 Composición por estado

| Estado | Fondo | Token de fondo | Texto e icono | Token de texto |
|---|---|---|---|---|
| **Default** | `#222220` | `Zellia/Mono/900` | `#F8F8F8` | `Zellia/Mono/100` |
| **Hover** | `#3D3D3B` | `Zellia/Mono/700` | `#F8F8F8` | `Zellia/Mono/100` |
| **Disabled** | `#CECECE` | `Zellia/Mono/300` | `#F8F8F8` | `Zellia/Mono/100` |

El color del contenido permanece constante en los tres estados; la diferenciación se resuelve íntegramente mediante el fondo.

---

## 7. `CTA Outline/Zellia`

Acción terciaria. Contenedor transparente delimitado por borde de `1 px`; el color se aplica al borde y al contenido, nunca al relleno.

**Node ID:** `2290:7707`

### 7.1 Propiedades

| Propiedad | Tipo | Valor por defecto | Opciones |
|---|---|---|---|
| `State` | Variant | `Default` | `Default` · `Hover` · `Disabled` |
| `Left Icon#4019:13` | Boolean | `true` | Muestra u oculta el icono izquierdo |
| `Right Icon#4019:10` | Boolean | `true` | Muestra u oculta el icono derecho |

### 7.2 Estructura y espaciado

| Atributo | Valor |
|---|---|
| Dirección del Auto Layout | Horizontal |
| Padding superior / inferior | `12 px` |
| Padding izquierdo / derecho | `16 px` |
| Separación entre elementos | `8 px` |
| Alineación eje primario | `CENTER` |
| Alineación contraeje | `CENTER` |
| Redimensionado | Hug horizontal · Hug vertical |
| Altura | `48 px` |
| Radio de esquina | `8 px` |
| Relleno | Ninguno (transparente) |
| Grosor de borde | `1 px` |

### 7.3 Tipografía

| Atributo | Valor |
|---|---|
| Estilo de texto vinculado | `Button Font/Large/Semibold` |
| Familia | Inter |
| Peso | Semi Bold |
| Tamaño | `16 px` |
| Interlineado | `18 px` |
| Espaciado entre letras | `0 %` |
| Redimensionado del texto | Auto width and height |

### 7.4 Composición por estado

| Estado | Borde | Token de borde | Alineación del borde | Texto e icono | Token de contenido |
|---|---|---|---|---|---|
| **Default** | `#7F7F7E` | `Zellia/Mono/500` | `INSIDE` | `#3D3D3B` | `Zellia/Mono/700` |
| **Hover** | `#2D2D2B` | `Zellia/Mono/800` | `OUTSIDE` | `#222220` | `Zellia/Mono/900` |
| **Disabled** | `#CECECE` | `Zellia/Mono/300` | `OUTSIDE` | `#CECECE` | `Zellia/Mono/300` |

En Hover, borde y contenido avanzan un paso hacia el extremo oscuro de la rampa `Mono`. En Disabled ambos convergen en un mismo token, `Zellia/Mono/300`, que aplana el control.

---

## 8. Matriz consolidada de estados

| Estado | `CTA Principal/Zellia` | `CTA Secundary/Zellia` | `CTA Outline/Zellia` |
|---|---|---|---|
| **Default** | `Zellia/Primary/700` | `Zellia/Mono/900` | Borde `Zellia/Mono/500` |
| **Hover** | `Zellia/Primary/600` | `Zellia/Mono/700` | Borde `Zellia/Mono/800` |
| **Clicked** | `Zellia/Secondary/900` | — | — |
| **Disabled** | `Zellia/Primary/300` | `Zellia/Mono/300` | Borde `Zellia/Mono/300` |

---

## 9. Matriz de propiedades booleanas

Los tres sets exponen el mismo par de propiedades para el control de iconos, con idéntico valor por defecto.

| Component Set | Icono izquierdo | Icono derecho | Por defecto |
|---|---|---|---|
| `CTA Principal/Zellia` | `Left Icon#4019:0` | `Right Icon#4019:5` | `true` |
| `CTA Secundary/Zellia` | `Left Icon#2495:0` | `Right Icon#2495:4` | `true` |
| `CTA Outline/Zellia` | `Left Icon#4019:13` | `Right Icon#4019:10` | `true` |

---

## 10. Resumen de spacing

| Medida | Principal | Secundary | Outline |
|---|---|---|---|
| Padding superior | `10 px` | `12 px` | `12 px` |
| Padding inferior | `10 px` | `12 px` | `12 px` |
| Padding izquierdo | `16 px` | `16 px` | `16 px` |
| Padding derecho | `16 px` | `16 px` | `16 px` |
| Separación interna | `8 px` | `8 px` | `8 px` |
| Contenedor de icono | `24 px` | `24 px` | `24 px` |
| Altura total | `48 px` | `48 px` | `48 px` |
| Radio de esquina | `8 px` | `8 px` | `8 px` |

Secundary y Outline comparten padding vertical de `12 px`, que con el contenedor de icono de `24 px` resuelve los `48 px` exactos. Principal emplea `10 px` y fija su altura, absorbiendo la diferencia en el contenedor.

---

*Documento generado a partir del estado de la página `Zellia Mockups`.*
