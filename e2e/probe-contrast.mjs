
const { chromium } = require('playwright-core');
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1440, height: 900 } });
  const p = await ctx.newPage();
  await p.addInitScript(() => localStorage.setItem('theme', 'light'));
  await p.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });
  await p.fill('#email', 'e2e-test@inventoros.test');
  await p.fill('#password', 'E2ETestPassword123!');
  await p.locator('form button').first().click();
  await p.waitForURL('**/dashboard', { timeout: 15000 });
  await p.waitForTimeout(500);
  const clsRoot = await p.evaluate(() => document.documentElement.className);
  const lbl = await p.locator('.zellia-stat__label').first().evaluate(el => ({
    color: getComputedStyle(el).color,
    opacity: getComputedStyle(el).opacity,
    text: el.textContent.trim(),
  }));
  // ascendentes con class dark
  const darkAnc = await p.evaluate(() => {
    const el = document.querySelector('.zellia-stat__label');
    let n = el, chain = [];
    while (n && n !== document.documentElement) {
      if (n.classList && n.classList.contains('dark')) chain.push(n.tagName + '.' + n.className.slice(0, 40));
      n = n.parentElement;
    }
    return chain;
  });
  console.log(JSON.stringify({ clsRoot, lbl, darkAnc }, null, 1));
  await b.close();
})();
