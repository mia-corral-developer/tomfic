# Zellia Design System — documentación del proyecto

**Decisión vigente:** toda la UI del producto (páginas, componentes, layouts) se construye
con el sistema de diseño Zellia. React y Ant se consideraron y se descartaron; la base del
proyecto sigue siendo Vue 3 + Inertia, con los tokens y specs de Zellia como única referencia.

## Origen

- Figma: archivo `Finco`, página `Zellia Mockups`
- Colección de tokens: `Foundation` (modos `Lightmode` / `Darkmode`)

## Documentos (v1.1 / v1.0, 2026-09 y 2026-09-19)

| Archivo | Qué define |
|---|---|
| `ZELLIA-DESIGN-SYSTEM.md` | Foundations: 228 tokens (color 50, tipografía 41+66, spacing 14, radius 6, shadows 13, buttons 25, grid 10). Arquitectura de 3 capas (primitivos → semánticos → componente). CSS completo y JSON W3C en apéndices. |
| `ZELLIA-COMPONENT-SPECS-sidebar.md` | `ButtonSidebar` (3 estados × 2 temas) + `SiderZellia` (320×800, Light/Dark), normalizaciones aplicadas. |
| `ZELLIA-HEADER-SPECS.md` | `Header-Dashboard/Desktop` (1150×66), composición modular (3 booleans), colores por modo, forma de fondo. |
| `Zellia-CTA-Dashboard-Specs.md` | `CTA Principal` (4 estados), `CTA Secundary` (3), `CTA Outline` (3). Base común: 48px alto, radius 8, Inter 16/18. |
| `logo/` | Assets de logo en `Light/` y `Dark/`: `icon`, `splash`, `adaptive-icon`, store icons (android/apple). |

## Reglas no negociables (resumen operativo)

1. **Tres capas:** un componente nunca consume un token primitivo directo; si falta el semántico, se crea.
2. **Color:** escalas 100–300 = superficies; 600–900 = texto; 400–500 = bordes/iconos. Brand = `primary.800` / `secondary.500`. Máximo 1 accent por pantalla. Accent y Error nunca adyacentes.
3. **Tipografía:** solo Inter (pesos 300/400/500/600/700). Solo la matriz de 66 estilos compuestos es válida. No se saltan niveles. 1 Display por pantalla. Button font solo en interactivos.
4. **Spacing:** toda medida múltiplo de 4 (excepción: 2px para micro-gaps). 8 y 16 por defecto. Solo `gap`/`margin-bottom`, nunca margins duales.
5. **Radius:** sm=4 (inputs), **md=8 (default, botones)**, lg=12 (tabs/modals), full (avatares/pills). Regla de anidado: `radius_interno = radius_externo − padding`.
6. **Elevación:** sombras tintadas `rgba(0,32,65,…)`, 2 capas, niveles 0–6, un salto por interacción, transición 150ms.
7. **Focus:** ring 4px (`box-shadow`, no outline) + cambio de border color. Nunca eliminar el foco.
8. **Grid:** mobile-first, 4/8/12 columnas, márgenes 16/32/48, gutter 16/24/24, max 1440px desktop.
9. **Botones CTA:** altura 48px, radius 8, Inter 16/18 — Principal (Primary/700→600→Secondary/900→Primary/300), Secundary (Mono/900→700→300), Outline (borde Mono/500→800→300).

## Convención de nombres

- Figma Variables: `zellia.color.primary.800`
- CSS: `--zellia-color-primary-800`
- JS/TS: `zelliaColorPrimary800`

## Estado de implementación en este repo

- **Pendiente.** Los specs existen como documento; aun no hay tokens en el código.
  Primer paso propuesto: portar el CSS del Apéndice A a `resources/css/zellia.css`
  (o al formato de `@theme` de Tailwind si se prefiere) y crear los componentes base
  (`zellia-sidebar-item`, header, CTA) según los specs.
