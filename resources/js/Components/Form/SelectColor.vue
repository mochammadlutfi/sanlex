<template>
    <el-select-v2 v-model="value" 
        multiple 
        clearable
        :placeholder="$t('common.select')"
        filterable
        :options="dataList"
        :props="{
            label: 'name',
            value: 'value',
        }"
        value-key="id"
        @change="selectChange"
        class="w-full">
        <template #default="{ item }">
            <div class="flex items-center">
                <el-tag :color="item.value.hex" size="small" class="me-2" />
                <span>{{ item.value.code }} -{{ item.value.name }}</span>
            </div>
        </template>
        <template #tag>
            <el-tag v-for="v in value" :key="v.id" :color="v.hex" />
        </template>
    </el-select-v2>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';

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

const propsSelect = {
  label: 'name',
  value: 'id',
}

// Define data
const dataList = ref([]);
const value = ref(props.modelValue);
const isLoading = ref(false);

// Watch for changes to modelValue
watch(() => props.modelValue, (newValue) => {
    value.value = newValue;
});

// Fetch data on mounted
const fetchData = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get("/product/color/data");
        if (response.status === 200) {
            // dataList.value = response.data;

            response.data.forEach((v) => {
                dataList.value.push({
                    id : v.id,
                    name : `${ v.code } - ${ v.name }`,
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

// Emit value change
const selectChange = (newValue) => {
    emit('update:modelValue', value.value);
    // value.value = newValue;
};
</script>