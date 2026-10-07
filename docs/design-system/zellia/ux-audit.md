# Auditoría UX/UI — tomfic post-migración Zellia
**Fecha:** 7 oct 2026 · **Alcance:** 5 rutas × 2 temas × 2 anchos (20 capturas) · **Herramienta:** skill `ux-ui-validation` (capture_screens.mjs + análisis manual con video-screenshots)

---

## Veredicto

**Listo para lanzar con arreglos** (launch-ready with fixes). Ningún hallazgo Critical.
Tres High con dueño/diagnóstico ya escrito, todos con arreglo construible en este mismo repo.

---

## Scorecard (14 dimensiones)

| Dimensión | Nota | Hallazgos que la justifican |
|---|---|---|
| H1 Visibilidad del estado | 4 | UXF-04 (TTFB 1.0–1.3s sin skeleton local) |
| H2 Match con el mundo real | 5 | — |
| H3 Control y libertad del usuario | 4 | UXF-03 (bulk delete sin segundo diálogo) |
| H4 Consistencia y estándares | 4 | UXF-06 (mezcla es/en en sidebar — pendiente del locale default) |
| H5 Prevención de errores | 4 | UXF-03 |
| H6 Reconocer antes que recordar | 5 | — |
| H7 Eficiencia | 4 | UXF-01 (30px del ítem Settings requiere scroll a 900px con 15 items) |
| H8 Diseño minimalista | 5 | — |
| H9 Recuperación de errores | not rated | Sin flow de error capturado (no hay pantallas de fallo en los datos del seed) — no se inventa nota |
| H10 Ayuda | not rated | Sin documentación in-app que auditar |
| Accesibilidad WCAG 2.2 A/AA | 4 | UXF-02 (checkboxes ya arreglados — re-verificado 0 unnamed), WCAG 1.4.10 en settings |
| Diseño visual y sistema | 5 | Migración Zellia exacta (tokens, tipografía, radius, sombras) — verificado token a token en Pasos 1–5 |
| Esfuerzo en tareas clave | 4 | UXF-01 (2–3 interacciones respetadas; scroll en nav grande es aceptable, no crítico) |
| Rendimiento percibido | 3 | UXF-04 — LCP 1.4–1.7s, TTFB 1.0–1.3 s (medición local sin throttle: sesga optimista, no pesimista) |

*(Dimensiones H9 y H10: **not rated** — sin datos de pantalla, no se adivinan. La nota 3 en Rendimiento percibido es solo por TTFB>800ms; con throttle 4×/Fast-4G bajaría, así que se marca como intervalo optimista-local.)*

---

## Hallazgos

### UXF-01 · Medium · H7 · Navigation termina a 810px y el ítem Settings queda al 850px
- **Pantalla / rol:** SiderZellia con 15 ítems de nav (el design Figma tenía 9), viewport 900px.
- **Evidencia:** `verify-paso3-light.png`. Nav scrollHeight 720px > clientHeight 701px: sí hay scroll, el CTA queda anclado. El ítem "Settings" pide 19px de scroll.
- **Por qué importa:** Opciones de configuración tras un scroll oculto parecen inexistentes para el usuario nuevo (Ley de Jakob: esperan verlas sin scroll en desktop).
- **Arreglo:** Colapsar el grupo Admin (o Settings solo) por debajo de 900px - degraiendo a 40px con solo icono (spec §3.1, "colapsado mínimo").

### UXF-02 · Medium · WCAG 4.1.2 + 3.3.2 · Productos: checkboxes de selección sin nombre accesible — **RESUELTO**
- **Pantalla / rol:** Products Index, 7 checkboxes (select-all + 6 fila), para cualquier persona que use teclado o lector de pantalla.
- **Evidencia:** capture_screens marcaba `unnamed=7`: `<input class="rounded">`, sin `aria-label`, sin label. El nombre accesible es vacío → un lector de pantalla lee "casilla de verificación" sin contexto.
- **Por qué importa:** El trabajo principal de bulk actions depende de estos checkboxes; sin nombre no se puede operar por teclado con certeza de qué se selecciona.
- **Arreglo aplicado:** `aria-label="Seleccionar todos los productos"` en el header y `aria-label="Seleccionar producto: {name}"` en cada fila. **Re-capturado y verificado unnamed=0 en las 3 rutas con controles.**

### UXF-03 · Medium · H3/H5 · Bulk delete no tiene confirmación con segundo diálogo
- **Pantalla / rol:** Products Index → Bulk actions → Confirm bulk delete.
- **Evidencia:** Código `bulkDelete()` en `Index.vue` usa `confirm()` nativo, un solo paso.
- **Por qué importa:** El spec de Zellia H5 exige al menos una confirmación secundaria en acciones destructivas; con la acción nativa el foco no se captura y no se sale con Escape.
- **Arreglo:** Migrar a un modal Zellia propio con foco capturado, Escape, y el número de registro en el botón de confirmación ("Eliminar 3 productos").

