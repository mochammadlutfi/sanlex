<template>
    <el-select v-model="value" value-key="id" 
    class="w-100"
    filterable 
    clearable 
    remote
    @change="selectChange"
    autocomplete="off"
    :disabled="disabled"
    :placeholder="placeholder"
    :loading="isLoading">
        <el-option
            v-for="item in dataList"
            :key="item.id"
            :label="item.name"
            :value="item.id"
        />
    </el-select>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';

const emit = defineEmits(['update:modelValue'])
// Define props
const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    placeholder : {
        type : String,
        default : ''
    },
    disabled : {
        type : Boolean,
        default : false
    }
});

// Define data
const dataList = ref([]);
const value = ref(props.modelValue);
const isLoading = ref(false);

const getLabel = (val) => {
    if(val.short_name){
        return `${val.name} (${val.short_name})`;
    }else{
        return val.name;
    }
};

// Watch for changes to modelValue
watch(() => props.modelValue, (newValue) => {
    value.value = newValue;
});

// Fetch data on mounted
const fetchData = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get("/branch/data");
        if (response.status === 200) {
            dataList.value = response.data;
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
    value.value = newValue;
    emit('update:modelValue', newValue);
};
</script>