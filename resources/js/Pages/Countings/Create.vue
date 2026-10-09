<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    locations: Array,
    capturadores: Array,
});

const form = useForm({
    name: '',
    description: '',
    location_ids: [],
    capturador_ids: [],
});

const submit = () => {
    form.post(route('countings.store'));
};
</script>

<template>
    <Head title="Nueva toma" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-2 text-xs">
                <span class="text-text-tertiary">Workspace</span>
                <span class="text-text-tertiary">/</span>
                <Link :href="route('countings.index')" class="text-text-tertiary hover:text-text-primary">Tomas</Link>
                <span class="text-text-tertiary">/</span>
                <span class="font-medium text-text-primary">Nueva</span>
            </div>
        </template>

        <PageHeader title="Nueva toma de inventario" description="Programa el doble conteo: una ronda C1 y una C2 por ubicación, cada una con su capturador." />

        <Card class="max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6 p-6">
                <div>
                    <InputLabel for="name" value="Nombre de la toma" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="description" value="Descripción (opcional)" />
                    <textarea id="description" v-model="form.description" class="mt-1 block w-full rounded-md border border-border-subtle bg-surface-canvas px-3 py-2 text-sm text-text-primary ds-focus-ring" rows="2" />
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <div>
                    <InputLabel value="Ubicaciones a contar" />
                    <p class="mb-2 text-xs text-text-tertiary">Se programarán una ronda C1 y una C2 por ubicación seleccionada.</p>
                    <div class="max-h-64 space-y-1 overflow-y-auto rounded-md border border-border-subtle p-3">
                        <label v-for="location in locations" :key="location.id" class="flex items-center gap-2 text-sm text-text-primary">
                            <input type="checkbox" :value="location.id" v-model="form.location_ids" class="rounded border-border-subtle" />
                            {{ location.name }} <span v-if="location.code" class="text-text-tertiary">({{ location.code }})</span>
                        </label>
                    </div>
                    <InputError :message="form.errors.location_ids" class="mt-2" />
                </div>

                <div>
                    <InputLabel value="Capturadores (se asignan por ronda)" />
                    <div class="max-h-40 space-y-1 overflow-y-auto rounded-md border border-border-subtle p-3">
                        <label v-for="user in capturadores" :key="user.id" class="flex items-center gap-2 text-sm text-text-primary">
                            <input type="checkbox" :value="user.id" v-model="form.capturador_ids" class="rounded border-border-subtle" />
                            {{ user.name }}
                        </label>
                    </div>
                    <InputError :message="form.errors.capturador_ids" class="mt-2" />
                </div>

                <div class="flex justify-end gap-2">
                    <Button variant="outline" as="Link" :href="route('countings.index')">Cancelar</Button>
                    <Button variant="default" type="submit" :disabled="form.processing">Crear toma</Button>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
