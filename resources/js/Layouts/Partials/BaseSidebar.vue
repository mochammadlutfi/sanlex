<template>
    <div id="page-sidebar">

        <div class="sidebar-content justify-end">
            <!-- Sidenav Brand Logo -->
            <Link href="/dashboard" class="logo">
                <!-- Light Brand Logo -->
                <div class="logo-light">
                    <img src="/images/logo/logo.png" class="logo-lg h-10 smini-hidden" alt="Light logo">
                    <!-- <img :src="$page.props.settings.logo_light" class="logo-lg h-10 smini-hidden" alt="Light logo"> -->
                    <!-- <img :src="$page.props.settings.logo_light_sm" class="logo-sm smini-visible" alt="Small logo"> -->
                </div>

                <!-- Dark Brand Logo -->
                <div class="logo-dark">
                    <img src="/images/logo/logo.png" class="logo-lg h-10 smini-hidden" alt="Dark logo">
                    <!-- <img :src="$page.props.settings.logo_dark" class="logo-lg h-10 smini-hidden" alt="Dark logo">
                    <img :src="$page.props.settings.logo_dark_sm" class="logo-sm smini-visible" alt="Small logo"> -->
                </div>
            </Link>
            
            <el-scrollbar class="md:h-[550px] p-4">
                <!-- <div class="content-side content-side-full pb-5"> -->
                    <base-navigation :nodes="$page.props.menu"></base-navigation>
                <!-- </div> -->
            </el-scrollbar>
        </div>
    </div>
</template>
<script setup>
import { useAppBaseStore } from '@/Stores/base';
import { ref, computed } from 'vue';
import { useDark, useToggle } from "@vueuse/core";
import BaseNavigation from './BaseNavigation.vue';
import { Link } from '@inertiajs/vue3';

const appBase = useAppBaseStore();
const darkMode = useDark();

const app = computed(() => appBase.app);

const isDark = computed(() => (darkMode.value ? 'solar:sun-linear' : 'solar:moon-linear'));

const isCollapse = computed(() => appBase.settings.sidebarMini);

</script>
