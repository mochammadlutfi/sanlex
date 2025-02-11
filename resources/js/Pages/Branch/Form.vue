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
                        <el-breadcrumb-item>{{ $t('base.product') }}</el-breadcrumb-item>
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
                                <el-input v-model="form.name" placeholder="Masukan Nama Kantor Cabang"/>
                            </el-form-item>
                            <el-form-item label="No Telepon">
                                <el-input v-model="form.phone" placeholder="Masukan No Telpon"/>
                            </el-form-item>
                            <el-row :gutter="16">
                                <el-col :md="12">
                                    <el-form-item label="Provinsi" prop="province_id">
                                        <select-daerah v-model="form.province_id"/>
                                    </el-form-item>
                                </el-col>
                                <el-col :md="12">
                                    <el-form-item label="Kota" prop="city_id">
                                        <select-daerah v-model="form.city_id" type="kota" hasParent :parent="form.province_id"/>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-form-item label="Alamat Lengkap">
                                <el-input type="textarea" :row="2" v-model="form.address" placeholder="Masukan No Telpon"/>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-row :gutter="16">
                                <el-col :md="12">
                                    <el-form-item label="Latitude">
                                        <el-input v-model="form.lat" placeholder="Masukan Latitude"/>
                                    </el-form-item>
                                </el-col>
                                <el-col :md="12">
                                    <el-form-item label="Longtitude">
                                        <el-input v-model="form.lng" placeholder="Masukan Longtitude"/>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="16">
                                <el-col :md="8">
                                    <el-form-item label="Kode">
                                        <el-input v-model="form.code"/>
                                    </el-form-item>
                                </el-col>
                                <el-col :md="8">
                                    <el-form-item label="Key">
                                        <el-input v-model="form.key"/>
                                    </el-form-item>
                                </el-col>
                                <el-col :md="8">
                                    <el-form-item label="Ref">
                                        <el-input v-model="form.ref"/>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-form-item label="Server">
                                <el-input v-model="form.server_link"/>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <div class="flex">
                        <el-button>
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
import { router } from '@inertiajs/vue3';

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

onMounted(() => {
    title.value = props.data ? 'Ubah Cabang' : 'Buat Cabang Baru';

    if(props.data){
        form.value.id = props.data.id;
        form.value.name = props.data.name;
        form.value.province_id = props.data.province_id;
        form.value.city_id = props.data.city_id;
        form.value.address = props.data.address;
        form.value.lat = props.data.lat;
        form.value.lng = props.data.lng;
        form.value.code = props.data.code;
        form.value.key = props.data.key;
        form.value.ref = props.data.ref;
        form.value.server_link = props.data.server;
    }
});

const onSubmit = async () => {
    if (!formRef.value) return;
    formRef.value.validate(async (valid, e) => {
        if (valid) {
            formLoading.value = true;
            try {
                const url = form.value.id ? `/branch/${form.value.id}/update` : '/branch/store';
                const method = form.value.id ? 'post' : 'post';

                const response = await axios({
                    method,
                    url,
                    data: form.value,
                });
                if (response.status === 200) {
                    router.replace('/branch');

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