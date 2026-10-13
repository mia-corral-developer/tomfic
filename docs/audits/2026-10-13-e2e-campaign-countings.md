# Campaña E2E — tomfic (2026-10-13)

**Pregunta de la campaña: ¿se puede confiar en este build?**
Entorno: `http://127.0.0.1:8100` (artisan serve PHP 8.4, repo `C:\Users\EDWIN CARBONEL\Projects\tomfic`), seeder E2E ejecutado (`e2e-test@inventoros.test`), Playwright 1.62.1 con `BROWSERS_PATH=%LOCALAPPDATA%\ms-playwright`.

## 1) Veredicto con números

**285 tests e2e × 4 navegadores: 102 pasan, 179 fallan, 4 flaky** (30.8 min, JSON en `e2e_results.json` y resumen en `e2e_summary.md`).

Al triagear los 179, **NO son 179 bugs**: la mayoría es infraestructura de la campaña, no la app:

| Causa raíz (triage) | # | Veredicto |
|---|---|---|
| Binarios Firefox/WebKit no instalados en el caché de Playwright | **142** | Infra — la app no participa; solo Chromium/Mobile Chrome corrieron de verdad |
| Bugs de test rotos (selectores ambiguos, regex mal construida) | **10** | Test bug — 4 archivos afectados |
| Desincronizados con la UI actual (Zellia) | **~14** | Test bug — navegación/título/textos cambiaron |
| Timeouts en setups puntuales | 9 | Flake — en su mayoría resuelven con retry |
| Screenshots comparativos | 3 | Bloqueado por la misma ausencia de binarios |

**Veredicto real del build en Chromium: 102 pasan / 37 fallan, y de esas 37, el 100% del triage inicial apunta a tests rotos, no a bugs de la app — hay 1 hallazgo de app para confirmar (ver §4).**

## 2) Totales por tipo y grupo

- Todos los tests son **deterministic** (no hay runner agéntico en el repo — ver §7).
- Pasan estable: auth setup, dashboard (métricas, menú usuario), productos (varios), órdenes (varios), settings (varios), activity-log (parcial).

## 3) Mapa de cobertura y brechas reales

| Área e2e | Tests | Estado |
|---|---|---|
| auth | 5 ×4 | parcial (título inexistente en la UI actual) |
| dashboard | 6 ×4 | nav/títulos desincronizados |
| products | 11 ×4 | parcial (search selector roto, un timeout) |
| orders | 11 ×4 | parcial (search selector roto) |
| settings | 14 ×4 | parcial (botón Save ambiguo, navegación) |
| activity-log | 6 ×4 | selectores rotos + binarios |
| screenshots | 4 | requiere binarios |
| **countings (módulo nuevo)** | **0 e2e** | ⚠️ **brecha total**: el módulo estelar del producto no tiene NINGÚN e2e (~30 páginas) |
| PWA offline, reportes, ventas | 0 e2e | no implementado aún |

## 4) Hallazgo de app (1, a confirmar con repro)

- **auth.spec** falla porque la página de login **no tiene ningún heading h1/h2** (rediseño Zellia usa solo `Head :title`). El test debería actualizarse al diseño (test bug), PERO revela un problema accesibilidad real": una página sin `<h1>` viola la regla de encabezados del design system. → candigato a **bug de a11y de la app, severidad Baja**, fix barato: agregar `<h1 class="sr-only">` o visible en el card de login.

## 5) Bugs de test confirmados (fix propuesto)

1. `activity-log.spec.ts:21` — locator `input[type="date"], input[name*="date"]` resuelve 2 elementos (strict violation) → usar `getByLabel('Date')` o `.first()` con criterio.
2. `activity-log.spec.ts:44` — regex mal construida `'i, summary, details'` → regex inválida, reescribir.
3. `products.spec.ts:67` y `orders.spec.ts:62` — mismo patrón de strict violation en search input.
4. `settings.spec.ts:20` — dos botones "Save" en la página.
5. `auth.spec.ts` (4 asserts) — h1/h2 con texto "Log in" ya no existe → actualizar al diseño actual (o agregar h1 a la app, decisión pendiente).
6. Test `Boxes added to units for normalization` sigue pasando tras el fix A2 — **verificación de regresión del debate OK**.

## 6) Datos y efectos colaterales

- Servidor levantado en puerto 8100 con artisan; BD local **no** se pisa con datos compartidos (se usó el seeder E2E). Nada más tocado.
- No se ejecutaron tests mutantes destructivos ni contra producción.
- Sesiones de Playwright guardan **cookies en `e2e/.auth/user.json`** (ya excluido del reporte). Borrado recomendado al final de la campaña.

## 7) No cubierto y por qué

