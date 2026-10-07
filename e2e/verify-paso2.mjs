// Verificación visual del Paso 2 — componentes Vue migrados a Zellia:
// PrimaryButton (CTA Principal 48px), SecondaryButton, TextInput (radius sm,
// ring 4px). Mide el botón, el radio, la letra del botón y la altura.
import { chromium } from 'playwright-core';

const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });
await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });

const btn = page.locator('form button').first();
await btn.waitFor({ state: 'visible', timeout: 10000 });
const m = await btn.evaluate(el => {
  const s = getComputedStyle(el);
  const r = el.getBoundingClientRect();
  return {
    height: r.height,
    width: r.width,
    radius: s.borderRadius,
    fontSize: s.fontSize,
    lineHeight: s.lineHeight,
    fontWeight: s.fontWeight,
    textTransform: s.textTransform,
    letterSpacing: s.letterSpacing,
    bg: s.backgroundColor,
    cls: el.className,
  };
});
console.log('=== PrimaryButton (debe ser CTA Principal Zellia) ===');
console.log('clase       :', m.cls);
console.log('bg          :', m.bg, m.bg === 'rgb(35, 82, 127)' ? '✓ primary.700 (fondo CTA Principal del spec)' : '✗');
console.log('altura      :', Math.round(m.height) + 'px', Math.round(m.height) === 48 ? '✓ 48px CTA' : '(≠48)');
console.log('radius      :', m.radius, parseFloat(m.radius) === 8 ? '✓ radius.md 8' : '✗');
console.log('fuente      :', m.fontSize, '/', m.lineHeight, m.fontSize === '16px' ? '✓ 16px' : '(≠16px)');
console.log('peso        :', m.fontWeight, m.fontWeight === '500' ? '✓ Medium 500' : '(' + m.fontWeight + ')');
console.log('case        :', m.textTransform, m.textTransform === 'none' ? '✓ ORIGINAL (sin uppercase)' : '✗ still ' + m.textTransform);
console.log('letterspacing:', m.letterSpacing, m.letterSpacing === 'normal' ? '✓ 0%' : '✗ ' + m.letterSpacing);

// TextInput
const inp = page.locator('#email');
await inp.waitFor({ state: 'visible', timeout: 5000 });
const im = await inp.evaluate(el => {
  const s = getComputedStyle(el);
  return { radius: s.borderRadius, border: s.borderColor, padding: s.padding, fs: s.fontSize, bg: s.backgroundColor };
});
console.log('=== TextInput (spec: radius sm 4, borde neutral.300, padded 12) ===');
console.log('radius      :', im.radius, parseFloat(im.radius) === 4 ? '✓ 4px' : '✗');
// El #email está autofocado → borde navy = ESTADO FOCUS correcto (ring + border-color brand)
console.log('borde       :', im.border, im.border === 'rgb(2, 53, 98)' ? '✓ focus con primary.800 (autofocado)' : '✗ ' + im.border);
console.log('fondo       :', im.bg, im.bg === 'rgb(255, 255, 255)' ? '✓ blanco' : '✗');
console.log('fuente      :', im.fs, im.fs === '16px' ? '✓ body1' : '✗');

await page.screenshot({ path: 'docs/design-system/zellia/verify-paso2-componentes.png' });
await browser.close();
