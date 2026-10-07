<script setup>
/*
 * PageHeader — jerarquía tipográfica Zellia (§2.3/§2.5):
 *   título = headline2 (24/32), descripción = body2 (14/20),
 *   eyebrow = label (12/16) — sin uppercase: el spec fija ORIGINAL.
 */
defineProps({
    title: { type: String, required: true },
    description: { type: String, default: null },
    eyebrow: { type: String, default: null },
});
</script>

<template>
    <header class="zellia-pageheader">
        <div class="min-w-0">
            <p v-if="eyebrow" class="zellia-label zellia-w-medium zellia-pageheader__eyebrow">
                {{ eyebrow }}
            </p>
            <h1 class="zellia-headline-2 zellia-w-semibold zellia-pageheader__title">
                {{ title }}
            </h1>
            <p v-if="description" class="zellia-body-2 zellia-w-regular zellia-pageheader__description">
                {{ description }}
            </p>
        </div>
        <div v-if="$slots.actions" class="flex items-center gap-2 shrink-0">
            <slot name="actions" />
        </div>
    </header>
</template>

<style scoped>
.zellia-pageheader {
    display: flex;
    flex-direction: column;
    gap: var(--zellia-space-16);
    padding-bottom: var(--zellia-space-24);
    border-bottom: 1px solid var(--zellia-color-neutral-300);
}
@media (min-width: 768px) {
    .zellia-pageheader {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}
.zellia-pageheader__title { margin-top: 0; color: var(--text-primary); }
.zellia-pageheader__description { margin-top: var(--zellia-space-4); color: var(--text-secondary); }
.zellia-pageheader__eyebrow { color: var(--text-tertiary); }
</style>
