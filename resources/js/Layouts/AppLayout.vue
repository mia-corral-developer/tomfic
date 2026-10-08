<script setup>
/**
 * AppLayout — Linear-inspired authenticated layout.
 *
 * The single authenticated layout for the app. To use, `import AppLayout`
 * and wrap your page content in it; the default slot is the page body.
 *
 * Design notes:
 *   - Light, single-surface sidebar (matches the workspace, not a dark slab)
 *   - Lucide icons (no inline SVG paths)
 *   - 240px fixed width — no collapse rail; mobile drawer instead
 *   - Section labels (UPPERCASE, tracking-wider) group related nav items
 *   - Workspace badge at top, user pill at bottom
 *   - Top strip is a thin 44px row, breadcrumb + actions
 *   - Density: 8px nav rows, 13px font, hover = subtle surface change
 */
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import {
    Boxes,
    LayoutGrid,
    ShoppingCart,
    Undo2,
    ClipboardList,
    Truck,
    Tag,
    MapPin,
    Warehouse,
    ArrowLeftRight,
    ScanLine,
    Hammer,
    FileSpreadsheet,
    BarChart3,
    Users,
    ShieldCheck,
    Puzzle,
    Settings2,
    Bell,
    Search,
    Menu,
    X,
    ChevronUp,
} from 'lucide-vue-next';

import { usePermissions } from '@/composables/usePermissions';
import FlashMessages from '@/Components/Layout/FlashMessages.vue';
import GlobalSearch from '@/Components/Layout/GlobalSearch.vue';
import NotificationDropdown from '@/Components/Layout/NotificationDropdown.vue';
import ThemeToggle from '@/Components/Layout/ThemeToggle.vue';
import WarehouseSwitcher from '@/Components/WarehouseSwitcher.vue';

const { t } = useI18n();
const page = usePage();
const { hasPermission } = usePermissions();

const mobileOpen = ref(false);
const globalSearchRef = ref(null);

// Close mobile drawer on navigation
onMounted(() => {
    router.on('start', () => {
        mobileOpen.value = false;
    });
});

/**
 * Lock the page behind the mobile drawer.
 *
 * The drawer is a fixed overlay, so without this the body keeps scrolling
 * under it: a swipe meant for the nav list scrolls the page instead, and
 * closing the drawer leaves you somewhere you never meant to be. Scoped to
 * the drawer being open, and restored on unmount so a page transition
 * mid-animation cannot strand the lock.
 */
watch(mobileOpen, (open) => {
    document.body.style.overflow = open ? 'hidden' : '';
});

onBeforeUnmount(() => {
    document.body.style.overflow = '';
});

// Cmd/Ctrl-K opens global search anywhere
const handleHotkey = (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        globalSearchRef.value?.open();
    }
};
onMounted(() => window.addEventListener('keydown', handleHotkey));

const user = computed(() => page.props.auth?.user);
const workspaceName = computed(() => page.props.auth?.organization?.name || 'Inventoros');

// Tema actual para el wordmark del sidebar (ThemeToggle guarda en localStorage)
const isDark = ref(false);
onMounted(() => {
    isDark.value = document.documentElement.classList.contains('dark');
    // re-sincroniza cuando ThemeToggle cambia el tema
    const obs = new MutationObserver(() => {
        isDark.value = document.documentElement.classList.contains('dark');
    });
    obs.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    onBeforeUnmount(() => obs.disconnect());
});

/**
 * Nav schema. Each section is { labelKey, items: [{ icon, nameKey, href, active, perm? }] }.
 * Render is data-driven so adding a section is one line. Labels are i18n keys
 * resolved with t() at render time, so the sidebar follows the active locale.
 */
