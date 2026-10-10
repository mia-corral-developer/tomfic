<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import Badge from '@/Components/ui/Badge.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Download, CheckCircle2, TriangleAlert } from 'lucide-vue-next';

const props = defineProps({
    counting: Object,
    resolved: Array,
    discrepancies: Array,
    canClose: Boolean,
});

const roundVariant = (status) =>
    ({ pending: 'neutral', open: 'info', closed: 'success' }[status] || 'neutral');
const typeVariant = (type) =>
    ({ c1: 'brand', c2: 'info', c3: 'warning' }[type] || 'neutral');

const openRound = (round) => router.post(route('countings.rounds.open', round.id));
const closeRound = (round) => router.post(route('countings.rounds.close', round.id));
const closeCounting = () => router.post(route('countings.close', props.counting.id));
</script>

<template>
    <Head :title="`Toma: ${counting.name}`" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-2 text-xs">
                <span class="text-text-tertiary">Workspace</span>
                <span class="text-text-tertiary">/</span>
                <span class="font-medium text-text-primary">{{ counting.name }}</span>
            </div>
        </template>

        <PageHeader :title="counting.name" :description="counting.description || 'Toma de inventario con doble conteo.'">
            <template #actions>
                <Badge :variant="counting.status === 'closed' ? 'success' : counting.status === 'in_progress' ? 'info' : 'neutral'">
                    {{ counting.status }}
                </Badge>
                <Button variant="default" size="sm" :disabled="!canClose || counting.status === 'closed'" @click="closeCounting">
                    <CheckCircle2 :size="14" />
                    Cerrar toma
                </Button>
            </template>
        </PageHeader>

        <div v-if="discrepancies.length > 0" class="mb-4 flex items-start gap-3 rounded-md border border-warning-subtle bg-warning-canvas p-4 text-sm">
            <TriangleAlert :size="18" class="mt-0.5 text-warning-base" />
            <div>
                <p class="font-medium text-text-primary">{{ discrepancies.length }} producto(s) con discrepancia C1≠C2.</p>
                <p class="text-text-secondary">Abre una ronda C3 (árbitro) en cada ubicación afectada para resolverlas — el valor final SIEMPRE la ronda de arbitraje.</p>
            </div>
        </div>

        <div class="space-y-4">
            <Card v-for="(rounds, locationId) in groupByLocation(counting.rounds)" :key="locationId">
                <div class="p-4">
                    <h3 class="mb-3 text-sm font-medium text-text-primary">{{ rounds[0].location?.name || `Ubicación #${locationId}` }}</h3>
                    <div class="space-y-2">
                        <div v-for="round in rounds" :key="round.id" class="flex items-center justify-between rounded-md border border-border-subtle px-3 py-2">
                            <div class="flex items-center gap-2">
                                <Badge :variant="typeVariant(round.round_type)">{{ round.round_type.toUpperCase() }}</Badge>
                                <Badge :variant="roundVariant(round.status)">{{ round.status }}</Badge>
                                <span class="text-xs text-text-secondary">
                                    {{ round.assignments.map(a => a.user?.name).filter(Boolean).join(', ') || 'sin capturador' }}
                                </span>
                                <span class="text-xs text-text-tertiary">{{ round.items.length }} item(es)</span>
                            </div>
                            <div v-if="counting.status !== 'closed'" class="flex gap-2">
                                <Button v-if="round.status === 'pending'" variant="outline" size="sm" @click="openRound(round)">Abrir</Button>
                                <Button v-if="round.status === 'open'" variant="default" size="sm" @click="closeRound(round)">Cerrar</Button>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>

            <Card>
                <div class="p-4">
                    <h3 class="mb-3 text-sm font-medium text-text-primary">Resultados resueltos</h3>
                    <p v-if="resolved.length === 0" class="text-sm text-text-secondary">
                        Aún no hay valores finales. Cierra las rondas C1 y C2 (coincidentes) o resuelve con C3.
                    </p>
                    <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border-subtle">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-text-secondary">Producto</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-text-secondary">C1</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-text-secondary">C2</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-text-secondary">C3</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-text-secondary">Final</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-text-secondary">Fuente</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle">
                            <tr v-for="(r, idx) in resolved" :key="idx">
                                <td class="px-3 py-2 text-sm text-text-primary">{{ productName(r.product_id) }}</td>
                                <td class="px-3 py-2 text-sm text-text-secondary">{{ r.c1_units ?? '—' }}</td>
                                <td class="px-3 py-2 text-sm text-text-secondary">{{ r.c2_units ?? '—' }}</td>
                                <td class="px-3 py-2 text-sm text-text-secondary">{{ r.c3_units ?? '—' }}</td>
                                <td class="px-3 py-2 text-sm font-medium text-text-primary">{{ r.final_units }}</td>
                                <td class="px-3 py-2">
                                    <Badge :variant="r.source === 'c3_arbitration' ? 'warning' : 'success'">
                                        {{ r.source === 'c3_arbitration' ? 'C3 mandó' : 'C1=C2' }}
                                    </Badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

<script>
export default {
    methods: {
        groupByLocation(rounds) {
            const groups = {};
            (rounds || []).forEach((round) => {
                (groups[round.product_location_id] ||= []).push(round);
            });
            return groups;
        },
        productName(productId) {
            const round = (this.counting.rounds || []).find((r) => r.items?.some?.((i) => i.product_id === productId))
                || (this.counting.rounds || []).find((r) => r.items?.length);
            const items = (round?.items || []);
            const found = items.find((i) => i.product_id === productId);
            return found?.product?.name || `Producto #${productId}`;
        },
    },
};
</script>
