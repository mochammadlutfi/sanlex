<template>
    <el-select-v2
        v-model="value"
        multiple 
        clearable
        :placeholder="$t('common.select')"
        filterable
        :options="options"
        :props="{
            label: 'name',
            value: 'value',
        }"
        value-key="id"
        :loading="isLoading"
        @change="selectChange"
        class="w-full">

        <template #footer>
            <el-button v-if="!isAdding" class="w-full" type="primary" @click="onAddOption">
                Tambah Kemasan
            </el-button>
            <div v-else class="select-footer">
                <el-form ref="formPackagingRef" :model="form" @submit.prevent="onSubmitOption"
                v-loading="formLoading">
                        <el-form-item prop="name" :rules="[{
                            required: true,
                            message: t('validation.required', {
                                attribute: t('common.name')
                            }),
                            trigger: 'blur'
                        }]">
                            <el-input v-model="form.name" />
                        </el-form-item>
                    <div>
                        <el-button @click="onCancelOption">Batal</el-button>
                        <el-button type="primary" native-type="submit">
                            Tambah
                        </el-button>
                    </div>
                </el-form>
            </div>
        </template>
        </el-select-v2>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { trans as t } from 'laravel-vue-i18n';

const emit = defineEmits(['update:modelValue'])
// Define props
const props = defineProps({
    modelValue: {
        type: Array,
        default: []
    },
    placeholder : {
        type : String,
        default : ''
    }
});

// Define data
const value = ref(props.modelValue);
const isLoading = ref(false);
const options = ref([]);
const form = ref({
    name : '',
});
const formPackagingRef = ref();
const isAdding = ref(false);
const formLoading = ref(false);
// Watch for changes to modelValue
watch(() => props.modelValue, (newValue) => {
    value.value = newValue;
});


// Fetch data on mounted
const fetchData = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get("/product/packaging/data");
        if (response.status === 200) {
            // dataList.value = response.data;
            options.value = [];
            response.data.forEach((v) => {
                options.value.push({
                    id : v.id,
                    name : v.name,
                    value : v
                });

            });
        }
        isLoading.value = false;
    } catch (error) {
    }
};

onMounted(() => {
    fetchData();
});

const onAddOption = () => {
    isAdding.value = true;
}

const onCancelOption = () => {
    form.value.name = '';
    isAdding.value = false;
}

const onSubmitOption = async () => {
    if (!formPackagingRef.value) return;
    formPackagingRef.value.validate(async (valid) => {
        if (valid) {
            formLoading.value = true;
            await axios.post('/product/packaging/store', form.value)
            .then(response => {
                fetchData();
                onCancelOption();
            })
            .catch(error => {
                ElMessage({
                    message: t('message.error_server'),
                    type: 'error',
                });
            });
            formLoading.value = false;
        } else {
            ElMessage({
                message: t('message.error_input'),
                type: 'error',
            });
        }
    });
}

// Emit value change
const selectChange = () => {
    emit('update:modelValue', value.value);
    onCancelOption();
};
</script>