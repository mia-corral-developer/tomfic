<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <!-- Zellia input: radius sm (4), borde neutral.300, texto neutral.900,
         padding space.12, focus con ring 4px + cambio de border-color -->
    <input
        class="zellia-input"
        v-model="model"
        ref="input"
    />
</template>

<style scoped>
.zellia-input {
    border-radius: var(--zellia-radius-sm);
    border: 1px solid var(--zellia-color-neutral-300);
    background: var(--zellia-color-common-white);
    color: var(--zellia-color-neutral-900);
    padding: var(--zellia-space-12);
    font-family: var(--zellia-font-family-base);
    font-size: var(--zellia-font-size-body-1);
    line-height: var(--zellia-font-line-height-body-1);
    width: 100%;
    box-sizing: border-box;
    transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
}
.zellia-input::placeholder { color: var(--zellia-color-neutral-400); }
.zellia-input:focus {
    outline: none;
    border-color: var(--zellia-color-primary-800);
    box-shadow: 0 0 0 4px rgba(2, 53, 98, 0.20); /* zellia shadow.focus.primary */
}
.zellia-input:disabled {
    border-color: var(--zellia-color-neutral-300);
    color: var(--zellia-color-neutral-400);
    background: var(--zellia-color-neutral-100);
    cursor: not-allowed;
}
.dark .zellia-input,
:global(.dark) .zellia-input {
    background: var(--surface-raised);
    color: var(--text-primary);
}
</style>
