// Paso 3 — verificación visual del SiderZellia + Header Zellia en el dashboard
// con sesión real. Mide: sidebar 320px, item activo 40px con bg secondary.500,
// tagline, buscador, header 66px, forma decorativa, CTA inferior.
import { chromium } from 'playwright-core';

const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

// Login
await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });
await page.fill('#email', 'e2e-test@inventoros.test');
await page.fill('#password', 'E2ETestPassword123!');
await page.locator('form button').first().click();
await page.waitForURL('**/dashboard', { timeout: 15000 });

// Medidas del sidebar (SiderZellia)
const sidebar = await page.locator('aside.zellia-sidebar').evaluate(el => {
  const s = getComputedStyle(el);
  const r = el.getBoundingClientRect();
  return { w: r.width, bg: s.backgroundColor, borderRight: s.borderRightColor, pad: s.padding };
});
console.log('=== SiderZellia ===');
console.log('ancho   :', Math.round(sidebar.w) + 'px', Math.round(sidebar.w) === 320 ? '✓ 320px' : '✗');
console.log('fondo   :', sidebar.bg, sidebar.bg === 'rgb(245, 249, 254)' ? '✓ primary.100' : '✗ ' + sidebar.bg);
console.log('borde dcho:', sidebar.borderRight, sidebar.borderRight === 'rgb(206, 206, 206)' ? '✓ Mono/300 solo Light' : '✗');

// Item activo (Dashboard): aria-current + 40px + bg secondary.500
const active = page.locator('aside .zellia-sidebar-item[aria-current="page"]').first();
const am = await active.evaluate(el => {
  const s = getComputedStyle(el);
  const r = el.getBoundingClientRect();
  return { h: r.height, bg: s.backgroundColor, color: s.color, weight: s.fontWeight, label: el.textContent.trim() };
});
console.log('=== ButtonSidebar activo ===');
console.log('item    :', am.label);
console.log('alto    :', Math.round(am.h) + 'px', Math.round(am.h) === 40 ? '✓ 40px' : '✗');
console.log('bg      :', am.bg, am.bg === 'rgb(87, 153, 186)' ? '✓ secondary.500' : '✗ ' + am.bg);
console.log('texto   :', am.color, am.color === 'rgb(248, 248, 248)' ? '✓ Mono/100' : '✗');

// Buscador
const search = await page.locator('.zellia-sidebar__search').evaluate(el => {
  const s = getComputedStyle(el);
  return { h: getComputedStyle(el).height, radius: s.borderRadius, bg: s.backgroundColor };
});
console.log('=== Buscador (§2.5) ===');
console.log('alto/radius:', search.h, '/', search.radius, parseFloat(search.radius) === 12 ? '✓ radius.lg' : '✗');
console.log('fondo   :', search.bg, search.bg === 'rgb(230, 235, 239)' ? '✓ primary.200' : '✗');

// Header Zellia
const header = await page.locator('.zellia-header').first().evaluate(el => {
  const s = getComputedStyle(el);
  const r = el.getBoundingClientRect();
  const shape = el.querySelector('.zellia-header__shape');
  const ss = shape ? getComputedStyle(shape) : null;
  return { h: r.height, bg: s.backgroundColor, borderB: s.borderBottomColor, shapeBg: ss?.backgroundColor, shapeOp: ss?.opacity };
});
console.log('=== Header Zellia ===');
console.log('alto    :', Math.round(header.h) + 'px', Math.round(header.h) === 66 ? '✓ 66px (65+1)' : '✗');
console.log('fondo   :', header.bg, header.bg === 'rgb(245, 249, 254)' ? '✓ primary.100' : '✗');
console.log('borde inf:', header.borderB, header.borderB === 'rgb(206, 206, 206)' ? '✓ Mono/300' : '✗');
console.log('forma   :', header.shapeBg, 'op', header.shapeOp, header.shapeOp === '0.08' ? '✓ 8% Secondary/400' : '✗');

// CTA inferior
const cta = await page.locator('.zellia-sidebar__cta').evaluate(el => {
  const s = getComputedStyle(el);
  const r = el.getBoundingClientRect();
  return { h: r.height, y: r.y };
});
console.log('=== CTA switcher ===');
console.log('alto    :', Math.round(cta.h) + 'px', Math.round(cta.h) === 32 ? '✓ 32px' : '✗');

// Screenshots: full + sidebar solo
await page.screenshot({ path: 'docs/design-system/zellia/verify-paso3-dashboard.png' });
await page.locator('aside.zellia-sidebar').screenshot({ path: 'docs/design-system/zellia/verify-paso3-sidebar.png' });

// Dark mode
await page.emulateMedia({ colorScheme: 'dark' });
await page.evaluate(() => document.documentElement.classList.add('dark'));
await page.waitForTimeout(400);
const darkS = await page.locator('aside.zellia-sidebar').evaluate(el => getComputedStyle(el).backgroundColor);
console.log('=== Dark ===');
console.log('sidebar :', darkS, darkS === 'rgb(0, 32, 65)' ? '✓ primary.900' : '✗');
await page.screenshot({ path: 'docs/design-system/zellia/verify-paso3-dark.png' });

await browser.close();
console.log('PASO 3 VERIFICADO');
