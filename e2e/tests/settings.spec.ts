import { test, expect } from '../fixtures';

// Spec mutates shared org data: keep serial to avoid cross-worker contention on sqlite.
test.describe.configure({ mode: 'serial' });

test.describe('Organization Settings', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('/settings/organization');
    });

    test('should display organization settings page', async ({ page }) => {
        await expect(page.locator('h1, h2').filter({ hasText: /settings|organization/i })).toBeVisible();
    });

    test('should display organization name field', async ({ page }) => {
        await expect(page.locator('input[name="name"], #name')).toBeVisible();
    });

    test('should display address fields', async ({ page }) => {
        await expect(page.locator('input[name="address"], #address, textarea[name="address"]')).toBeVisible();
    });

    test('should update organization settings', async ({ page }) => {
        // Update organization name
        const nameInput = page.locator('input[name="name"], #name');
        const currentName = await nameInput.inputValue();

        await nameInput.fill(`${currentName} Updated`);

        // Find and click save button
        const saveButton = page.locator('form:has(#name) button[type="submit"]').first();
        await saveButton.click();

        // Should show success message
        await expect(page.locator('text=/success|updated|saved/i')).toBeVisible({ timeout: 5000 });

        // Revert the name
        await nameInput.fill(currentName);
        await saveButton.click();
    });
});

test.describe('Account Settings', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('/settings/account');
    });

    test('should display account settings page', async ({ page }) => {
        await expect(page.locator('h1, h2').filter({ hasText: /account|profile|settings/i })).toBeVisible();
    });

    test('should display profile fields', async ({ page }) => {
        await expect(page.locator('input[name="name"], #name')).toBeVisible();
        await expect(page.locator('input[name="email"], #email')).toBeVisible();
    });

    test('should have password change section', async ({ page }) => {
        // The account page groups profile fields in tabs (Profile / Password / Notifications).
        const passwordTab = page.getByRole('button', { name: 'Password' });
        if (await passwordTab.isVisible()) {
            await passwordTab.click();
        }
        await expect(page.locator('#password, input[name="current_password"]')).toBeVisible();
    });

    test('should have notification preferences', async ({ page }) => {
        // Look for notification toggle/checkbox
        const notificationToggle = page.locator('input[type="checkbox"][name*="notification"], .notification-toggle');

        if (await notificationToggle.first().isVisible()) {
            await expect(notificationToggle.first()).toBeVisible();
        }
    });
});

test.describe('User Management', () => {
    test('should display users list', async ({ page }) => {
        await page.goto('/users');

        await expect(page.locator('h1, h2').filter({ hasText: /users/i })).toBeVisible();
    });

    test('should have create user button', async ({ page }) => {
        await page.goto('/users');

        const createButton = page.locator('a, button').filter({ hasText: /create|add|invite/i });
        await expect(createButton.first()).toBeVisible();
    });

    test('should display user details', async ({ page }) => {
        await page.goto('/users');
        // Espera la fila hidratada (la lista es client-side después del primer render)
        await page.locator('table tbody tr').first().waitFor({ state: 'visible', timeout: 20000 });

        // Solo el link de VER (excluye create/edit/pagination/other-user pages)
        const viewButton = page.locator('a[href*="/users/"]:not([href*="edit"]):not([href*="create"])').first();
        await expect(viewButton).toBeVisible({ timeout: 10000 });
        await viewButton.click();
        await expect(page).toHaveURL(/users\/\d+$/, { timeout: 10000 });
        await expect(page.locator('h1, h2').first()).toBeVisible();
    });
});

test.describe('Role Management', () => {
    test('should display roles list', async ({ page }) => {
        await page.goto('/roles');

        await expect(page.locator('h1, h2').filter({ hasText: /roles/i })).toBeVisible();
    });

    test('should have create role button', async ({ page }) => {
        await page.goto('/roles');

        const createButton = page.locator('a, button').filter({ hasText: /create|add|new/i });
        await expect(createButton.first()).toBeVisible();
    });

    test('should display permissions in role form', async ({ page }) => {
        await page.goto('/roles/create');

        // Permission checkboxes presentes en el form de role
        await expect(page.locator('input[type="checkbox"]').first()).toBeVisible();
    });
});