const sections = computed(() => [
    {
        labelKey: 'nav.sections.workspace',
        items: [
            { icon: LayoutGrid, nameKey: 'nav.dashboard', href: route('dashboard'), active: ['dashboard'] },
            { icon: Boxes, nameKey: 'nav.inventory', href: route('products.index'), active: ['products.*'], perm: 'view_products' },
            { icon: ShoppingCart, nameKey: 'nav.orders', href: route('orders.index'), active: ['orders.*'], perm: 'view_orders' },
            { icon: Undo2, nameKey: 'nav.returns', href: route('returns.index'), active: ['returns.*'], perm: 'manage_returns' },
            { icon: ClipboardList, nameKey: 'nav.purchaseOrders', href: route('purchase-orders.index'), active: ['purchase-orders.*'], perm: 'view_purchase_orders' },
            { icon: Truck, nameKey: 'nav.suppliers', href: route('suppliers.index'), active: ['suppliers.*'], perm: 'view_suppliers' },
        ],
    },
    {
        labelKey: 'nav.sections.catalog',
        items: [
            { icon: Tag, nameKey: 'nav.categories', href: route('categories.index'), active: ['categories.*'], perm: 'manage_categories' },
            { icon: MapPin, nameKey: 'nav.locations', href: route('locations.index'), active: ['locations.*'], perm: 'manage_locations' },
            { icon: Warehouse, nameKey: 'nav.warehouses', href: route('warehouses.index'), active: ['warehouses.*'], perm: 'manage_warehouses' },
        ],
    },
    {
        labelKey: 'nav.sections.stock',
        items: [
            { icon: ArrowLeftRight, nameKey: 'nav.stockTransfers', href: route('stock-transfers.index'), active: ['stock-transfers.*'], perm: 'view_stock_transfers' },
            { icon: ScanLine, nameKey: 'nav.stockAudits', href: route('stock-audits.index'), active: ['stock-audits.*'], perm: 'view_stock_audits' },
            { icon: Hammer, nameKey: 'nav.workOrders', href: route('work-orders.index'), active: ['work-orders.*'], perm: 'manage_stock' },
        ],
    },
    {
        labelKey: 'nav.sections.insights',
        items: [
            { icon: FileSpreadsheet, nameKey: 'nav.importExport', href: route('import-export.index'), active: ['import-export.*'], perm: 'manage_import_export' },
            { icon: BarChart3, nameKey: 'nav.reports', href: route('reports.index'), active: ['reports.*'], perm: 'view_reports' },
        ],
    },
    {
        labelKey: 'nav.sections.admin',
        items: [
            { icon: Users, nameKey: 'nav.users', href: route('users.index'), active: ['users.*'], perm: 'manage_users' },
            { icon: ShieldCheck, nameKey: 'nav.roles', href: route('roles.index'), active: ['roles.*'], perm: 'manage_roles' },
            { icon: Puzzle, nameKey: 'nav.plugins', href: route('plugins.index'), active: ['plugins.*'], perm: 'manage_plugins' },
            { icon: Settings2, nameKey: 'nav.settings', href: route('settings.account.index'), active: ['settings.*', 'webhooks.*', 'account.*'] },
        ],
    },
]);

const visibleSections = computed(() =>
    sections.value
        .map((s) => ({
            ...s,
            items: s.items.filter((i) => !i.perm || hasPermission(i.perm)),
        }))
        .filter((s) => s.items.length > 0)
);

const isActive = (item) => item.active.some((pattern) => route().current(pattern));
</script>

