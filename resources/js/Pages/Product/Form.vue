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
                label-position="top"
                v-loading="formLoading">
                    
                    <div class="p-4">
                        <el-row :gutter="16">
                            <el-col :md="4">
                                <el-form-item :label="$t('common.image')" prop="image">
                                    <image-upload v-model="form.image"  class="w-28 h-28"/>
                                </el-form-item>
                            </el-col>
                            <el-col :md="20">
                                <el-form-item :label="$t('common.name')" prop="name">
                                    <el-input v-model="form.name"/>
                                </el-form-item>
                                <el-row :gutter="20">
                                    <el-col :md="12">
                                        <el-form-item :label="$t('base.category')" prop="category_id">
                                            <select-product-category v-model="form.category_id"/>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :md="12">
                                        <el-form-item :label="$t('base.brand')" prop="brand_id">
                                            <select-brand v-model="form.brand_id"/>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </el-col>
                        </el-row>

                        <el-form-item :label="$t('common.description')">
                            <el-input
                                v-model="form.description"
                                placeholder="Masukan Deskripsi"
                                show-word-limit
                                :rows="4"
                                type="textarea"
                            />
                        </el-form-item>

                        <div class="text-sm	mb-2 neutral-600">Spesifikasi</div>
                        <el-table :data="form.spesification" border class="w-full mb-4">
                            <el-table-column  label="Label">
                                <template #default="scope">
                                    <el-input v-model="scope.row.title" placeholder="Masukan Deskripsi"/>
                                </template>
                            </el-table-column>
                            <el-table-column  label="Nilai">
                                <template #default="scope">
                                    <el-input v-model="scope.row.value" placeholder="Masukan Nilai"/>
                                </template>
                            </el-table-column>
                            <el-table-column width="70" header-align="center" align="center">
                                <template #header>
                                    <el-button @click="addRow" size="small" type="primary">
                                        <Icon icon="fluent:add-28-filled"/>
                                    </el-button>
                                </template>
                                <template #default="scope">
                                    <el-button @click="removeRow(scope.$index)" size="small">
                                        <Icon icon="fluent:dismiss-24-filled"/>
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                        <el-row :gutter="16">
                            <el-col :md="12">
                                <el-form-item label="Youtube URL" prop="youtube_url">
                                    <el-input v-model="form.youtube_url"/>
                                </el-form-item>
                            </el-col>
                            <el-col :md="12">
                                <el-form-item label="Status Penayangan">
                                    <el-radio-group v-model="form.status">
                                        <el-radio :value="0">Aktif</el-radio>
                                        <el-radio :value="1">Tidak Aktif</el-radio>
                                    </el-radio-group>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-row :gutter="16">
                            <el-col :md="12">
                                <el-form-item :label="$t('base.packaging')">
                                    <select-packaging v-model="form.packaging" @change="updateVariants"/>
                                </el-form-item>
                            </el-col>
                            <el-col :md="12">
                                <el-form-item :label="$t('base.color')">
                                    <select-color v-model="form.color" @change="updateVariants"/>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        
                        <el-table :data="form.variant" border
                        class="w-full mb-4" id="variant"
                        row-key="name"
                        @selection-change="onSelectionChange"
                        :span-method="rowSpanMethod"
                        v-if="form.packaging">
                            <el-table-column :label="$t('base.packaging')" width="300">
                                <template #default="scope">
                                    {{ scope.row.packaging.name }}
                                </template>
                            </el-table-column>
                            <el-table-column  :label="$t('base.color')" v-if="form.color.length">
                                <template #default="scope" >
                                    {{ scope.row.color.code }} -
                                    {{ scope.row.color.name }}
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('common.code')">
                                <template #default="scope">
                                    <el-form-item :prop="'variant.' + scope.$index + '.code'" :rules="[{
                                        required: true,
                                        message: t('validation.required', {
                                            attribute: t('common.code')
                                        }),
                                        trigger: 'blur'
                                    }]">
                                    <el-input v-model="scope.row.code"/>
                                </el-form-item>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>

                    <div class="p-4">
                        <el-button native-type="submit" type="primary">
                            <Icon icon="mingcute:check-fill" class="me-2"/>
                            {{ $t('common.save') }}
                        </el-button>
                    </div>
                </el-form>
            </el-card>
        </div>
    </base-layout>
</template>

<script setup>
import { Icon } from '@iconify/vue';
import { ref, onMounted, watch, nextTick } from 'vue';
import { trans as t } from 'laravel-vue-i18n';
import { debounce } from 'lodash';
import ImageUpload from '@/Components/Form/ImageUpload.vue';
import SelectProductCategory from '@/Components/Form/SelectProductCategory.vue';
import SelectBrand from '@/Components/Form/SelectBrand.vue';
import SelectPackaging from '@/Components/Form/SelectPackaging.vue';
import SelectColor from '@/Components/Form/SelectColor.vue';
import { useBaseUtil } from '@/Composables/baseComposable';

const { objectToFormData } = useBaseUtil();

const props = defineProps({
    data : {
        type : Object,
        default : null
    }
});

const title = ref('');
const formRef = ref(null);
const formLoading = ref(false);
const form = ref({
    id : null,
    locale : 'id',
    name : null,
    category_id : null,
    brand_id : null,
    description : null,
    image : null,
    spesification : [
        {
            title : null,
            value : null
        }
    ],
    youtube : null,
    packaging : [],
    status: 1,
    color : [],
    variant : [],
    removed_variant : [],
});

