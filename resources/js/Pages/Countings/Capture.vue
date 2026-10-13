<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { ScanLine, Save } from 'lucide-vue-next';
import Badge from '@/Components/ui/Badge.vue';
import { enqueue, sync, pendingCount, loadQueue } from './capture-offline';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';

/*
 * Captura en campo (capturador): UN solo input que acepta pistola de codigo
 * de barras (Enter sola), tipeo manual o camara. Re-escanear el mismo
 * producto REEMPLAZA la cantidad. Sin datos de la otra ronda (a ciegas).
 */
const props = defineProps({
    round: Object,
    items: Array,
});

const code = ref('');
const units = ref(0);
const boxes = ref(0);
const pendingProduct = ref(null);
const itemList = ref([...(props.items || [])]);
const flash = ref('');
const products = ref([]);
const scanInput = ref(null);

onMounted(async () => {
    // PWA: registrar el service worker (shell offline)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js').catch(() => {});
    }
    // Sincronizar cola local al montar y al reconectar la red
    const doSync = async () => {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const n = await sync(props.round.id, csrf);
        if (n > 0) {
            flash.value = `Sincronización completa: ${n} captura(s) subidas.`;
            // Refresca la lista desde el servidor
            try {
                const rr = await fetch(route('countings.rounds.show', props.round.id), {
                    headers: { Accept: 'application/json', credentials: 'same-origin' },
                });
                if (rr.ok) {
                    const dd = await rr.json();
                    itemList.value = dd.items || [];
                }
            } catch { /* sigue con la lista local */ }
        }
    };
    await doSync();
    window.addEventListener('online', doSync);
    try {
        const res = await fetch(route('countings.products', { counting: props.round.counting_id }), {
            headers: { Accept: 'application/json' },
        });
        const data = await res.json();
        const payload = data.products;
products.value = Array.isArray(payload) ? payload : (payload?.data || []);
    } catch {
        // offline: solo lo ya cargado
    }
});

const findByCode = (value) => {
    const q = value.trim().toUpperCase();
    return products.value.find((p) => (p.sku || '').toUpperCase() === q)
        || products.value.find((p) => (p.barcode || '').toUpperCase() === q)  // la pistola dispara el EAN
        || products.value.find((p) => (p.name || '').toUpperCase() === q)
        || null;
};

const onScan = () => {
    const found = findByCode(code.value);
    if (!found) {
        flash.value = `Producto no encontrado: ${code.value}`;
        code.value = '';
        return;
    }
    pendingProduct.value = found;
    flash.value = `Escaneado: ${found.name}. Digita cantidades y GUARDA.`;
    code.value = '';
};

const onScanKey = (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        onScan();
    }
};

const capture = async () => {
    if (!pendingProduct.value) return;
    try {
        const response = await fetch(route('countings.rounds.capture', props.round.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({
                product_id: pendingProduct.value.id,
                units: Number(units.value) || 0,
                boxes: Number(boxes.value) || 0,
            }),
        });
        const data = await response.json();
        if (response.ok) {
            const idx = itemList.value.findIndex((i) => i.product_id === data.item.product_id);
            if (idx >= 0) {
                itemList.value[idx] = data.item; // reemplazo, nunca duplica
            } else {
                itemList.value.push(data.item);
            }
            flash.value = data.replaced
                ? `Cantidad ACTUALIZADA para ${data.item.product.name}.`
                : `Capturado ${data.item.product.name}.`;
            pendingProduct.value = null;
            units.value = 0;
            boxes.value = 0;
            scanInput.value?.focus();
        } else {
            flash.value = data.error || 'Error al capturar.';
        }
    } catch {
        const pending = enqueue(props.round.id, {
            product_id: pendingProduct.value.id,
            units: Number(units.value) || 0,
            boxes: Number(boxes.value) || 0,
        });
        const local = itemList.value.findIndex((i) => i.product_id === pendingProduct.value.id);
        if (local < 0) {
            itemList.value.push({
                product_id: pendingProduct.value.id,
                product: pendingProduct.value,
                units: Number(units.value) || 0,
                boxes: Number(boxes.value) || 0,
                pending: true,
            });
        } else {
            itemList.value[local].units = Number(units.value) || 0;
            itemList.value[local].boxes = Number(boxes.value) || 0;
            itemList.value[local].pending = true;
        }
        flash.value = `Sin conexion - guardado local (${pending} pendientes). Se sincroniza al reconectar.`;
        pendingProduct.value = null;
        units.value = 0;
        boxes.value = 0;
        scanInput.value?.focus();
    }
};
</script>

