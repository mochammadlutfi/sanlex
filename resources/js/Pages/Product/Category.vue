<template>
    <base-layout>
        <div class="content">
            <div class="content-header">
                <div class="mt-auto mb-0">
                    <div class="text-lg font-semibold">{{ $t('base.category') }}</div>
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
                            <el-table-column :label="$t('common.image')" width="90">
                                <template #default="scope">
                                    <el-image 
                                        class="!w-14 !h-14 rounded-md me-2"
                                        :src="scope.row.image"
                                        :preview-src-list="[scope.row.image]"
                                        :initial-index="0"
                                        preview-teleported
                                        />
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('common.name')" sortable>
                                <template #default="scope">
                                    {{  formatName(scope.row.depth, scope.row.name) }}
                                </template>
                            </el-table-column>
                            <el-table-column prop="product_count" :label="$t('common.total_data', { data : $t('base.product')})" sortable />
                            
                            <el-table-column width="60" header-align="center">
                                <template #header>
                                    <Icon icon="flag:us-1x1"/>
                                </template>
                                <template #default="scope">
                                    <el-button size="small" @click.prevent="onEdit(scope.row, 'en')">
                                        <Icon :icon="scope.row.translations.length > 1 ? 'mingcute:edit-3-fill' : 'mingcute:plus-fill'"/>
                                    </el-button>
                                </template>
                            </el-table-column>
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
                    <div class="bg-teal-100 mb-4 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                        role="alert" v-if="form.locale == 'en'">
                        <div class="flex">
                            <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                                </svg></div>
                            <div>
                                <div class="font-bold">{{ $t('common.translation_english') }}</div>
                                <div class="text-sm">{{ form.origin }}</div>
                            </div>
                        </div>
                    </div>
                    <el-form-item :label="$t('common.name')" prop="name">
                        <el-input v-model="form.name" />
                    </el-form-item>
                    <el-form-item :label="$t('common.parent_data', {data : $t('base.category')})" prop="parent_id" v-if="form.locale == 'id'">
                        <select-product-category v-model="form.parent_id" />
                    </el-form-item>
                    <el-form-item :label="$t('common.description')" prop="description">
                        <el-input type="textarea" v-model="form.description" :row="4"/>
                    </el-form-item>
                    <el-form-item :label="$t('common.image')" prop="image" v-if="form.locale == 'id'">
                        <image-upload v-model="form.image" class="w-28 h-28"/>
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
        onMounted,
        getCurrentInstance
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
    import SelectProductCategory from '@/Components/Form/SelectProductCategory.vue';
    import ImageUpload from '@/Components/Form/ImageUpload.vue';
    import { useBaseUtil } from '@/Composables/baseComposable';

    const { objectToFormData } = useBaseUtil();

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
        const response = await axios.get("/product/category/data", {
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

    const formatName = (level, title) => {
        var depth = '';
        if(level){
            for (let i = 0; i <= level; i++) {
                depth += "—";
            }
        }
        return depth + ' ' + title;
    }
    

    const formRef = ref(null);
    const formShow = ref(false);
    const formTitle = ref('');
    const form = ref({
        id: null,
        origin : null,
        name: null,
        parent_id : null,
        description : null,
        locale : 'id',
        image : null
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
        formTitle.value = `${ t('common.create') } ${ t('base.category') }`;
        formShow.value = true;
        form.value.id = null;
        form.value.name = null;
        form.value.parent_id = null;
        form.value.description = null;
        form.value.locale = 'id';
        form.value.image = null;
    }

    const onEdit = (data, locale = 'id') => {
        formTitle.value = `${ t('common.edit') } ${ t('base.category') }`;
        formShow.value = true;
        form.value.id = data.id;
        form.value.parent_id = data.parent_id;
        form.value.image = data.image != '/images/placeholder/product.png' ? data.image : null ;
        form.value.locale = locale;
        if(locale == 'en'){
            form.value.origin = data.name;
            form.value.name = data.translations.length > 1? data.translations[1].name : null ;
            form.value.description = data.translations.length > 1 ? data.translations[1].description : null;
        }else{
            form.value.name = data.name;
            form.value.description = data.description;
        }

    }

    const onResetForm = () => {
        formShow.value = false;
        form.value.id = null;
        form.value.name = null;
        form.value.parent_id = null;
        form.value.description = null;
        form.value.locale = 'id';
        form.value.image = null;
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
                    const url = form.value.id ? `/product/category/${form.value.id}/update` :
                        '/product/category/store';
                    const method = 'post';

                    const formData = objectToFormData(form.value);

                    await axios({
                        method,
                        url,
                        data: formData,
                        headers: {
                            "Content-Type": "multipart/form-data"
                        },
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
                    console.log(error);
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
            axios.delete(`/product/category/${id}/delete`).then(() => {
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
