<template>
    <base-layout title="Edit Profile">
        <div class="content">
            <div class="content-header">
                <div class="content-title">{{ $t('menu.profile') }}</div>
            </div>
            <el-card shadow="never" body-class="!p-0" class="!rounded-lg !shadow-md">
                <el-form @submit.prevent="onSubmit" label-position="top">
                    <div class="md:p-6">
                        <el-row :gutter="20">
                            <el-col :md="4">
                                <el-form-item :label="$t('avatar')" :error="form.errors.avatar">
                                    <image-upload v-model="form.avatar"/>
                                </el-form-item>
                            </el-col>
                            <el-col :md="8">
                                <el-form-item :label="$t('name')" :error="form.errors.name">
                                    <el-input v-model="form.name" />
                                </el-form-item>
                                <el-form-item :label="$t('email')" :error="form.errors.email">
                                    <el-input v-model="form.email" />
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </div>
                </el-form>
            </el-card>
        </div>
    </base-layout>
</template>
<script setup>
import { defineProps } from 'vue';
import ImageUpload from '@/Components/Form/ImageUpload.vue';
import { useForm } from '@inertiajs/vue3';
const props = defineProps({
    data: {
        type: Object,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    avatar : props.data.avatar,
    name : props.data.name,
    email : props.data.email,
})

const onSubmit = () => {

}
</script>