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
                                    <select-branch v-model="form.branch_id"/>
                                </el-form-item>
                            </el-col>
                            <el-col :md="12">
                                <el-form-item label="Orange Ref" prop="ref">
                                    <el-input v-model="form.ref"/>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <div class="flex justify-between items-center mb-4">
                            <div class="text-lg font-semibold">Alamat Customer</div>
                            <el-button type="primary" @click.prevent="onAddAddress">
                                Tambah Alamat
                            </el-button>
                        </div>
                        <el-row :gutter="12" class="mb-4">
                            <el-col :md="12" v-for="(d, i) in form.address" :key="i">
                                <el-card class="mb-4">
                                    <div class="flex justify-between mb-2">
                                        <div>
                                            {{ d.address }} ,{{ d.postal_code }} <br>
                                            {{ d.kel.name }} ,{{ d.kec.name }} ,{{ d.kota.name }} ,{{ d.prov.name }}

                                        </div>
                                        <el-tag type="primary" v-if="d.is_main">Alamat Utama</el-tag>
                                    </div>
                                    <div class="flex">
                                        <el-button @click.prevent="onEditAddress(i)">
                                            Ubah
                                        </el-button>
                                        <el-button type="danger" @click.prevent="onRemoveAddress(i)">
                                            Hapus
                                        </el-button>
                                    </div>
                                </el-card>
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

    <el-dialog id="modalForm" v-model="formShow" title="Alamat Customer" class="!sm:w-full !w-1/2 rounded-lg"
        :close-on-click-modal="false" :close-on-press-escape="false">
        <el-form label-position="top" ref="formRef" :model="formAddress" @submit.prevent="onsubmitAddress">
            <el-row :gutter="16">
                <el-col :md="12">
                    <el-form-item label="Provinsi" prop="prov">
                        <select-daerah v-model="formAddress.prov" type="provinsi"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Kota" prop="kota">
                        <select-daerah v-model="formAddress.kota" type="kota" hasParent :parent="formAddress.prov ? formAddress.prov.id : null"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Kecamatan" prop="kec">
                        <select-daerah v-model="formAddress.kec" type="kecamatan" hasParent :parent="formAddress.kota ? formAddress.kota.id : null"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Desa/Kelurahan" prop="kel">
                        <select-daerah v-model="formAddress.kel" type="kelurahan" hasParent :parent="formAddress.kec ? formAddress.kec.id : null"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Kode POS" prop="postal_code">
                        <el-input v-model="formAddress.postal_code" />
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Alamat Utama">
                        <el-switch v-model="formAddress.is_main"/>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item label="Alamat" prop="alamat">
                <el-input v-model="formAddress.address" />
            </el-form-item>
            <div class="text-end">
                <el-button @click.prevent="onResetForm">
                    Batal
                </el-button>
                <el-button type="primary" native-type="submit">
                    Simpan
                </el-button>
            </div>
        </el-form>
    </el-dialog>
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
import SelectBranch from '@/Components/Form/SelectBranch.vue';
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
    mobile : null,
    branch_id : null,
    ref :null,
    address : [],
    removeAddress : [],
});

const formShow = ref(false);
const formAddress = ref({
    index : null,
    id : null,
    prov : null,
    kota : null,
    kec : null,
    kel : null,
    postal_code : null,
    address : null,
    is_main : false,
    lat : null,
    lng : null
});

const formRules = ref({
    name: [{
        required: true,
        message: t('validation.required', {
            attribute: t('common.name')
        }),
        trigger: 'blur'
    },],

});

const onCancel = () => {
    router.push('/customer');
}

const onAddAddress = () => {
    formShow.value = true;
    formAddress.value = {
        index : form.value.address.length,
        id : null,
        prov : null,
        kota : null,
        kec : null,
        kel : null,
        postal_code : null,
        address : null,
        is_main : false,
        lat : null,
        lng : null
    }
}

const onEditAddress = (index) => {
    formShow.value = true;
    formAddress.value.id = form.value.address[index].id;
    formAddress.value.index = index;
    formAddress.value.prov = form.value.address[index].prov;
    formAddress.value.kota = form.value.address[index].kota;
    formAddress.value.kec = form.value.address[index].kec;
    formAddress.value.kel = form.value.address[index].kel;
    formAddress.value.postal_code = form.value.address[index].postal_code;
    formAddress.value.address = form.value.address[index].address;
    formAddress.value.is_main = form.value.address[index].is_main;
    formAddress.value.lat = form.value.address[index].lat;
    formAddress.value.lng = form.value.address[index].lng;
}

const onResetForm = () => {
    formShow.value = false;
}

const onsubmitAddress = () => {
    form.value.address[formAddress.value.index] = {
        index : formAddress.value.index,
        id : formAddress.value.id,
        prov : formAddress.value.prov,
        kota : formAddress.value.kota,
        kec : formAddress.value.kec,
        kel : formAddress.value.kel,
        postal_code : formAddress.value.postal_code,
        address : formAddress.value.address,
        lat : formAddress.value.lat,
        lng : formAddress.value.lng,
        is_main : formAddress.value.is_main
    };
    formShow.value = false;
}
const onRemoveAddress = (index) => {
    if(form.value.address[index].id){
        form.value.removeAddress.push(form.value.address[index].id);
    }
    form.value.address.splice(index, 1);
}

onMounted(() => {
    title.value = props.data ? 'Ubah Customer' : 'Buat Customer Baru';

    if(props.data){
        form.value.id = props.data.id;
        form.value.name = props.data.name;
        form.value.phone = props.data.phone;
        form.value.email = props.data.email;
        form.value.mobile = props.data.mobile;
        form.value.branch_id = props.data.branch_id;
        form.value.ref = props.data.ref;

        if(props.data.address){
            props.data.address.forEach((v,i) => {
                form.value.address.push({
                    index : i,
                    id : v.id,
                    prov : v.provinsi,
                    kota : v.kota,
                    kec : v.kecamatan,
                    kel : v.kelurahan,
                    postal_code : v.pos,
                    address : v.address,
                    is_main : v.is_main ? true : false,
                    lat : v.lat,
                    lng : v.lng
                });
            });
        }
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