<template>
    <div class="min-h-screen bg-surface-canvas text-text-primary">
        <!-- Mobile top bar -->
        <div class="md:hidden fixed top-0 inset-x-0 z-40 h-12 flex items-center justify-between px-4 bg-surface-base border-b border-border-subtle">
            <Link :href="route('dashboard')" class="flex items-center gap-2">
                <img src="/images/brand/inventoros_icon_transparent_512.png" alt="Inventoros" class="h-7 w-7 shrink-0" />
                <span class="text-sm font-semibold tracking-tight">{{ workspaceName }}</span>
            </Link>
            <button
                @click="mobileOpen = !mobileOpen"
                class="p-1.5 rounded-md text-text-secondary hover:bg-surface-overlay ds-focus-ring"
                aria-label="Toggle navigation"
            >
                <Menu v-if="!mobileOpen" :size="18" />
                <X v-else :size="18" />
            </button>
        </div>

        <!-- Sidebar — SiderZellia (320px, spec §2) -->
        <aside
            :class="[
                'zellia-sidebar',
                mobileOpen && 'zellia-sidebar--open',
            ]"
            aria-label="Principal"
        >
            <!-- Formas decorativas de fondo (§2.8): recortadas por overflow:hidden -->
            <div class="zellia-sidebar__shape zellia-sidebar__shape--1" aria-hidden="true" />
            <div class="zellia-sidebar__shape zellia-sidebar__shape--2" aria-hidden="true" />

            <!-- Header: marca Zellia (§2.4) — wordmark claro/oscuro según tema -->
            <div class="zellia-sidebar__header">
                <Link :href="route('dashboard')" class="zellia-sidebar__brand">
                    <img
                        :src="'/images/brand/zellia/zellia-wordmark-' + (isDark ? 'dark' : 'light') + '.png'"
                        alt="Zellia"
                        class="zellia-sidebar__logo"
                    />
                </Link>
            </div>

            <!-- Buscador (§2.5) -->
            <div class="zellia-sidebar__search-row">
                <label class="zellia-sidebar__search">
                    <Search :size="20" aria-hidden="true" />
                    <input
                        type="search"
                        :placeholder="t('nav.search', 'Buscar...')"
                        @click="globalSearchRef?.open()"
                        @focus="globalSearchRef?.open()"
                        readonly
                        aria-label="Buscar"
                    />
                </label>
            </div>

            <!-- Nav principal (§2.6) -->
            <div class="zellia-sidebar__main">
                <div class="zellia-sidebar__divider" aria-hidden="true" />
                <nav class="zellia-sidebar__nav ds-scroll">
                    <div v-for="section in visibleSections" :key="section.labelKey" class="zellia-sidebar__group">
                        <p class="zellia-sidebar__group-title">{{ t(section.labelKey) }}</p>
                        <Link
                            v-for="item in section.items"
                            :key="item.nameKey"
                            :href="item.href"
                            class="zellia-sidebar-item"
                            :aria-current="isActive(item) ? 'page' : undefined"
                        >
                            <component
                                :is="item.icon"
                                :size="24"
                                class="zellia-sidebar-item__icon"
                                aria-hidden="true"
                            />
                            <span class="truncate">{{ t(item.nameKey) }}</span>
                        </Link>
                    </div>
                </nav>
                <div class="zellia-sidebar__divider" aria-hidden="true" />
            </div>

            <!-- CTA inferior: user switcher (§2.7) -->
            <Link
                :href="route('settings.account.index')"
                data-testid="user-menu"
                class="zellia-sidebar__cta"
            >
                <span
                    class="zellia-sidebar-item__icon grid place-items-center rounded-full text-caption-1 font-semibold shrink-0"
                    style="background: #0F489D; color: var(--zellia-color-neutral-100); width: 24px; height: 24px;"
                >
                    {{ (user?.name || '?').charAt(0).toUpperCase() }}
                </span>
                <span class="zellia-sidebar__cta-text truncate">{{ user?.name }}</span>
                <ChevronUp :size="24" class="zellia-sidebar-item__icon" aria-hidden="true" />
            </Link>
        </aside>

        <!-- Mobile backdrop -->
        <div
            v-show="mobileOpen"
            @click="mobileOpen = false"
            class="md:hidden fixed inset-0 z-40 bg-black/40 mt-12"
            aria-hidden="true"
        />

        <!-- Main column — desplazado por el SiderZellia de 320px -->
        <div class="md:pl-80 pt-12 md:pt-0">
            <!-- Header Zellia: 66px (65 + borde), forma decorativa en sup-der -->
            <div class="zellia-header sticky top-0 z-30">
                <div class="zellia-header__shape" aria-hidden="true" />
                <div class="zellia-header__row">
                    <div class="flex-1 min-w-0">
                        <slot name="header">
                            <span class="zellia-sidebar__tagline">{{ workspaceName }}</span>
                        </slot>
                    </div>
                    <div class="zellia-header__items">
                        <WarehouseSwitcher />
                        <ThemeToggle />
                        <NotificationDropdown />
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="px-4 md:px-6 py-6 md:py-8">
                <slot />
            </main>
        </div>

        <GlobalSearch ref="globalSearchRef" />
        <FlashMessages />
    </div>
</template>
