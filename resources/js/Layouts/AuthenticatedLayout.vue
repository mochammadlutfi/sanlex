<template>
    <el-config-provider>
        <Head>
            <title>{{ title }}</title>
        </Head>
        <div id="page-wrapper" :class="classContainer">
            <base-sidebar/>

            <base-header/>
            <main id="page-container">
                <slot />
            </main>

        </div>
    </el-config-provider>
</template>

<script setup>
    import BaseHeader from './Partials/BaseHeader.vue';
    import BaseSidebar from './Partials/BaseSidebar.vue';
    import { computed, defineProps, onMounted } from 'vue';
    import { useAppBaseStore } from '@/Stores/base';
    import { Head } from '@inertiajs/vue3';
    import { useAppSettingsStore } from '@/Stores/setting';
    import { ElLoading } from 'element-plus';

    defineProps({
        title : {
            type : String,
            default : ""
        }
    });

    const appBase = useAppBaseStore();
    const appSetting = useAppSettingsStore();
    // const loading = computed(() => appSetting.loading);

    const classContainer = computed(() => ({
        'sidebar-r': appBase.layout.sidebar && !appBase.settings.sidebarLeft,
        'sidebar-mini': appBase.layout.sidebar && appBase.settings.sidebarMini,
        'sidebar-o': appBase.layout.sidebar && appBase.settings.sidebarVisibleDesktop,
        'sidebar-o-xs': appBase.layout.sidebar && appBase.settings.sidebarVisibleMobile,
        'sidebar-dark': appBase.layout.sidebar && appBase.settings.sidebarDark,
        'side-overlay-o': appBase.layout.sideOverlay && appBase.settings.sideOverlayVisible,
        'side-overlay-hover': appBase.layout.sideOverlay && appBase.settings.sideOverlayHoverable,
        'enable-page-overlay': appBase.layout.sideOverlay && appBase.settings.pageOverlay,
        'page-header-fixed': appBase.layout.header && appBase.settings.headerFixed,
        'page-header-dark': appBase.layout.header && appBase.settings.headerDark,
        'main-content-boxed': appBase.settings.mainContent === 'boxed',
        'main-content-narrow': appBase.settings.mainContent === 'narrow',
        'rtl-support': appBase.settings.rtlSupport,
        'side-trans-enabled': appBase.settings.sideTransitions,
        'side-scroll': true
    }));
    
    onMounted(() => {
        // appSetting.loadSettings();
    });
</script>