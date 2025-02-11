<template>
    <base-layout>
        <div class="content">
            <div class="content-header">
                <div class="mt-auto mb-0">
                    <div class="text-lg font-semibold">{{ $t('base.customer') }}</div>
                </div>
                <div class="mt-auto mb-0">
                    <el-breadcrumb separator="/">
                        <el-breadcrumb-item :to="{ path: '/dashboard' }">{{ $t('base.dashboard') }}
                        </el-breadcrumb-item>
                        <el-breadcrumb-item>{{ $t('base.customer') }}</el-breadcrumb-item>
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
                        <el-button :tag="Link" href="/customer/create" type="primary">
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
                            <el-table-column prop="name" :label="$t('common.name')" sortable/>
                            <el-table-column prop="address" :label="$t('common.address')" sortable/>
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
    import {
        useQuery
    } from '@tanstack/vue-query';
    import { Link } from '@inertiajs/vue3';

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
        const response = await axios.get("/customer/data", {
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

    const onDelete = (id) => {
        ElMessageBox.confirm(t("message.delete_confirm"), t('message.delete_confirm_title'), {
            confirmButtonText: t("common.ok"),
            cancelButtonText: t("common.cancel"),
            type: 'warning',
        }).then(() => {
            axios.delete(`/product/customer/${id}/delete`).then(() => {
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
