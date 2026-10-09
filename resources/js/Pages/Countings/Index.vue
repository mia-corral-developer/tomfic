<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import Badge from '@/Components/ui/Badge.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus, ClipboardList } from 'lucide-vue-next';

defineProps({
    countings: Object,
});

const statusVariant = (status) =>
    ({ draft: 'neutral', in_progress: 'info', closed: 'success' }[status] || 'neutral');
</script>

<template>
    <Head title="Tomas de inventario" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-2 text-xs">
                <span class="text-text-tertiary">Workspace</span>
                <span class="text-text-tertiary">/</span>
                <span class="font-medium text-text-primary">Tomas</span>
            </div>
        </template>

        <PageHeader title="Tomas de inventario" description="Doble conteo con rondas C1/C2 y árbol de desempate.">
            <template #actions>
                <Button variant="default" size="sm" as="Link" :href="route('countings.create')">
                    <Plus :size="14" />
                    Nueva toma
                </Button>
            </template>
        </PageHeader>

        <Card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border-subtle">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-medium tracking-tight text-text-secondary">Toma</th>
                            <th class="px-4 py-2.5 text-left text-xs font-medium tracking-tight text-text-secondary">Estado</th>
                            <th class="px-4 py-2.5 text-left text-xs font-medium tracking-tight text-text-secondary">Rondas</th>
                            <th class="px-4 py-2.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        <tr v-if="countings.data.length === 0">
                            <td colspan="4" class="px-4 py-12 text-center text-sm text-text-secondary">
                                <ClipboardList :size="32" class="mx-auto mb-3 text-text-tertiary" />
                                No hay tomas todavía. Crea la primera para programar el doble conteo.
                            </td>
                        </tr>
                        <tr v-for="counting in countings.data" :key="counting.id" class="hover:bg-surface-subtle">
                            <td class="px-4 py-3 text-sm text-text-primary">{{ counting.name }}</td>
                            <td class="px-4 py-3"><Badge :variant="statusVariant(counting.status)">{{ counting.status }}</Badge></td>
                            <td class="px-4 py-3 text-sm text-text-secondary">{{ counting.rounds_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <Button variant="outline" size="sm" as="Link" :href="route('countings.show', counting.id)">Ver</Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
