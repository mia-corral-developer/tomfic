<template>
    <button
        :type="$attrs.type || 'submit'"
        class="zellia-cta zellia-cta-principal"
        :disabled="$attrs.disabled || undefined"
    >
        <slot />
    </button>
</template>
