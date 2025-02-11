<template>
    <el-input 
    ref="inputRef"
    v-model="formattedValue"
    @change="onInput" class="w-full"/>
</template>

<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import { useCurrencyInput } from 'vue-currency-input';
import { useAppSettingsStore } from '@/Stores/setting';


const emit = defineEmits(['update:modelValue']);
const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    placeholder : {
        type : String,
        default : ''
    },
    max : {
        type : Number,
    }
});

const useAppSetting = useAppSettingsStore();

const input = ref('');

watch(
  () => useAppSetting.settings.currency, // Watch specific property
  (newCurrency, oldCurrency) => {
    // console.log('Currency changed from', oldCurrency, 'to', newCurrency);
    // Perform actions when currency changes
    if(newCurrency){
        options.value.currency = newCurrency.code;
        setOptions(options.value);
    }
  }
);

// Options untuk vue-currency-input
const options = ref({
  currency: 'USD',
  locale: 'en-US',
  hideCurrencySymbolOnFocus: false,
  hideGroupingSeparatorOnFocus: false,
  precision: 0,
  valueRange: { min: 0, max: props.max },
});

watch(
  () => props.modelValue,
  (value) => {
    setValue(value);
  }
);

const { inputRef, formattedValue, setOptions, setValue } = useCurrencyInput(options.value);

watch(() => props.modelValue, (newValue) => {
    input.value = newValue;
});

const onInput = () => {
    emit('update:modelValue', input.value);
}

onMounted(() => {
    useAppSetting.loadSettings();
    // options.value.currency = useAppSetting.settings.currency ? useAppSetting.settings.currency.code : 'USD';
});

</script>