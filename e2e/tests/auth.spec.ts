import { test, expect } from '@playwright/test';

test.describe('Authentication', () => {
    // Use a fresh context for auth tests (no stored state)
    test.use({ storageState: { cookies: [], origins: [] } });

    test('should display login page', async ({ page }) => {
        await page.goto('/login');

        await expect(page.locator('h1, h2').filter({ hasText: /log\s?in|sign in/i })).toBeVisible();
        await expect(page.locator('input#email, input[name="email"]')).toBeVisible();
        await expect(page.locator('input#password, input[name="password"]')).toBeVisible();
        await expect(page.locator('button[type="submit"]')).toBeVisible();
    });

    test('should show validation errors for empty form', async ({ page }) => {
        await page.goto('/login');

        // La validación vive en los inputs HTML5 `required` (Zellia): el navegador
        // bloquea el submit y no hay texto de error que localizar.
        await expect(page.locator('input#email[required]')).toBeVisible();
        await expect(page.locator('input#password[required]')).toBeVisible();
    });

    test('should show error for invalid credentials', async ({ page }) => {
        await page.goto('/login');

        await page.fill('input#email', 'invalid@example.com');
        await page.fill('input#password', 'wrongpassword');
        await page.click('button[type="submit"]');

        // Should show authentication error
        await expect(page.locator('text=/credentials|invalid|incorrect/i')).toBeVisible({ timeout: 15000 });
    });

    test('should redirect unauthenticated users to login', async ({ page }) => {
        await page.goto('/dashboard');

        // Should redirect to login
        await expect(page).toHaveURL(/.*login.*/);
    });

    test('self-registration is disabled by design (SaaS admin-created users)', async ({ page }) => {
        // La app DESACTIVA el registro público: los usuarios los crea el Admin de la
        // organización. /register responde 404 a propósito (config auth.registration_enabled=false).
        const res = await page.goto('/register');
        expect(res?.status()).toBe(404);
    });
});
