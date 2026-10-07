<script setup>
import { computed } from 'vue';
import { cn } from '@/lib/utils';

/*
 * StatTile — Zellia: card radius.md, borde neutral.300, sombra xs;
 * label = label (12/16, medium, sin uppercase), valor = subtitle1 (20/28)
 * tabular-nums; delta = caption1 con color de su familia de estado.
 * Hover: borde a neutral.400 (un solo cambio, sin salto de sombra).
 */
const props = defineProps({
    label: { type: String, required: true },
    value: { type: [String, Number], required: true },
    delta: { type: [String, Number], default: null },
    deltaTone: {
        type: String,
        default: 'neutral',
        validator: (t) => ['neutral', 'up', 'down'].includes(t),
    },
    hint: { type: String, default: null },
    iconTone: {
        type: String,
        default: null,
        validator: (t) => t === null || ['brand', 'success', 'warning', 'violet', 'info'].includes(t),
    },
});

const deltaClass = computed(() => ({
    up: 'text-status-success',
    down: 'text-status-danger',
    neutral: 'text-text-tertiary',
}[props.deltaTone]));

const iconColorClass = computed(() => ({
    brand: 'zellia-ico-brand',
    success: 'zellia-ico-success',
    warning: 'zellia-ico-warning',
    violet: 'zellia-ico-violet',
    info: 'zellia-ico-info',
}[props.iconTone] || 'zellia-ico-neutral'));
</script>

<template>
    <div class="zellia-stat">
        <div class="flex items-start justify-between gap-2">
            <p class="zellia-label zellia-w-medium zellia-stat__label">
                {{ label }}
            </p>
            <span
                v-if="iconTone"
                :class="cn('shrink-0', iconColorClass)"
            >
                <slot name="icon" />
            </span>
            <slot v-else name="icon" />
        </div>
        <p class="zellia-subtitle-1 zellia-w-semibold zellia-stat__value">
            {{ value }}
        </p>
        <p v-if="delta !== null || hint" :class="cn('zellia-caption-1 mt-1 flex items-center gap-1.5', deltaClass)">
            <span v-if="delta !== null">{{ delta }}</span>
            <span v-if="hint" class="text-text-tertiary">{{ hint }}</span>
        </p>
    </div>
</template>

<style scoped>
.zellia-stat {
    border-radius: var(--zellia-radius-md);
    border: 1px solid var(--zellia-color-neutral-300);
    background: var(--zellia-color-common-white);
    box-shadow: var(--zellia-shadow-xs);
    padding: var(--zellia-space-16);
    transition: border-color 150ms ease-out;
}
.zellia-stat:hover { border-color: var(--zellia-color-neutral-400); }
.zellia-stat__label { color: var(--text-tertiary); text-transform: none; letter-spacing: 0; }
.zellia-stat__value { color: var(--text-primary); font-variant-numeric: tabular-nums; }
:global(.dark) .zellia-stat {
    background: var(--surface-raised);
    border-color: var(--border-subtle);
}
/* Tonos de ícono: base = solo para iconografía (regla §1.6) */
.zellia-ico-brand   { color: var(--zellia-color-primary-800); }
.zellia-ico-success { color: var(--zellia-color-success-base); }
.zellia-ico-warning { color: var(--zellia-color-warning-base); }
.zellia-ico-violet  { color: #8b5cf6; }
.zellia-ico-info    { color: var(--zellia-color-info-base); }
.zellia-ico-neutral { color: var(--zellia-color-neutral-500); }
</style>
