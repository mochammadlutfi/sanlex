<template>
    <el-select v-model="value" 
    value-key="id" 
    class="w-100"
    filterable 
    clearable
    remote
    @change="selectChange"
    :placeholder="placeholder"
    :disabled="isDisabled"
    :loading="isLoading">
        <el-option
            v-for="item in dataList"
            :key="item.id"
            :label="item.name"
            :value="item"
        />
    </el-select>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';

const emit = defineEmits(['update:modelValue'])
// Define props
const props = defineProps({
    modelValue: {
        type: Object,
        default: null
    },
    placeholder: {
        type: String,
        default: ''
    },
    filter: {
        type: [String, Array],
    },
    type: {
        type: String,
        default: 'provinsi',
    },
    parent: {
        type: [String, Number],
    },
    hasParent: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    clearable: {
        type: Boolean,
        default: false,
    },
    multiple: {
        type: Boolean,
        default: false,
    },
});

const isDisabled = computed(() => {
    if (props.disabled) {
        return true;
    } else {
    if (props.hasParent) {
        return !props.parent;
    } else {
        return false;
    }
    }
});
// Define data
const dataList = ref([]);
const value = ref(props.modelValue);
const isLoading = ref(false);

// Watch for changes to modelValue
watch(() => props.modelValue, (newValue) => {
    value.value = newValue;
});

watch(() => props.parent, (newValue) => {
    if (newValue) {
        value.value = null;
        fetchData();
    }
});
// Fetch data on mounted
const fetchData = async () => {
    try {
        isLoading.value = true;

        const response = await axios.get("/base/daerah",{
          params: {
                type: props.type,
                parent: props.parent,
                filter: props.filter,
            },
        });
        if (response.status === 200) {
            dataList.value = response.data;
        }
        isLoading.value = false;
    } catch (error) {
    }
};

onMounted(async () => {
    if (!props.hasParent) {
        await fetchData();
    } else {
    if (props.parent) {
        await fetchData();
    }
    }
});

// Emit value change
const selectChange = (newValue) => {
    emit('update:modelValue', newValue);
};
</script>