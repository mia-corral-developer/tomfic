<script setup>
import { computed } from 'vue';
import { cva } from 'class-variance-authority';
import { cn } from '@/lib/utils';

/*
 * Badge — Zellia Alert System (§1.5): fondo light, texto dark de la MISMA
 * familia (única pareja válida), borde base al 20%. Dot = base. neutral
 * usa Mono. Radios: sm (4) para controles pequeños.
 */
const props = defineProps({
    variant: {
        type: String,
        default: 'neutral',
        validator: (v) => ['neutral', 'brand', 'success', 'warning', 'danger', 'info'].includes(v),
    },
    size: {
        type: String,
        default: 'md',
        validator: (s) => ['sm', 'md'].includes(s),
    },
    dot: { type: Boolean, default: false },
});

const badge = cva(
    'inline-flex items-center gap-1.5 rounded-md font-medium tracking-tight zellia-badge',
    {
        variants: {
            variant: {
                neutral: 'zellia-badge-neutral',
                brand: 'zellia-badge-brand',
                success: 'zellia-badge-success',
                warning: 'zellia-badge-warning',
                danger: 'zellia-badge-danger',
                info: 'zellia-badge-info',
            },
            size: {
                sm: 'zellia-badge--sm',
                md: 'zellia-badge--md',
            },
        },
    }
);
</script>

<template>
    <span :class="cn(badge({ variant, size }))">
        <span v-if="dot" :class="['zellia-badge__dot']" />
        <slot />
    </span>
</template>

<style scoped>
.zellia-badge { line-height: var(--zellia-font-line-height-label); }
.zellia-badge--sm { height: 20px; padding-inline: var(--zellia-space-4); font-size: var(--zellia-font-size-caption-2); border-radius: var(--zellia-radius-sm); }
.zellia-badge--md { height: 24px; padding-inline: var(--zellia-space-8); font-size: var(--zellia-font-size-caption-1); border-radius: var(--zellia-radius-sm); }
.zellia-badge__dot { height: 6px; width: 6px; border-radius: var(--zellia-radius-full); flex: 0 0 auto; }

.zellia-badge-neutral { background: var(--surface-overlay); color: var(--text-secondary); border: 1px solid var(--border-subtle); }
.zellia-badge-neutral .zellia-badge__dot { background: var(--text-tertiary); }

.zellia-badge-brand   { background: var(--zellia-color-primary-100); color: var(--zellia-color-primary-900); border: 1px solid rgba(2, 53, 98, 0.2); }
.zellia-badge-brand .zellia-badge__dot { background: var(--zellia-color-primary-800); }

.zellia-badge-success { background: var(--status-success-soft); color: var(--zellia-color-success-dark); border: 1px solid rgba(34, 197, 94, 0.2); }
.zellia-badge-success .zellia-badge__dot { background: var(--status-success); }

.zellia-badge-warning { background: var(--status-warning-soft); color: var(--zellia-color-warning-dark); border: 1px solid rgba(245, 158, 11, 0.2); }
.zellia-badge-warning .zellia-badge__dot { background: var(--status-warning); }

.zellia-badge-danger  { background: var(--status-danger-soft); color: var(--zellia-color-error-dark); border: 1px solid rgba(239, 68, 68, 0.2); }
.zellia-badge-danger .zellia-badge__dot { background: var(--status-danger); }

.zellia-badge-info    { background: var(--status-info-soft); color: var(--zellia-color-info-dark); border: 1px solid rgba(33, 150, 243, 0.2); }
.zellia-badge-info .zellia-badge__dot { background: var(--status-info); }
</style>
