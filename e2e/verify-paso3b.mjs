// Paso 3b — verificación separada Light (con theme=light forzado) y Dark (default de la app)
import { chromium } from 'playwright-core';

const browser = await chromium.launch();

// ---- LIGHT (forzado por localStorage, igual que hace ThemeToggle) ----
{
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await ctx.newPage();
  await page.addInitScript(() => localStorage.setItem('theme', 'light'));
  await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });
  await page.fill('#email', 'e2e-test@inventoros.test');
  await page.fill('#password', 'E2ETestPassword123!');
  await page.locator('form button').first().click();
  await page.waitForURL('**/dashboard', { timeout: 15000 });
  await page.waitForTimeout(600);

  const sidebar = await page.locator('aside.zellia-sidebar').evaluate(el => {
    const s = getComputedStyle(el);
    return { bg: s.backgroundColor, br: s.borderRightColor };
  });
  const active = await page.locator('aside .zellia-sidebar-item[aria-current="page"]').first().evaluate(el => {
    const s = getComputedStyle(el);
    return { bg: s.backgroundColor, w: getComputedStyle(el).fontWeight };
  });
  const search = await page.locator('.zellia-sidebar__search').evaluate(el => getComputedStyle(el).backgroundColor);
  const header = await page.locator('.zellia-header').first().evaluate(el => getComputedStyle(el).backgroundColor);

  console.log('=== LIGHT (theme=light) ===');
  console.log('sidebar bg :', sidebar.bg, sidebar.bg === 'rgb(245, 249, 254)' ? '✓ primary.100' : '✗');
  console.log('borde dcho :', sidebar.br, sidebar.br === 'rgb(206, 206, 206)' ? '✓ Mono/300' : '✗');
  console.log('item activo:', active.bg, active.bg === 'rgb(87, 153, 186)' ? '✓ secondary.500' : '✗', '| peso', active.w, active.w === '500' ? '✓ medium' : '✗');
  console.log('buscador   :', search, search === 'rgb(230, 235, 239)' ? '✓ primary.200' : '✗');
  console.log('header     :', header, header === 'rgb(245, 249, 254)' ? '✓ primary.100' : '✗');
  await page.screenshot({ path: 'docs/design-system/zellia/verify-paso3-light.png' });
  await ctx.close();
}

// ---- DARK (default de la app si no hay theme guardado) ----
{
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await ctx.newPage();
  await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });
  await page.fill('#email', 'e2e-test@inventoros.test');
  await page.fill('#password', 'E2ETestPassword123!');
  await page.locator('form button').first().click();
  await page.waitForURL('**/dashboard', { timeout: 15000 });
  await page.waitForTimeout(600);

  const cls = await page.evaluate(() => document.documentElement.classList.contains('dark'));
  const sidebar = await page.locator('aside.zellia-sidebar').evaluate(el => getComputedStyle(el).backgroundColor);
  const active = await page.locator('aside .zellia-sidebar-item[aria-current="page"]').first().evaluate(el => getComputedStyle(el).backgroundColor);
  const header = await page.locator('.zellia-header').first().evaluate(el => getComputedStyle(el).backgroundColor);

  console.log('=== DARK (app default) ===  html.dark:', cls);
  console.log('sidebar :', sidebar, sidebar === 'rgb(0, 32, 65)' ? '✓ primary.900' : '✗');
  console.log('item    :', active, active === 'rgb(2, 53, 98)' ? '✓ primary.800' : '✗');
  console.log('header  :', header, header === 'rgb(0, 32, 65)' ? '✓ primary.900' : '✗');
  await page.screenshot({ path: 'docs/design-system/zellia/verify-paso3-dark.png' });
  await ctx.close();
}

await browser.close();
console.log('FIN VERIFICACION 3b');
