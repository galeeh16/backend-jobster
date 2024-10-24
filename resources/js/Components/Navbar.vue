<script setup>
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
import Button from './Button.vue';
</script>

<template>
    <div
        class="bg-white mx-auto flex items-center max-w-screen-2xl xl:px-12 lg:px-10 md:px-6 px-4 sm:py-3 md:py-4 lg:py-5">
        <div class="flex justify-between w-full items-center">
            <div>
                <Link href="/" class="flex items-center gap-2">
                <AppLogo class="block h-9 w-auto fill-current text-emerald-600" />
                JobSeeker
                </Link>
            </div>

            <div class="flex items-center gap-4">
                <Button variant="outline-primary">
                    <Link href="/login">Sign in</Link>
                </Button>
                <Button>
                    <Link href="/register">Register</Link>
                </Button>
            </div>
        </div>
    </div>
</template>