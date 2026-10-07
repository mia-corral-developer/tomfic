// Paso 6a — confirma el hallazgo de bajo contraste con datos exactos:
// cuáles textos fallan 1.4.3 en dashboard light y dark, y su ratio.
import { chromium } from 'playwright-core';

const browser = await chromium.launch();
const audit = JSON.parse(require('fs')
  .readFileSync('ux-audit-paso6/audit.json', 'utf8'));

const dDashL = audit.find(c => c.route === '/dashboard' && c.variant === 'light' && c.width === 1440);
const dDashD = audit.find(c => c.route === '/dashboard' && c.variant === 'dark' && c.width === 1440);
console.log('light checks:', dDashL.failedChecks.filter(c => c.check === 'contrast').length, 'fallas de contraste');
console.log('dark  checks:', dDashD.failedChecks.filter(c => c.check === 'contrast').length, 'fallas de contraste');

for (const [label, cap] of [['light', dDashL], ['dark', dDashD]]) {
  const contrast = cap.failedChecks.filter(c => c.check === 'contrast');
  const worst = contrast.slice(0, 6);
  console.log(`\n== ${label} — peores 6 ==`);
  for (const c of contrast) {
    const item = c.items?.[0] || c.item || {};
    console.log('-', c.count || 1, 'texto(s) bajo AA | ratio', item.ratio || c.ratio, '|', (item.sel || c.sel || '').slice(0, 60));
  }
}
await browser.close();
