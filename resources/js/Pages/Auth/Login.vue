<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import BlankLayout from '../../Layouts/BlankLayout.vue';
import InputText from '../../Components/InputText.vue';
import InputLabel from '../../Components/InputLabel.vue';
import Button from '../../Components/Button.vue';
import InputError from '../../Components/InputError.vue';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <BlankLayout>
        <div class="bg-gray-100 min-h-screen flex items-center justify-center w-full">
            <div class="mx-auto w-[400px] px-4 bg-white rounded-xl p-4 lg:p-8 xl:p-10">
                <h1 class="text-xl font-medium block">Login</h1>

                <form method="post" @submit.prevent="submit">
                    <div class="mt-8 mb-5">
                        <InputLabel>Email</InputLabel>
                        <InputText type="text" v-model="form.email" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="mb-5">
                        <InputLabel>Password</InputLabel>
                        <InputText type="password" v-model="form.password" />
                        <InputError :message="form.errors.password" />
                    </div>
                    <div>
                        <Button type="submit" class="w-full">Login</Button>
                    </div>
                </form>
            </div>
        </div>
    </BlankLayout>
</template>