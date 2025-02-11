<template>
    <header id="page-header">
        <div class="content-header">
            <div class="flex gap-3 items-center">
                <!-- Sidenav Menu Toggle Button -->
                <layout-modifier tag="button" id="button-toggle-menu" action="sidebarMiniToggle" class="hidden md:block nav-link p-2 me-auto">
                    <span class="sr-only">Menu Toggle Button</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <Icon icon="solar:menu-dots-square-bold" class="text-2xl"/>
                    </span>
                </layout-modifier>

                
                <layout-modifier tag="button" id="button-toggle-menu" action="sidebarToggle" class="md:hidden nav-link p-2">
                    <span class="sr-only">Menu Toggle Button</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <i class="mgc_menu_line text-xl"></i>
                    </span>
                </layout-modifier>
            </div>

            <div class="flex gap-3 items-center">
                

                <!-- Language Dropdown Button -->
                <div class="relative"></div>

                <!-- Light/Dark Toggle Button -->
                <div class="flex">
                    <button id="light-dark-mode" type="button" class="nav-link p-2" @click.prevent="toggleDark()">
                        <span class="sr-only">Light/Dark Mode</span>
                        <span class="flex items-center justify-center h-6 w-6">
                            <Icon :icon="isDark" class="text-2xl"/>
                        </span>
                    </button>
                </div>

                <!-- Profile Dropdown Button -->
                <div class="relative">
                    <el-dropdown trigger="click" @command="handleCommand">
                        <button data-fc-type="dropdown" data-fc-placement="bottom-end" type="button">
                            <div class="flex">
                                <img :src="$page.props.auth.user.image" alt="user-image" class="rounded-md h-10">
                                <div class="md:block hidden my-auto ml-2 text-left">
                                    <div class="font-semibold fs-md mb-0 leading-none text-[#536485] ">{{ $page.props.auth.user.name }}</div>
                                    <span class="opacity-[0.7] fs-xs font-normal text-[#536485] block">{{ $page.props.auth.user.email }}</span>
                                </div>
                            </div>
                        </button>
                        <template #dropdown>
                            <el-dropdown-menu class="w-44 p-2 rounded-md">
                                <el-dropdown-item command="profile.edit">
                                    <Icon icon="solar:user-linear" class="text-md mr-4"/>
                                    <span>Profile</span>
                                </el-dropdown-item>
                                <el-dropdown-item command="profile.password">
                                    <Icon icon="solar:lock-password-linear" class="text-md mr-4"/>
                                    <span>Password</span>
                                </el-dropdown-item>
                                <el-dropdown-item command="logout">
                                    <Icon icon="solar:exit-linear" class="text-md mr-4"/>
                                    <span>Log Out</span>
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </div>
            </div>
        </div>
    </header>
</template>
<script setup>
import LayoutModifier from '@/Components/LayoutModifier.vue';
import { useAppBaseStore } from '@/Stores/base';
import { useDark, useToggle } from "@vueuse/core";
import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';
import { router } from '@inertiajs/vue3'

const darkMode = useDark();
const locales = computed(() => useAppBaseStore().locales);

const toggleDark = useToggle(darkMode);

const isDark = computed(() => (darkMode.value ? 'solar:sun-linear' : 'solar:moon-linear'));

const handleCommand = (command) =>{
    // alert(command);
    if(command == 'logout'){
        router.post(route(command));
    }else{
        router.get(route(command))
    }
}

const logout = async () => {

};


</script>