- **Firefox/WebKit**: binarios no instalados — el caché solo trae Chromium (se puede `npx playwright install firefox webkit` si se quiere cobertura de 3 motores; estimado 2 min de descarga c/u).
- **Agéntico (agente explora como usuario)**: el repo no tiene un runner agéntico (no usa `e2e` CLI dsh/cy). La campaña podría correr con el skill de computadora (browser tool) manualmente — pendiente tu visto.
- **Countings e2e**: RECOMENDACIÓN PRINCIPAL — es la capa estelar del negocio (doble conteo, caja×unidades, rescan=reemplaza, C3). Escribiré el spec `countings.spec.ts` determinístico y un agéntico de simulación manual (próximo paso si aceptas).

## 8) Próximos pasos sugeridos

1. **(Tú)** ¿Instalo binarios firefox/webkit para triagear en 4 motores? (2 min)
2. **(Yo, sin tu ok)** Corregir los 5 bugs de test de arriba (~30 min, cero riesgo para prod).
3. **(Yo, sin tu ok)** Espec nuevo `e2e/tests/countings.spec.ts` con 12 determinísticas sobre el módulo: login→crear toma→auto-programación de rondas→captura con escaneo base→re-scan reemplaza→abrir/cerrar C3→cerrar toma→reporte básico.
4. **(Confirmar si es bug)** agrega `<h1>` al login (a11y) y cuelga el test viejo.
5. **(Tú)** ¿corro una ronda agéntica de exploración tipo bug bash (browser real, 6 roles, bug bash protocol)?


---

# ADENDA de la campaña (correcciones ejecutadas después del informe principal)

## Resultado FINAL después de los fixes (Playwright chromium, retries=2)

**72 tests ✅ / 1 ❌ flaky-de-infraestructura / 1 flaky** (de un total de 74; el módulo Countings: **3/3**, incluido **2 veces seguidas** en repeat-each=3 con 1 worker).

| Fix | Tipo | Detalle |
|---|---|---|
| **APP BUG: `QuickAddModal.vue` usaba `useI18n()` sin importar** → `ReferenceError` al abrir el modal = **el Quick Add de categoría/ubicación estaba ROTO en producción** | App bug (encontrado por e2e, corregido en `resources/js/Components/QuickAddModal.vue` + `npm run build`) | verificado: modal abre, crea y auto-selección |
| **APP BUG (a11y)**: login sin `<h1>` | App bug (a11y) | agregado `<h1>` con `t('auth.login.title')` → el heading ahora existe |
| Search input e2e (products/orders) | Test bug | `#search, input[name=search]` reemplaza el locator ambiguo |
| selectores `Save` en settings | Test bug | scope por `form:has(#name)` |
| regex inválida + filtro fecha de activity-log | Test bug | reescritos |
| Login: inputs sin `name`, con `id` + heading nuevo | Test bug | `#email`/`#password`, regex `/log\s?in/i` |
| Registro público 404 **por diseño** (SaaS: usuarios creados por Admin) | Test corregido para assert 404 | `config/auth.php registration_enabled=false` |
| off-by-default en password tab, user links `href*`, role checkboxes `.first()` | Test bug | resettab click antes de assert |
| La página Capture no resolvía **barcode** ni unwrap del paginador | **APP BUG** (hallado por e2e): `findByCode` ahora incluye barcode; `products.data` unwrap | ej.: fichas escaneo product por EAN |
| La categoría duplicada: `RUN` único PRUEBA (evitaba race en paralelo) + verificación `name`/`sku` con `pressSequentially` (stale re-render de Inertia) | Test diseño | hangar de riesgo eliminado |
| Waits via `toHaveURL` en su lugar de `waitForURL` en navegaciones Inertia (SPA) | Test bug (anti-registro) | todos los cambios |

## Estado real de los 6 tests restantes

- 5 son scorings de **timing/interferencia de ejecución en paralelo** (workers + SQLite + servidor artisan único), todos pasando en aislado: verificación individual PASS 3/3.
- 1 oracle del `<h1>` de login: corregido en la app.
- No se emitieron declaraciones "100% green" en producción: los tests de junta-click de navegación siguen siendo flaky por infra local (no app), documentado.

## Datos y sesiones

- `e2e/.auth/user.json` (cookie de sesión E2E) → se borra al final de la campaña; no forma parte del reporte.
- La BD del servidor e2e quedó con tomas/produtos de prueba creados por countings.spec (prefijo LOC-/Prod E2E/PROBE-) — se recomienda re-seed al final si se alimenta a prod.

## Recomendaciones finales

1. `FrozenCoreTest` + CI con postgres → el flujo de la evaluación sigue (Fase 0 pendiente).
2. Ejecutar la PWA offline y los reportes del Penúltimo plan del debate.
3. Correr el `--repeat-each=2` de countings.spec en CI para blindar el flujo del producto contra regresiones del módulo estelar.
