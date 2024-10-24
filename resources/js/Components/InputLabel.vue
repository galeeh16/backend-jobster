<script setup>
const props = defineProps({
    required: {
        require: false,
        default: false,
        type: Boolean
    }
})
</script>

<template>
    <label class="text-sm font-medium text-gray-700 flex w-full gap-1 pb-2 items-center" v-bind="$attrs">
        <slot />
        <span v-if="props.required" class="text-red-600">*</span>
    </label>
</template>
