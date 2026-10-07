// Paso 5 — verificación StatTile + Badge Zellia en el dashboard LIVE
import { chromium } from 'playwright-core';

const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
const page = await ctx.newPage();
await page.addInitScript(() => localStorage.setItem('theme', 'light'));
await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });
await page.fill('#email', 'e2e-test@inventoros.test');
await page.fill('#password', 'E2ETestPassword123!');
await page.locator('form button').first().click();
await page.waitForURL('**/dashboard', { timeout: 15000 });
await page.waitForTimeout(600);

const stat = page.locator('.zellia-stat').first();
if (await stat.count()) {
  const m = await stat.evaluate(el => {
    const s = getComputedStyle(el);
    const lbl = el.querySelector('.zellia-stat__label');
    const val = el.querySelector('.zellia-stat__value');
    return {
      radius: s.borderRadius,
      bg: s.backgroundColor,
      labelFs: getComputedStyle(lbl).fontSize,
      labelCase: getComputedStyle(lbl).textTransform,
      valFs: getComputedStyle(val).fontSize,
      valLh: getComputedStyle(val).lineHeight,
    };
  });
  console.log('=== StatTile ===');
  console.log('radius    :', m.radius, parseFloat(m.radius) === 8 ? '✓ 8' : '✗');
  console.log('fondo     :', m.bg, m.bg === 'rgb(255, 255, 255)' ? '✓ blanco' : '✗');
  console.log('label     :', m.labelFs, m.labelFs === '12px' ? '✓ label 12' : '✗', '| case:', m.labelCase, m.labelCase === 'none' ? '✓ sin uppercase' : '✗');
  console.log('valor     :', m.valFs, '/', m.valLh, m.valFs === '20px' && m.valLh === '28px' ? '✓ subtitle1 20/28' : '✗');
} else {
  console.log('✗ NO hay .zellia-stat en el dashboard');
}

const badge = page.locator('.zellia-badge').first();
if (await badge.count()) {
  const b = await badge.evaluate(el => {
    const s = getComputedStyle(el);
    return { h: Math.round(el.getBoundingClientRect().height), bg: s.backgroundColor, color: s.color, radius: s.borderRadius };
  });
  console.log('=== Badge (neutral, del listado de órdenes si hay; sino el que aparezca) ===');
  console.log('alto  :', b.h + 'px', [20,24].includes(b.h) ? '✓ escala badge' : '✗');
  console.log('bg/txt:', b.bg, '/', b.color, '| radius:', b.radius, parseFloat(b.radius) === 4 ? '✓ sm' : '');
} else {
  console.log('(sin badges visibles en dashboard vacío — se verificarán con datos)');
}

await page.screenshot({ path: 'docs/design-system/zellia/verify-paso5-dashboard.png' });
await ctx.close();
await browser.close();
console.log('FIN PASO 5 (parte A)');
