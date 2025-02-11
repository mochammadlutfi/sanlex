<template>
    <base-layout>
        <div class="content">
            <div class="content-header">
                <div class="mt-auto mb-0">
                    <div class="text-lg font-semibold">{{ $t('base.color') }}</div>
                </div>
                <div class="mt-auto mb-0">
                    <el-breadcrumb separator="/">
                        <el-breadcrumb-item :to="{ path: '/dashboard' }">{{ $t('base.dashboard') }}</el-breadcrumb-item>
                        <el-breadcrumb-item :to="{ path: '/product' }">{{ $t('base.product') }}</el-breadcrumb-item>
                        <el-breadcrumb-item>{{ $t('base.color') }}</el-breadcrumb-item>
                    </el-breadcrumb>
                </div>
            </div>

            <el-card body-class="!p-0" class="!rounded-lg !shadow-md !mb-10">
                <div class="flex justify-between items-center p-4">
                    <el-select v-model="params.limit" :placeholder="$t('common.select')" class="w-24"
                        @change="refetch"
                        :disable="isLoading">
                        <el-option label="25" value="25" />
                        <el-option label="50" value="50" />
                        <el-option label="100" value="100" />
                    </el-select>

                    <div class="flex items-center gap-2">
                        <el-input v-model="params.search"
                            clearable
                            @input="doSearch"
                            :disable="isLoading"
                            >
                            <template #prefix>
                                <Icon icon="mingcute:search-line" />
                            </template>
                        </el-input>
                        <el-button type="primary" @click.prevent="openModal">
                            <icon icon="mingcute:add-line" class="me-2" />
                            {{ $t('common.create') }}
                        </el-button>
                    </div>
                </div>
                <el-skeleton :loading="isLoading" animated>
                    <template #template>
                        <skeleton-table />
                    </template>
                    <template #default>
                        <el-table class="min-w-full" :data="data.data" @sort-change="sortChange"
                            @selection-change="onSelectionChange">
                            <el-table-column type="selection" width="55" />
                            <el-table-column :label="$t('base.color')">
                                <template #default="scope">
                                    <div class="w-25 py-2" :style="{ background : scope.row.hex }"></div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="name" :label="$t('common.name')" sortable />
                            <el-table-column prop="code" :label="$t('common.code')" sortable />
                            <el-table-column prop="product_count" :label="$t('common.total_data', { data : $t('base.product')})" sortable />
                            <el-table-column :label="$t('common.action')" align="center" width="150">
                                <template #default="scope">
                                    <el-dropdown popper-class="dropdown-action" trigger="click">
                                        <el-button type="primary">
                                            {{ $t('common.action') }}
                                        </el-button>
                                        <template #dropdown>
                                            <el-dropdown-menu>
                                                <el-dropdown-item class="flex justify-between"
                                                    @click.prevent="onEdit(scope.row)">
                                                    <Icon icon="mingcute:edit-line" class="me-2" />
                                                    {{ $t('common.edit') }}
                                                </el-dropdown-item>
                                                <el-dropdown-item class="flex justify-between"
                                                    @click.prevent="onDelete(scope.row.id)">
                                                    <Icon icon="mingcute:delete-2-line" class="me-2" />
                                                    {{ $t('common.delete') }}
                                                </el-dropdown-item>
                                            </el-dropdown-menu>
                                        </template>
                                    </el-dropdown>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div class="flex justify-between items-center p-4">
                            <div class="flex items-center gap-2">
                                <span>{{ $t('common.table_paginate', { from: data.from, to: data.to, total:data.total }) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <el-pagination class="float-end" background layout="prev, pager, next"  
                                    :page-size="data.per_page" 
                                    :total="data.total" 
                                    :current-page="data.current_page" 
                                    @current-change="changePage"
                                />
                            </div>
                        </div>
                    </template>
                </el-skeleton>
            </el-card>

            <el-dialog id="modalForm" v-model="formShow" :title="formTitle" class="!sm:w-full !w-1/3 rounded-lg"
                :close-on-click-modal="false" :close-on-press-escape="false">
                <el-form label-position="top" ref="formRef" :model="form" :rules="formRules" @submit.prevent="onSubmit">
                    <el-form-item :label="$t('common.name')" prop="name">
                        <el-input v-model="form.name" />
                    </el-form-item>
                    <el-form-item :label="$t('common.code')" prop="code">
                        <el-input v-model="form.code" />
                    </el-form-item>
                    <el-form-item :label="$t('base.color')" prop="code">
                        <el-color-picker v-model="form.hex" size="large"/>
                    </el-form-item>
                    <div class="text-end">
                        <el-button @click.prevent="onResetForm">
                            {{ $t('common.cancel') }}
                        </el-button>
                        <el-button type="primary" native-type="submit">
                            {{ $t('common.save') }}
                        </el-button>
                    </div>
                </el-form>
            </el-dialog>
        </div>
    </base-layout>
</template>

<script setup>
    import {
        ref,
        onMounted
    } from 'vue';
    import axios from 'axios';
    import {
        ElMessageBox,
        ElMessage,
        ElLoading
    } from 'element-plus';
    import {
        Icon
    } from '@iconify/vue';
    import _ from 'lodash';
    import {
        trans as t
    } from 'laravel-vue-i18n';
    import SkeletonTable from '@/Components/SkeletonTable.vue';
    import { useQuery } from '@tanstack/vue-query';

    const params = ref({
        page: 1,
        limit: 25,
        search: ""
    });
    const selected = ref([]);

    const fetchData = async ({
        queryKey
    }) => {
        const [_key, queryParams] = queryKey;
        const response = await axios.get("/product/color/data", {
            params: queryParams,
        });
        return response.data;
    };

    const {
        data,
        isLoading,
        isError,
        error,
        refetch
    } = useQuery({
        queryKey: ['fetchData', params.value], // Query key unik
        queryFn: fetchData,
        keepPreviousData: true,
    });

    const doSearch = _.debounce(() => {
        params.value.page = 1;
        refetch();
    }, 1000);

    const onSelectionChange = (val) => {
        selected.value = val
    }
    const sortChange = () => {
        refetch();
    }

    const changePage = (newPage) => {
        params.value.page = newPage;
        refetch(); 
    };


    const formRef = ref(null);
    const formShow = ref(false);
    const formTitle = ref('');
    const form = ref({
        id: null,
        name: null,
    });

    const formRules = ref({
        name: [{
            required: true,
            message: t('validation.required', {
                attribute: t('common.name')
            }),
            trigger: 'blur'
        }, ],
    });
    const formLoading = ref(false);

    const openModal = () => {
        formTitle.value = `${ t('common.create') } ${ t('base.color') }`;
        formShow.value = true;
        form.value.id = null;
        form.value.name = null;
        form.value.code = null;
        form.value.hex = null;
    }

    const onEdit = (data) => {
        formTitle.value = `${ t('common.edit') } ${ t('base.color') }`;
        formShow.value = true;
        form.value.id = data.id;
        form.value.name = data.name;
        form.value.code = data.code;
        form.value.hex = data.hex;
    }

    const onResetForm = () => {
        formShow.value = false;
        form.value.id = null;
        form.value.name = null;
        form.value.code = null;
        form.value.hex = null;
    }

    const onSubmit = async () => {
        if (!formRef.value) return;
        formRef.value.validate(async (valid) => {
            if (valid) {
                const loading = ElLoading.service({
                    customClass: 'rounded-md',
                    target: document.querySelector('#modalForm')
                });
                try {
                    formLoading.value = true;
                    const url = form.value.id ? `/product/color/${form.value.id}/update` :
                        '/product/color/store';
                    const method = form.value.id ? 'put' : 'post';
                    await axios({
                        method,
                        url,
                        data: form.value
                    });

                    formLoading.value = false;
                    formShow.value = false;
                    refetch();
                    onResetForm();
                    ElMessage({
                        message: t('message.success_save'),
                        type: 'success',
                    });
                } catch (error) {
                    formLoading.value = false;
                    ElMessage({
                        message: t('message.error_server'),
                        type: 'error',
                    });
                }
                loading.close();
            } else {
                ElMessage({
                    message: t('message.error_input'),
                    type: 'error',
                });
            }
        });
    };

    const onDelete = (id) => {
        ElMessageBox.confirm(t("message.delete_confirm"), t('message.delete_confirm_title'), {
            confirmButtonText: t("common.ok"),
            cancelButtonText: t("common.cancel"),
            type: 'warning',
        }).then(() => {
            axios.delete(`/product/color/${id}/delete`).then(() => {
                refetch();
                ElMessage({
                    type: 'success',
                    message: t('message.delete_success'),
                });
            }).catch(error => {
                ElMessage({
                    type: 'error',
                    message: t('message.delete_cancel'),
                });
            });
        }).catch(() => {
            ElMessage({
                type: 'info',
                message: t('message.delete_cancel')
            });
        });
    };

</script>
