<script setup>
import { cn } from '@/lib/utils';

/*
 * Card — Zellia: radius.md (8) default del sistema, borde neutral.300,
 * fondo blanco (Light) / surface-raised (Dark), sombra xs tintada
 * rgb(0,32,65); hover sube UN nivel (xs → md, regla §5.5).
 */
defineProps({
    padded: { type: Boolean, default: true },
    hoverable: { type: Boolean, default: false },
});
</script>

<template>
    <div
        :class="cn(
            'zellia-card',
            padded && 'zellia-card--padded',
            hoverable && 'zellia-card--hoverable',
            $attrs.class,
        )"
    >
        <slot />
    </div>
</template>

<style scoped>
.zellia-card {
    border-radius: var(--zellia-radius-md);
    border: 1px solid var(--zellia-color-neutral-300);
    background: var(--zellia-color-common-white);
    box-shadow: var(--zellia-shadow-xs);
    transition: box-shadow var(--zellia-shadow-transition), border-color 150ms ease-out;
}
.zellia-card--padded { padding: var(--zellia-space-24); }
.zellia-card--hoverable { cursor: pointer; }
.zellia-card--hoverable:hover {
    box-shadow: var(--zellia-shadow-md);
    border-color: var(--zellia-color-neutral-400);
}
:global(.dark) .zellia-card {
    background: var(--surface-raised);
    border-color: var(--border-subtle);
}
</style>
