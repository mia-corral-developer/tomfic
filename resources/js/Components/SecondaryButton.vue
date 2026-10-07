<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <!-- Zellia CTA Secondary: fondo Mono/900, SemiBold, 48px -->
    <button
        :type="type"
        class="zellia-cta zellia-cta-secondary"
        :disabled="$attrs.disabled || undefined"
    >
        <slot />
    </button>
</template>
