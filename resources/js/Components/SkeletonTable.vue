<template>
    <div class="flex justify-between items-center p-4">
        <el-skeleton-item variant="rect" class="w-20 h-8"></el-skeleton-item>
        <div class="flex">
            <el-skeleton-item variant="rect" class="w-48 h-8 me-2"></el-skeleton-item>
            <el-skeleton-item variant="rect" class="w-28 h-8"></el-skeleton-item>
        </div>
    </div>
    <el-row :gutter="16" class="border-t border-b border-gray-200 px-4 py-2" v-for="i in 5" :key="i">
        <el-col :md="1">
            <el-skeleton-item variant="rect" class="w-4 h-4"></el-skeleton-item>
        </el-col>
        <el-col :md="5">
            <el-skeleton-item variant="rect" class="w-full h-4"></el-skeleton-item>
        </el-col>
        <el-col :md="4">
            <el-skeleton-item variant="rect" class="w-full h-4"></el-skeleton-item>
        </el-col>
        <el-col :md="4">
            <el-skeleton-item variant="rect" class="w-full h-4"></el-skeleton-item>
        </el-col>
        <el-col :md="4">
            <el-skeleton-item variant="rect" class="w-full h-4"></el-skeleton-item>
        </el-col>
        <el-col :md="4">
            <el-skeleton-item variant="rect" class="w-full h-4"></el-skeleton-item>
        </el-col>
        <el-col :md="2">
            <el-skeleton-item variant="rect" class="w-full h-4"></el-skeleton-item>
        </el-col>
    </el-row>
</template>