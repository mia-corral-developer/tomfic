<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { cva } from 'class-variance-authority';
import { cn } from '@/lib/utils';

/*
 * Button — migrado a Zellia (docs/design-system/zellia/Zellia-CTA-Dashboard-Specs.md).
 * API intacta: variantes y tamaños existentes se mapean a la escala Zellia:
 *   variantes → Principal (p700→600→sec900→p300), Secundary (Mono/900→700→300),
 *   Outline (borde Mono/500→800→300); danger mantiene error.base, link sin caja.
 *   tamaños → escala de alturas Zellia 24/32/40/48 con radius md (8) —
 *   los tamaños xs→lg equivalen a Tiny/Small/Medium/Large del sistema.
 * Base tipográfica: Inter, sin uppercase, letter-spacing 0 (spec §CTA).
 */
const props = defineProps({
    variant: {
        type: String,
        default: 'default',
        validator: (v) => ['default', 'secondary', 'ghost', 'outline', 'danger', 'link'].includes(v),
    },
    size: {
        type: String,
        default: 'md',
        validator: (s) => ['xs', 'sm', 'md', 'lg', 'icon'].includes(s),
    },
    as: {
        type: String,
        default: 'button',
        validator: (a) => ['button', 'a', 'Link'].includes(a),
    },
    href: { type: [String, Object], default: null },
    disabled: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
    type: { type: String, default: 'button' },
});

const button = cva(
    'zellia-cta', // base Zellia: 40px (medium)… ver override de tamaño por variante
    {
        variants: {
            variant: {
                default: 'zellia-cta-principal',
                secondary: 'zellia-cta-secondary',
                ghost: 'zellia-cta-ghost',
                outline: 'zellia-cta-outline',
                danger: 'zellia-cta-danger',
                link: 'zellia-cta-link',
            },
            size: {
                xs: 'zellia-h-24 zellia-px-8 zellia-fs-buttonTiny zellia-radius-sm',
                sm: 'zellia-h-32 zellia-px-12 zellia-fs-buttonSmall zellia-radius-sm',
                md: 'zellia-h-40 zellia-px-16 zellia-fs-buttonMedium',
                lg: 'zellia-h-48 zellia-px-20 zellia-fs-buttonLarge',
                icon: 'zellia-h-40 zellia-w-40 zellia-px-0',
            },
        },
        defaultVariants: { variant: 'default', size: 'md' },
    }
);

const classes = computed(() => cn(button({ variant: props.variant, size: props.size })));

const ComponentTag = computed(() => {
    if (props.as === 'Link') return Link;
    return props.as;
});
</script>

<template>
    <component
        :is="ComponentTag"
        :href="href"
        :type="as === 'button' ? type : undefined"
        :disabled="disabled || loading"
        :class="classes"
    >
        <svg
            v-if="loading"
            class="zellia-btn-spinner"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25" />
            <path
                fill="currentColor"
                class="opacity-75"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
            />
        </svg>
        <slot />
    </component>
</template>
