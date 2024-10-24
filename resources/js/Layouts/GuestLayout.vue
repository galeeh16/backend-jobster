<script setup>
import Navbar from '../Components/Navbar.vue';
</script>

<template>
    <Navbar />
    <div class="bg-gray-100">
        <div class="mx-auto flex min-h-screen max-w-screen-2xl xl:px-12 lg:px-10 md:px-6 px-4 ">
            <slot />
        </div>
    </div>
</template>
