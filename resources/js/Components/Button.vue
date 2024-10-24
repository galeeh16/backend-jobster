<script setup>
import { computed } from 'vue';

const props = defineProps({
    size: {
        require: false,
        type: String,
        default: 'md',
    },
    variant: {
        require: false,
        type: String,
        default: 'primary'
    },
});

const variantClass = computed(() => {
    switch (props.variant) {
        case 'primary':
            return 'text-white bg-emerald-600 rounded-lg hover:bg-emerald-700'
        case 'outline-primary':
            return 'text-emerald-600 bg-white border border-emerald-600 rounded-lg hover:bg-emerald-50'
        case 'outline':
            return 'text-gray-800 bg-white rounded-3xl hover:bg-gray-100 border border-gray-800';
        default:
            return '';
    }
});
</script>

<template>
    <button type="button" :class="[
        variantClass,
        'py-2.5 px-4 rounded-md text-[14px] font-medium transition-all duration-150',
    ]">
        <slot />
    </button>
</template>