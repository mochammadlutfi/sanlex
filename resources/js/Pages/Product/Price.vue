<template>
    <base-layout>
        <div class="content">
            <div class="content-header">
                <div class="mt-auto mb-0">
                    <div class="text-lg font-semibold">Harga {{ data.name }}</div>
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
                label-position="top"
                v-loading="formLoading">
                    <div class="p-4">
                        <el-row :gutter="16" class="mb-4">
                            <el-col :md="12">
                                <el-form-item label="Cabang">
                                    <select-branch v-model="form.branch_id" :disabled="isLoading" @change="fetchVariant"/>
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-table :data="form.priceList" border
                            class="w-full mb-4" id="variant"
                            row-key="name"
                            v-loading="isLoading"
                            :span-method="rowSpanMethod">
                            <el-table-column :label="$t('base.packaging')" width="300">
                                <template #default="scope">
                                    {{ scope.row.packaging.name }}
                                </template>
                            </el-table-column>
                            <el-table-column  :label="$t('base.color')" v-if="data.color.length">
                                <template #default="scope" >
                                    {{ scope.row.color.code }} -
                                    {{ scope.row.color.name }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Kode">
                                <template #default="scope" >
                                    {{ scope.row.code }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Harga">
                                <template #default="scope">
                                    <el-form-item :prop="'priceList.' + scope.$index + '.price'" :rules="[{
                                        required: true,
                                        message: t('validation.required', {
                                            attribute: 'Harga'
                                        }),
                                        trigger: 'blur'
                                    }]">
                                    <el-input v-model="scope.row.price"/>
                                </el-form-item>
                                </template>
                            </el-table-column>
                        </el-table>

                        <div class="flex">
                            <el-button :disabled="!form.priceList.length" native-type="submit" type="primary">
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
import SelectBranch from '@/Components/Form/SelectBranch.vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    data : {
        type : Object,
        default :null,
    }
})
const branch_id = ref('');
const title = ref('');
const formRef = ref(null);
const formLoading = ref(false);
const priceList = ref([]);
const isLoading = ref(false);
const onResetSearch = () => {
    priceList.value = [];
}

const form = ref({
    branch_id : '',
    priceList : []
});

const fetchVariant = async () => {
    if(form.value.branch_id){

        isLoading.value = true;
        try {
            const response = await axios.get(`/product/${ props.data.id}/price/data`,{
                params : {
                    branch_id : form.value.branch_id
                }
            });
            if (response.status === 200) {
                
                form.value.priceList = [];
                response.data.forEach((v) => {
                    form.value.priceList.push({
                        id : v.price,
                        product_id : v.product_id,
                        variant_id : v.variant_id,
                        branch_id : v.branch_id,
                        name : v.name,
                        packaging_id : v.packaging_id,
                        packaging : v.packaging,
                        color_id : v.color_id,
                        color : v.color,
                        code : v.code,
                        price : v.price
                    });
                });
            }
        } catch (error) {
        }
        isLoading.value = false;
    }else{
        form.value.priceList = [];
    }
};

const formRules = ref({

});

const onCancel = () => {
    router.push('/customer');
}

const rowSpanMethod = ({
  row,
  column,
  rowIndex,
  columnIndex,
}) => {
    if (columnIndex === 0) {
        if (rowIndex === 0 || row.packaging_id !== form.value.priceList[rowIndex - 1].packaging_id) {
            let rowVal = 1;
            for (let i = rowIndex + 1; i < form.value.priceList.length; i++) {
                if (form.value.priceList[i].packaging_id === row.packaging_id) {
                    rowVal++;
                } else {
                    break;
                }
            }
            return {
                rowspan : rowVal,
                colspan: 1
            };
        } else {
            return {
                rowspan: 0,
                colspan: 0
            };
        }
    }
}
const onSubmit = async () => {
    if (!formRef.value) return;
    formRef.value.validate(async (valid, e) => {
        if (valid) {
            formLoading.value = true;
            try {
                const url = `/product/${props.data.id}/price/store`;
                const method = 'post';

                const response = await axios({
                    method,
                    url,
                    data: form.value,
                });
                if (response.status === 200) {
                    router.replace('/product');

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