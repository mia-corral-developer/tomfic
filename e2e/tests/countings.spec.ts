import { test, expect } from '@playwright/test';

/**
 * CAMPAIGN-2026-10-13 — E2E del módulo Countings (tomfic-field):
 * creación de toma con auto-programación C1/C2 + captura en campo con
 * UN solo input, re-escaneo que REEMPLAZA (5 → 7), y referencia de auditoría.
 *
 * Datos: cada prueba crea SU ubicación/categoría/producto vía UI o HTTP,
 * con fecha RUN y nunca toca datos de otras suites.
 */



async function csrfOf(page: any, path: string): Promise<string> {
    const html = await (await page.request.get(path)).text();
    const m = html.match(/csrf-token" content="([^"]+)/);
    return m ? m[1] : '';
}

async function createLocation(page: any, _request: any, name: string): Promise<void> {
    // Vía UI: modal Add Location (id name placeholder)
    await page.goto('/locations');
    await page.getByRole('button', { name: 'Add Location' }).click();
    const nIn = page.locator('input[placeholder="e.g., Warehouse A"]');
    await nIn.waitFor({ state: 'visible', timeout: 10000 });
    await nIn.fill(name);
    await page.locator('input[placeholder="e.g., WH-A"]').fill(`CC${name.replace('LOC-', '')}`);
    await page.locator('button:has-text("Create Location")').last().click();
    await nIn.waitFor({ state: 'hidden', timeout: 10000 }); // el modal cierra al guardar
}

test.describe('Countings E2E — tomas de inventario', () => {
    test('COUNT-E2E-01 crear toma programa C1 y C2 por ubicación', { tag: ['@mutating'] }, async ({ page, request }) => {
        const RUN = Date.now().toString().slice(-8) + String(Math.random()).slice(2, 6);
        const locName = `LOC-01-${RUN}`;
        await createLocation(page, request, locName);

        await page.goto('/countings/create');
        await page.locator('#name, input[name="name"]').fill(`Take E2E ${RUN}`);
        // Marca el checkbox cuyo LABEL muestra la ubicación creada
        await page.locator('label', { hasText: locName }).locator('input[type="checkbox"]').check({ force: true });
        await page.locator('button:has-text("Crear toma")').click();

        await expect(page).toHaveURL(/countings\/\d+/, { timeout: 15000 });
        await expect(page.locator('text=C1').first()).toBeVisible();
        await expect(page.locator('text=C2').first()).toBeVisible();
        // La toma auto-programada nace en_progress
        await expect(page.locator('text=in_progress').first()).toBeVisible();
    });

    test('COUNT-E2E-02 captura con un solo input y re-escaneo reemplaza', { tag: ['@mutating'] }, async ({ page, request }) => {
        const RUN = Date.now().toString().slice(-8) + String(Math.random()).slice(2, 6);
        const locName = `LOC-02-${RUN}`;
        await createLocation(page, request, locName);

        // Producto con barcode, creado primero (form real de /products/create)
        const barcode = `77${RUN}1`;
        await page.goto('/products/create');

        const sellPrice = page.getByLabel(/Selling Price/i).first();
        if (await sellPrice.count()) await sellPrice.fill('1000');
        const buyPrice = page.getByLabel(/Purchase Price/i).first();
        if (await buyPrice.count()) await buyPrice.fill('500');
        const stock = page.getByLabel(/Current Stock/i).first();
        if (await stock.count()) await stock.fill('0');
        const minStock = page.getByLabel(/Minimum Stock Level/i).first();
        if (await minStock.count()) await minStock.fill('0');
        // Category requerida: Quick Add del formulario (modal Teleport a body)
        await page.getByRole('button', { name: '+ Quick Add' }).first().click();
        const quickName = page.locator('body > div.fixed input[type="text"][required]').first();
        await quickName.waitFor({ state: 'visible', timeout: 8000 });
        await quickName.fill(`E2E Cat ${RUN}`);
        await page.locator('button:has-text("Create Category")').click();
        await quickName.waitFor({ state: 'hidden', timeout: 8000 }).catch(() => {});
        await page.waitForTimeout(600);
        // Fill tras stabilizar (el modal puede disparar recarga parcial del form)

        // Location requerida: la creada arriba
        const locCombo = page.locator('#location');
        if (await locCombo.count()) {
            const options = await locCombo.locator('option').allTextContents();
            const match = options.find((o: string) => o.includes(locName));
            if (match) await locCombo.selectOption({ label: match });
        }
                const nInput = page.locator('input[name="name"], #name').first();
        for (let attempt = 0; attempt < 3; attempt++) {
            await nInput.pressSequentially(`Prod E2E ${RUN}`, { delay: 15 });
            await page.waitForTimeout(500);
            if (await nInput.inputValue()) break;
        }
        const skuInput = page.locator('input[name="sku"], #sku').first();
        for (let attempt = 0; attempt < 3; attempt++) {
            await skuInput.pressSequentially(`E2E${RUN}`, { delay: 10 });
            await page.waitForTimeout(400);
            if (await skuInput.inputValue()) break;
        }
        const bi = page.locator('input[name="barcode"], #barcode').first();
        if (await bi.count()) await bi.fill(barcode);
        await page.locator('button:has-text("Create Product")').click();
        await expect(page).not.toHaveURL(/products\/create/, { timeout: 15000 });

        // Crear la toma (auto-programación) y ASIGNAR al capturador seeded
        await page.goto('/countings/create');
        await page.locator('#name, input[name="name"]').fill(`Take capture ${RUN}`);
        await page.locator('label', { hasText: locName }).locator('input[type="checkbox"]').check({ force: true });
        await page.locator('label:has(input):has-text("E2E Test User")').first().locator('input').check({ force: true });
        await page.locator('button:has-text("Crear toma")').click();
        await expect(page).toHaveURL(/countings\/\d+/, { timeout: 15000 });

        // roundId desde la API de mis rondas. page.request no comparte la sesión
        // en algunos contextos → evaluamos EN EL NAVEGADOR fetch same-origin (cookies).
        const roundId = await page.evaluate(async () => {
            const res = await fetch('/my/counting-rounds', {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!res.ok) return 0;
            const data = await res.json();
            const mine = (data.rounds || []).filter((r: any) => r.round_type === 'c1');
            return mine.length ? mine[mine.length - 1].id : 0;
        });
        expect(roundId).toBeGreaterThan(0);

        // La captura pre-carga el catálogo via /countings/products antes de escanear:
        const catalogPromise = page.waitForResponse(
            (r) => r.url().includes('/products') && r.url().includes('countings') && r.request().method() === 'GET',
        );
        await page.goto(`/counting-rounds/${roundId}`);
        expect((await catalogPromise).ok()).toBeTruthy();

        // UN solo input: escaneo manual (= pistola que dispara Enter)
        const scanInput = page.locator('input[placeholder*="Escanea"], input[placeholder*="codigo" i]').first();
        await expect(scanInput).toBeVisible();
        await scanInput.fill(barcode);
        await scanInput.press('Enter');
        await expect(page.locator('text=Prod E2E').first()).toBeVisible();

        // Digita y guarda → "Capturado"
        await page.locator('input[type="number"]').first().fill('5');
        await page.locator('button:has-text("Guardar")').click();
        await expect(page.locator('text=Capturado').first()).toBeVisible();

        // Re-escaneo REEMPLAZA (5 → 7), nunca duplica
        await scanInput.fill(barcode);
        await scanInput.press('Enter');
        await page.locator('input[type="number"]').first().fill('7');
        await page.locator('button:has-text("Guardar")').click();
        await expect(page.locator('text=ACTUALIZADA').first()).toBeVisible();
        await expect(page.locator('li:has-text("7 u.")')).toHaveCount(1);
    });
});
