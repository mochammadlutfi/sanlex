<template>
    <base-layout>
        <div class="content">
            <div class="content-header">
                <div class="mt-auto mb-0">
                    <div class="text-lg font-semibold">{{ title }}</div>
                </div>
                <div class="mt-auto mb-0">
                    <el-breadcrumb separator="/">
                        <el-breadcrumb-item :to="{ path: '/dashboard' }">{{ $t('base.dashboard') }}
                        </el-breadcrumb-item>
                        <el-breadcrumb-item>Customer</el-breadcrumb-item>
                    </el-breadcrumb>
                </div>
            </div>
            
            <el-card body-class="!p-0" class="!rounded-lg !shadow-md !mb-10">
                <el-form :model="form" ref="formRef" :rules="formRules" 
                @submit.prevent="onSubmit" 
                label-position="top" v-loading="formLoading">
                <div class="p-4">
                    <el-row :gutter="16">
                        <el-col :md="12">
                            <el-form-item label="Nama" prop="name">
                                <el-input v-model="form.name"/>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-form-item label="No Handphone" prop="mobile">
                                <el-input v-model="form.mobile"/>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-form-item label="Email" prop="email">
                                <el-input v-model="form.email"/>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-form-item label="No Telepon" prop="phone">
                                <el-input v-model="form.phone"/>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-form-item label="Cabang" prop="branch_id">
                                <el-input v-model="form.branch_id"/>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-form-item label="Orange Ref" prop="ref">
                                <el-input v-model="form.ref"/>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <div class="flex">
                        <el-button :tag="Link" href="/customer">
                            <Icon icon="mingcute:close-fill" class="me-2"/>
                            {{ $t('common.cancel') }}
                        </el-button>
                        <el-button native-type="submit" type="primary">
                            <Icon icon="mingcute:check-fill" class="me-2"/>
                            {{ $t('common.save') }}
                        </el-button>
                    </div>
                </div>
                </el-form>
            </el-card>
        </div>
    </base-layout>
</template>

<script setup>

import {ref,onMounted} from 'vue';
import axios from 'axios';
import { ElMessageBox,ElMessage,ElLoading } from 'element-plus';
import { Icon } from '@iconify/vue';
import _ from 'lodash';
import { trans as t } from 'laravel-vue-i18n';
import SelectDaerah from '@/Components/Form/SelectDaerah.vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    data : {
        type : Object,
        default :null,
    }
})

const title = ref('');
const formRef = ref(null);
const formLoading = ref(false);
const form = ref({
    id : null,
    name : null,
    phone : null,
    province_id : null,
    city_id :null,
    address : null,
    postal_code : null,
    lat : null,
    lng :null,
    code : null,
    key : null,
    server_link : null,
});

const formRules = ref({
    name: [{
        required: true,
        message: t('validation.required', {
            attribute: t('common.name')
        }),
        trigger: 'blur'
    }, ],
    province_id: [{
        required: true,
        message: t('validation.required', {
            attribute: 'Provinsi'
        }),
        trigger: 'change'
    }, ],

    city_id: [{
        required: true,
        message: t('validation.required', {
            attribute: 'Kota'
        }),
        trigger: 'change'
    }, ],

});

const onCancel = () => {
    router.push('/customer');
}

onMounted(() => {
    console.log(t('base.customer'));

    title.value = props.data ? 'Ubah Customer' : 'Buat Customer Baru';

    if(props.data){
        form.value.id = props.data.id;
        form.value.name = props.data.name;
        form.value.phone = props.data.phone;
        form.value.email = props.data.email;
        form.value.mobile = props.data.mobile;
    }
});

const onSubmit = async () => {
    if (!formRef.value) return;
    formRef.value.validate(async (valid, e) => {
        if (valid) {
            formLoading.value = true;
            try {
                const url = form.value.id ? `/customer/${form.value.id}/update` : '/customer/store';
                const method = form.value.id ? 'post' : 'post';

                const response = await axios({
                    method,
                    url,
                    data: form.value,
                });
                if (response.status === 200) {
                    router.replace('/customer');

                    ElMessage({
                        message: t('message.success_save'),
                        type: 'success',
                    });
                }
            } catch (error) {
                console.log(error);
                ElMessage({
                    message: t('message.error_server'),
                    type: 'error',
                });
            }
            formLoading.value = false;
        } else {
            console.log(valid);
            console.log(e);
            ElMessage({
                message: t('message.error_input'),
                type: 'error',
            });
        }
    });
}; 
</script>