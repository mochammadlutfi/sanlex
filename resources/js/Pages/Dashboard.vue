<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="content">
            <el-row :gutter="20">
                <el-col :span="8">
                    <el-card>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                                    <i class="mgc_suitcase_line el-icon text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h5 class="mb-1">Total Project</h5>
                                <p>85</p>
                            </div>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="8">
                    <el-card>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                                    <i class="mgc_suitcase_line el-icon text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h5 class="mb-1">Project Ready</h5>
                                <p class="font-semibold">85</p>
                            </div>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="8">
                    <el-card>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                                    <i class="mgc_suitcase_line el-icon text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h5 class="mb-1">Total Activity</h5>
                                <p>85</p>
                            </div>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </div>
        <!-- <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div> -->
    </AuthenticatedLayout>
</template>
