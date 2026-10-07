# Fase 0 — Estado real de Inventoros (tomfic)
**Fecha:** 5 oct 2026 · **Rama:** main (`ae0ab03`) · **Entorno local Windows:** PHP 8.4.25, Composer 2.10.3, sqlite

## Suites corridos
| Suite | Resultado | Detalle |
|---|---|---|
| PHPUnit (`php artisan test`) | ✅ **1413 passed, 1 skipped, 0 failed** | 4.466 asserts, 227 s, sqlite :memory: |
| Playwright (setup + chromium) | ✅ 57 passed / ⚠️ **15 failed** | 72 tests chromium; fallas = bugs de prueba + 2 bugs reales |

**Conclusión:** el backend está mucho mejor de lo que la auditoría de 2025-10 decía. De sus 5 gaps críticos, 3 ya resueltos verificados en código (Stock Adjustments existe; `update()` de órdenes reemplaza items con locks y guards; import mapea status→is_active).

## Los 15 fallos e2e — triaje
### Bugs de la PRUEBA (13) — la app está bien, el test quedó viejo
1. **`auth.spec` login page** — busca `input[name="email"]` y un heading /login|sign in/. La app usa `id="email"` sin `name` y GuestLayout no muestra heading; el title es "Iniciar sesión". FIX: usar `#email`, `#password`, y cambiar el heading a texto real (o asertar por title).
2. **`auth.spec` validation errors / invalid creds / registration** — mismos selectores.
3. **`activity-log` filter options** — locator `input[type="date"], [name*="date"]` ambiguo: hay 2 inputs (date_from/date_to). FIX: `#date_from`.
4. **`activity-log` change details** — el matcher de texto genera un RegExp con flags inválidos (`'i, summary, details'`): `<summary>` cayó dentro de un regex con `|`. FIX: separar selectores, no concatenarlos en un `text=/.../`.
5. **`products` create a new product** — timeout 30 s: asertos/UI cambiaron (probablemente modales de variante). Reescribir contra la UI actual.
6. **`products` bulk actions** — `text=/selected|print barcodes/i` no matchea la barra de acciones actual.
7. **`settings` Org update** — el test intenta `nameInput.fill(...)` pero **todos los campos están disabled**. Ver triaje real abajo (bug posible de la app).
8. **`settings` password change** — el input `#password` existe pero está oculto: la sección de cambio de contraseña está plegada/tras una pestaña. FIX: click en la pestaña/panel primero.
9. **`settings` user details / role permissions** — `input[type="checkbox"]` sin scope matchea 52 checkboxes. FIX: `.first()` o un checkbox concreto por permiso.

### Bugs reales de la APP (2) — investigar y arreglar
- **A1 · Campos de organización disabled para el admin.** DB dice `is_admin=1`, `role=admin`, pero `Settings/Organization/Index.vue` renderiza los 8 campos disabled y los oculta con `v-if="isAdmin"` (solo la pestaña de usuarios). Sospecha: `props.user.is_admin` llega vacío al componente — el accessor `getIsAdminAttribute()` no se serializa al JSON del modelo. FIX construido: computarlo en el controller (`'isAdmin' => $user->is_admin`) y usar ese prop. **P0: el admin no puede editar su propia organización.**
- **A2 · La barra de acciones masivas no aparece al seleccionar productos.** Investigar: `Products/Index.vue` selection → la acción *Print barcodes* del test busca `text=/selected|print barcodes/i` y no matchea la UI actual — confirmar si es solo copy o la barra de verdad no se muestra.

## Entorno que quedó montado
- `.env` con APP_KEY + sqlite; migraciones + seeders corriendo; `public/build` compilado (varios chunks >500 kB → code-split pendiente, ver mejoras).
- Chromium 1217/1234/1243 en `ms-playwright`. Laravel y Playwright corren desde `Projects\tomfic`.
- Para repro: `php artisan serve --host=127.0.0.1 --port=8000` (PHP 8.4) + `npx playwright test --project=chromium` con `APP_URL=http://127.0.0.1:8000`.

## Lo que sigue (Fase 1 re-scoped por los hallazgos)
1. **Arreglar A1** (org settings deshabilitados para admin) — backend 1 línea + front prop, ~30 min.
2. **Arreglar A2** (bulk bar) — investigar `Products/Index.vue`.
3. **Actualizar los 13 tests e2e** con los selectores actuales (~2 h).
4. Re-run de la suite completa y subir el fix a la rama `fix/fase-1-actualizada`.
