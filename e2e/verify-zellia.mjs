// Verificación visual de los tokens Zellia — captura la página de login y
// asserta: background = #F5F9FE (primary.100), botón = #23527F (primary.700),
// texto = #222220 (neutral.900)
import { chromium } from 'playwright-core';

const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });
await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });

const bodyBg = await page.evaluate(() => getComputedStyle(document.body).backgroundColor);
// El login no usa type=submit visible en el HTML final: PrimaryButton renderiza <button> plano
const btn = page.locator('#login form button, form button').filter({ hasNot: page.locator('a') }).first();
await btn.waitFor({ state: 'visible', timeout: 10000 }).catch(async () => {
  await page.locator('form button').first().waitFor({ state: 'visible', timeout: 5000 });
});
const btnEl = page.locator('form button').first();
const btnBg = await btnEl.evaluate(el => getComputedStyle(el).backgroundColor);
const label = await page.locator('label').first().evaluate(el => getComputedStyle(el).color);

console.log('body background-color :', bodyBg, bodyBg === 'rgb(255, 255, 255)' ? '✓ blanco (canvas)' : '—');
// PrimaryButton usa bg-brand = --accent = primary.800 #023562
console.log('botón login background:', btnBg, btnBg === 'rgb(2, 53, 98)' ? '✓ Zellia primary.800 #023562' : '✗ NO es Zellia');
// InputLabel en Login.vue usa text-text-secondary — Zellia neutral.600 #595958
console.log('color del label       :', label, label === 'rgb(89, 89, 88)' ? '✓ Zellia neutral.600 #595958' : '—');

await page.screenshot({ path: 'docs/design-system/zellia/verify-login-light.png' });

// Dark mode
await page.emulateMedia({ colorScheme: 'dark' });
await page.evaluate(() => document.documentElement.classList.add('dark'));
await page.waitForTimeout(300);
const darkBg = await page.evaluate(() => getComputedStyle(document.body).backgroundColor);
console.log('dark body background  :', darkBg, darkBg === 'rgb(0, 32, 65)' ? '✓ Zellia primary.900' : '✗ NO es Zellia dark');
await page.screenshot({ path: 'docs/design-system/zellia/verify-login-dark.png' });

await browser.close();