// watch(() => form.value.packaging, (newVal, oldVal) => {
//     if(!isFirstValue.value){
//         initVariant();
//         storeExistingValues();
//         if(newVal.length < oldVal.length){
//             restoreExistingValues();
//         }
//     }
// });

// watch(() => form.value.color, (newVal, oldVal) => {
//     initVariant();
//     restoreExistingValues();
// });

const formRules = ref({
    name: [{
        required: true,
        message: t('validation.required', {
            attribute: t('common.name')
        }),
        trigger: 'blur'
    }, ],
    category_id: [
        {
            required: true,
            message: t('validation.required', { attribute: t('base.category')}),
            trigger: 'change'
        }
    ],
    brand_id: [
        {
            required: true,
            message: t('validation.required', { attribute: t('base.brand')}),
            trigger: 'change'
        }
    ],
});
const addRow = () => {
    form.value.spesification.push({
        title : null,
        value : null,
    });
}
const removeRow = (index) => {
    form.value.spesification.splice(index, 1);
}
const isFirstValue = ref(false);
const existingVariant = ref({});

const updateVariants = () => {
    const newVariants = [];
    const newVariantKeys = new Set();
    const oldVariantMap = new Map();

    // Simpan semua varian lama dalam Map
    form.value.variant.forEach(v => {
        const key = `${v.packaging_id}-${v.color_id}`;
        oldVariantMap.set(key, v);
    });

    // Loop kombinasi baru
    form.value.packaging.forEach(var1 => {
        if (form.value.color.length) {
            form.value.color.forEach(var2 => {
                const key = `${var1.id}-${var2.id}`;
                newVariantKeys.add(key);

                newVariants.push(oldVariantMap.get(key) || {
                    id: null,
                    packaging_id: var1.id,
                    packaging: var1,
                    name: `${var1.id}-${var2.id}`,
                    color_id: var2.id,
                    color: var2,
                    code: null,
                });
            });
        } else {
            const key = `${var1.id}-null`;
            newVariantKeys.add(key);

            newVariants.push(oldVariantMap.get(key) || {
                id: null,
                packaging_id: var1.id,
                packaging: var1,
                name: `${var1.id}-`,
                color_id: null,
                color: null,
                code: null,
            });
        }
    });

    // Cari varian yang harus dihapus
    [...oldVariantMap.values()]
        .filter(v => v.id !== null && !newVariantKeys.has(`${v.packaging_id}-${v.color_id}`))
        .forEach(v => {
            if (!form.value.removed_variant.includes(v.id)) {
                form.value.removed_variant.push(v.id);
            }
        });

    form.value.variant = newVariants;
};

onMounted(() => {
    title.value = props.data ? 'Ubah Produk' : 'Buat Produk Baru';

    if(props.data){
        form.value.id = props.data.id;
        form.value.name = props.data.name;
        form.value.category_id = props.data.category_id;
        form.value.brand_id = props.data.brand_id;
        form.value.description = props.data.description;
        form.value.image = props.data.image;
        form.value.youtube = props.data.youtube;

        if(props.data.spesification.length){
            form.value.spesification = [];
            props.data.spesification.forEach((v) => {
                form.value.spesification.push({
                    title : v.title,
                    value : v.value
                });
            });
        }

        form.value.packaging = props.data.packaging;
        form.value.color = props.data.color;
        isFirstValue.value = true;
        
        if(props.data.variant.length){
            form.value.variant = [];
            props.data.variant.forEach((v) => {
                form.value.variant.push({
                    id : v.id,
                    packaging_id : v.packaging_id,
                    packaging : v.packaging,
                    name : v.name,
                    color_id : v.color_id,
                    color : v.color,
                    code : v.code,
                });
            });
        }
        storeExistingValues();
        nextTick(() => { 
            isFirstValue.value = false; 
        });
    }
});

const storeExistingValues = debounce(() => {
    existingVariant.value = {};
    form.value.variant.forEach(obj => {
        const key = `${obj.packaging_id}-${obj.color_id}`;
        existingVariant.value[key] = {
            code: obj.code
        };
    });
}, 300);

const restoreExistingValues = () => {
    form.value.variant.forEach(obj => {
        const key = `${obj.packaging_id}-${obj.color_id}`;
        if (existingVariant.value[key]) {
            obj.code = existingVariant.value[key].code;
        }
    });
}
const onSelectionChange = (v) => {
    console.log(v);
}


const rowSpanMethod = ({
  row,
  column,
  rowIndex,
  columnIndex,
}) => {
    if (columnIndex === 0) {
        if (rowIndex === 0 || row.packaging_id !== form.value.variant[rowIndex - 1].packaging_id) {
            let rowVal = 1;
            for (let i = rowIndex + 1; i < form.value.variant.length; i++) {
                if (form.value.variant[i].packaging_id === row.packaging_id) {
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
                const url = form.value.id ? `/product/${form.value.id}/update` : '/product/store';
                const method = form.value.id ? 'post' : 'post';

                const formData = objectToFormData(form.value);

                const response = await axios({
                    method,
                    url,
                    data: formData,
                    headers: {
                        "Content-Type": "multipart/form-data"
                    },
                });
                if (response.status === 200) {
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