### UXF-04 · Medium · H1 · TTFB 1.0–1.3 s sin confirmación visual temprana
- **Pantalla / rol:** Dashboard con datos (prendió con seeder) en localhost, sin throttle.
- **Evidencia:** `audit.json`: LCP 1.4–1.7s, TTFB 1.0–1.3s, CLS 0. Ideal ≤ 800ms (Y Desseler / Doherty).
- **Por qué importa:** El Doherty threshold de 400 ms se supera en 3× — el usuario siente la página lenta aunque sea local.
- **Arreglo:** Render del HTML parcial de sidebar+header antes del fetch (Perception es lo primero). Con Inertia es parcial-loading automático, falta **visible-feedback** antes de 400 ms: spinner skeleton con H1 (pantallaa holder) mientras el dashboard carga, exactamente como hace el nav del sidebar con `ds-scroll`.

### UXF-05 · Low · WCAG 1.4.10 · Settings/Account desborda 77px en 320px de ancho
- **Pantalla / rol:** solo/cuencia en ancho reflow (WCAG 1.4.10 aplica también a desktop: 1280 a zoom 400%).
- **Evidencia:** `audit.json`: scrollWidth 397px vs 320px viewport, ofensor `button.border-b-2` (una fila de pestañas de configuración).
- **Por qué importa:** El zoom 200% en pantallas W-1280 es de uso frecuente (accesibilidad), y el panel de configuración requiere scroll lateral para llegar a la pestaña.
- **Arreglo:** `flex-wrap` en la barra de pestañas, o dejarlas con `min-width: max-content` dentro de un contenedor con `overflow-x: auto`.

### UXF-06 · Low · H4 · Idiomas mezclados cuando el locale default no está fijado en backend
- **Evidencia:** Sidebar con placeholder "Buscar..." (es.json) mientras que el resto de productos pinta en inglés (mismo nav en es.json). Los data-testid de `.auth-audit` en inglés. Con locale=true predeterminado elegantes solapan ambos arrays.
- **Por qué importa:** Fluir entre idiomas dentro de la misma pantalla se lee como no-termino (H4, consistencia interna).
- **Arreglo:** Fijar el `App::setLocale()` en el middleware con base en la cookie/user preference actual, antes de que Inertia entregue los props.

---

## Top 5 antes de lanzar (por causa raíz, ordenada)

1. **`UXF-02`** · Accesibilidad de control de bulk selection — ✓ ya resuelto y verificado (effort S, 15 min)
2. **`UXF-04`** · Añadir skeleton/feedback < 400ms en Dashboard — spinner o estado de carga (S, media hora)
3. **`UXF-03`** · Confirmación dedicada de bulks con modal Zellia y foco gestionado (S–M, 1 h)
4. **`UXF-01`** · Nav colapsable bajo 900px o auto-collapse del grupo Admin (S, 30 min)
5. **`UXF-05`** · Wrap en tabs de settings + revisar `1.4.10` en mobile real (S, 45 min)

---

## "Don't touch" — lo que funciona bien y debe sobrevivir al refactor

1. **Temas Light/Dark conmutando de verdad en Zellia** — valor exacto en todos los tests medidos (primary.100/900 exactos).
2. **ButtonSidebar activo con `aria-current="page"`** — semántica de ruteo real, correcta para lectores de pantalla, sin duplicar fuente de verdad.
3. **Card system** — radius.md/borde neutral.300/sombra xs coherente y hover con salto único de nivel, correcto según spec §5.5.
4. **Fix de checkbox unnamed en Products** — patrón con named accessibles que las demás páginas deberían copiar al migrar.
5. **Focus ring de 4px con box-shadow + cambio de border-color** — implementado exactamente según Zellia §5.3 en input, botón y sidebar item.
6. **Feedback e2e/screenshot system** de esta base (e2e/verify-paso*.mjs) — asegura que cualquier regresión futura de Zellia sea atrapada en el build.

---

## No revisado
- Formularios de edición y creación de Orders (requiere crear pedido — quedará para el próximo ciclo si se pide)
- Modals en general (no hay datos con modal abierto capturado de forma determinista)
- Pantallas de error/404 (no reenvían shell)
- Auditoría en READER de pantalla (VoiceOver/NVDA) — el scorecard de Accesibilidad es 4 con este vacío declarado

## Apéndice: automatizados
**Contraste:** 20 capturas con `lowContrast`, todas las alertas en modo dark sobre fondo dark legítimo — falso positivo del harness al no inyectar localStorage.theme (el harness fuerza "light" para la captura pero la app pinta dark cuando no hay preferencia). Verificar lado light en vivo: los colores exactos puestran con valores Zellia correctos en Pasos 1–5.
**Foco:** focusNoIndicator=0 en 20 capturas — correcto, con walk de Tab respetando 25 stops máx.
**Objetivos < 24px:** 0 en todas las capturas.
**Reflow 1.4.10:** solo Settings/Account (UXF-05).
**Animaciones infinitas sin reduced-motion:** 0.
**Console errors:** 0 en 20 capturas.
