// Paso 5b — Products CON datos: badges de estado + toolbar + tabla
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

await page.goto('http://127.0.0.1:8000/products', { waitUntil: 'networkidle' });
await page.waitForTimeout(800);

const badge = page.locator('.zellia-badge').first();
if (await badge.count()) {
  const b = await badge.evaluate(el => {
    const s = getComputedStyle(el);
    return { h: Math.round(el.getBoundingClientRect().height), bg: s.backgroundColor, color: s.color, radius: s.borderRadius, cls: el.className.slice(0, 60) };
  });
  console.log('=== Badge con datos ===');
  console.log('alto  :', b.h + 'px', [20,24].includes(b.h) ? '✓' : '✗', '|', b.cls);
  console.log('bg/txt:', b.bg, '/', b.color);
  // pareja light/dark de la misma familia
  const fam = { 'rgb(234, 255, 241)': 'success.light', 'rgb(255, 247, 215)': 'warning.light', 'rgb(255, 215, 215)': 'error.light', 'rgb(230, 242, 255)': 'info.light', 'rgb(245, 249, 254)': 'primary.100' };
  const famDark = { 'rgb(21, 128, 61)': 'success.dark', 'rgb(146, 64, 14)': 'warning.dark', 'rgb(153, 27, 27)': 'error.dark', 'rgb(13, 71, 161)': 'info.dark', 'rgb(0, 32, 65)': 'primary.900' };
  console.log('fondo é familia:', fam[b.bg] || '⚠ ' + b.bg);
  console.log('texto é familia:', famDark[b.color] || '⚠ ' + b.color);
} else {
  console.log('✗ sin badges');
}

await page.screenshot({ path: 'docs/design-system/zellia/verify-paso5b-products.png' });
await ctx.close();
await browser.close();
console.log('FIN 5b');
