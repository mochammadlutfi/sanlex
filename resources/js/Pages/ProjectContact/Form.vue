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
                                <el-input v-model="form.name" placeholder="Masukan Nama"/>
                            </el-form-item>
                            <el-form-item label="No Telepon">
                                <el-input v-model="form.phone" placeholder="Masukan No Telpon"/>
                            </el-form-item>
                            <el-form-item label="Fax" >
                                <el-input v-model="form.fax" placeholder="Masukan No Fax"/>
                            </el-form-item>
                            <el-form-item label="Email" >
                                <el-input v-model="form.email" placeholder="Masukan No Email"/>
                            </el-form-item>
                            <el-form-item label="Nama PIC" >
                                <el-input v-model="form.pic_name" placeholder="Masukan Nama PIC"/>
                            </el-form-item>
                            <el-form-item label="No HP PIC">
                                <el-input v-model="form.pic_phone" placeholder="Masukan No HP PIC"/>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-row :gutter="16">
                                <el-col :md="12">
                                    <el-form-item label="Provinsi" prop="province">
                                        <select-daerah v-model="form.province"/>
                                    </el-form-item>
                                </el-col>
                                <el-col :md="12">
                                    <el-form-item label="Kota" prop="city">
                                        <select-daerah v-model="form.city" type="kota" hasParent :parent="form.province ? form.province.id : null"/>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-form-item label="Alamat Lengkap">
                                <el-input type="textarea" :row="2" v-model="form.address" placeholder="Masukan No Telpon"/>
                            </el-form-item>
                            <el-form-item label="Longtitude">
                                <el-input v-model="form.lng" placeholder="Masukan Longtitude"/>
                            </el-form-item>
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
                        </el-col>
                    </el-row>

                    <div class="flex">
                        <el-button :tag="Link" href="/project/contact">
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
    fax : null,
    email : null,
    pic_name : null,
    pic_phone : null,
    province : null,
    city :null,
    address : null,
    postal_code : null,
    lat : null,
    lng :null,
});

const formRules = ref({
    name: [{
        required: true,
        message: t('validation.required', {
            attribute: t('common.name')
        }),
        trigger: 'blur'
    }, ],
    province: [{
        required: true,
        message: t('validation.required', {
            attribute: 'Provinsi'
        }),
        trigger: 'change'
    }, ],

    city: [{
        required: true,
        message: t('validation.required', {
            attribute: 'Kota'
        }),
        trigger: 'change'
    }, ],

});

onMounted(() => {
    title.value = props.data ? 'Ubah Kontak' : 'Buat Kontak Baru';

    if(props.data){
        form.value.id = props.data.id;
        form.value.name = props.data.name;
        form.value.province = props.data.province;
        form.value.city = props.data.city;
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