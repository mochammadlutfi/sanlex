<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post("/login", {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
            <el-card class="!rounded-2xl">
                <img src="/images/logo/logo.png" class="w-75 mx-auto"/>
                <el-form label-position="top" @submit.prevent="submit">
                    <el-form-item label="Email" :error="form.errors.email">
                        <el-input type="text" v-model="form.email" />
                    </el-form-item>
                    <el-form-item label="Password" :error="form.errors.password">
                        <el-input type="password" v-model="form.password" />
                    </el-form-item>
                    <el-button type="primary" native-type="submit" class="w-full" :disabled="form.processing">
                        Login
                    </el-button>
                </el-form>
            </el-card>
        </div>
    </GuestLayout>
</template>
