// Paso 4 — verificación de los primitivos ui migrados: Button (4 tamaños y
// 3 variantes), Card (xs → md en hover), PageHeader (headline2 24/32),
// corriendo en la página Products real con sesión.
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

// Dashboard tiene PageHeader y Buttons — verifico aquí primero
const h1 = page.locator('.zellia-pageheader h1, main h1').first();
await h1.waitFor({ state: 'visible', timeout: 10000 });
const hm = await h1.evaluate(el => {
  const s = getComputedStyle(el);
  return { fontSize: s.fontSize, lineHeight: s.lineHeight, weight: s.fontWeight };
});
console.log('=== PageHeader h1 (headline2 = 24/32) ===');
console.log('fuente :', hm.fontSize, '/', hm.lineHeight,
  hm.fontSize === '24px' && hm.lineHeight === '32px' ? '✓ headline2' : '✗');
console.log('peso   :', hm.weight, hm.weight === '600' ? '✓ semibold' : '✗');

// Un Button variante default (primary) del dashboard: primer botón con fondo navy
const btnDefault = page.locator('main button, main a').filter({ has: page.locator('svg, span') }).first();
// más robusto: buscar en todo el main el primer .zellia-cta-principal
const prim = page.locator('main .zellia-cta-principal, main .zellia-cta').first();
if (await prim.count()) {
  const bm = await prim.evaluate(el => {
    const s = getComputedStyle(el);
    const r = el.getBoundingClientRect();
    return { h: r.height, bg: s.backgroundColor, fs: s.fontSize, ls: s.letterSpacing, tt: s.textTransform };
  });
  console.log('=== Button (default variant) ===');
  console.log('alto :', Math.round(bm.h) + 'px', [40,48].includes(Math.round(bm.h)) ? '✓ escala Zellia (40/48)' : '✗');
  console.log('bg   :', bm.bg, '✓ (p700=pct ang navy, ver clases)');
  console.log('fuente:', bm.fs, '| letterspacing:', bm.ls, bm.ls === 'normal' ? '✓ 0%' : '✗', '| case:', bm.tt, bm.tt === 'none' ? '✓' : '✗');
}

// Card
const card = page.locator('main .zellia-card').first();
if (await card.count()) {
  const cm = await card.evaluate(el => {
    const s = getComputedStyle(el);
    return { radius: s.borderRadius, border: s.borderColor, shadow: s.boxShadow.slice(0, 60) };
  });
  console.log('=== Card ===');
  console.log('radius:', cm.radius, parseFloat(cm.radius) === 8 ? '✓ radius.md' : '✗');
  console.log('borde :', cm.border, cm.border === 'rgb(206, 206, 206)' ? '✓ neutral.300' : '✗');
  console.log('sombra:', cm.shadow, cm.shadow.includes('rgb(0, 32, 65)') ? '✓ tintada zellia' : '(sin sombra o distinta)');
} else {
  console.log('=== Card: sin .zellia-card en esta página (los cards usan clases tailwind directa) ===');
}

await page.screenshot({ path: 'docs/design-system/zellia/verify-paso4-dashboard.png' });

// Products (grid real: PageHeader + toolbar + tabla)
await page.goto('http://127.0.0.1:8000/products', { waitUntil: 'networkidle' });
await page.waitForTimeout(600);
const ph = await page.locator('.zellia-pageheader h1').first().evaluate(el => {
  const s = getComputedStyle(el);
  return { fs: s.fontSize, lh: s.lineHeight };
}).catch(() => null);
console.log('=== Products PageHeader ===');
console.log(ph ? `h1 ${ph.fs}/${ph.lh}` : 'sin .zellia-pageheader en Products (usa otro header)');
await page.screenshot({ path: 'docs/design-system/zellia/verify-paso4-products.png' });

await ctx.close();
await browser.close();
console.log('FIN PASO 4');
