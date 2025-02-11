<template>
    <div>
        <el-row :gutter="16">
            <el-col :md="10">
                <el-input type="text" v-model="data.lat" placeholder="Latitude"/>
            </el-col>
            <el-col :md="10">
                <el-input type="text" v-model="data.lng" placeholder="Longitude"/>
            </el-col>
            <el-col :md="4">
                <el-button class="w-full" type="primary" @click="openMap">
                    <Icon icon="fluent:location-ripple-24-filled"/>
                </el-button>
            </el-col>
        </el-row>
        <el-dialog v-model="showMap" title="Temukan Koordinat">

        </el-dialog>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import {Icon} from '@iconify/vue';

const data = ref({
    lat : null,
    lng : null,
});

const showMap = ref(false);
const openMap = () =>{
    showMap.value = true;
}


</script>