<template>
    <Head :title="`Ronda ${round.round_type.toUpperCase()} - captura`" />
    <div class="min-h-screen bg-surface-canvas">
        <div class="mx-auto max-w-xl p-6">
            <div class="mb-4 flex items-center gap-2">
                <Badge variant="brand">Ronda {{ round.round_type.toUpperCase() }}</Badge>
                <Badge :variant="round.status === 'open' ? 'info' : round.status === 'closed' ? 'success' : 'neutral'">
                    {{ round.status }}
                </Badge>
            </div>

            <h1 class="mb-1 text-lg font-semibold text-text-primary">Captura con un solo input</h1>
            <p class="mb-6 text-sm text-text-secondary">Escanea, digita cantidades, guarda. Volver a escanear reemplaza.</p>

            <Card class="mb-4">
                <div class="p-4">
                    <div class="flex items-center gap-2">
                        <ScanLine :size="18" class="text-text-tertiary" />
                        <input
                            ref="scanInput"
                            v-model="code"
                            type="text"
                            class="h-12 flex-1 rounded-md border border-border-subtle bg-surface-canvas px-3 text-base text-text-primary ds-focus-ring"
                            placeholder="Escanea o escribe el codigo..."
                            @keydown="onScanKey"
                        />
                    </div>
                    <p v-if="flash" class="mt-2 text-xs text-text-secondary">{{ flash }}</p>
                </div>
            </Card>

            <Card v-if="pendingProduct" class="mb-4">
                <div class="p-4">
                    <p class="mb-3 text-sm font-medium text-text-primary">{{ pendingProduct.name }}</p>
                    <div class="flex items-end gap-3">
                        <div>
                            <label class="text-xs text-text-secondary">Unidades</label>
                            <input v-model.number="units" type="number" min="0" class="h-10 w-24 rounded-md border border-border-subtle px-3 text-sm text-text-primary ds-focus-ring" />
                        </div>
                        <div>
                            <label class="text-xs text-text-secondary">Cajas</label>
                            <input v-model.number="boxes" type="number" min="0" class="h-10 w-24 rounded-md border border-border-subtle px-3 text-sm text-text-primary ds-focus-ring" />
                        </div>
                        <Button variant="default" @click="capture">
                            <Save :size="14" />
                            Guardar
                        </Button>
                    </div>
                </div>
            </Card>

            <Card>
                <div class="p-4">
                    <h3 class="mb-3 text-sm font-medium text-text-primary">Ya contado en esta ronda ({{ itemList.length }})</h3>
                    <p v-if="itemList.length === 0" class="text-sm text-text-secondary">Nada aun. Escanea el primer producto.</p>
                    <ul v-else class="divide-y divide-border-subtle">
                        <li v-for="item in itemList" :key="item.product_id + (item.pending ? '-p' : '-s')" class="flex items-center justify-between py-2">
                            <span class="text-sm text-text-primary">{{ item.product.name }}</span>
                            <span class="text-sm font-medium text-text-primary">
                                {{ item.units }} u. / {{ item.boxes }} c.
                                <span v-if="item.pending" class="text-xs text-text-tertiary">local</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </Card>
        </div>
    </div>
</template>
