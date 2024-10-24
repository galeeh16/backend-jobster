<script setup>
const props = defineProps({

});

const model = defineModel();
</script>

<template>
    <input type="text" v-bind="$attrs" v-model="model"
        class="py-2.5 px-3 text-[14px] rounded-md block w-full duration-150 border border-gray-300 outline-none focus-visible:outline-none focus:border-emerald-500" />
</template>