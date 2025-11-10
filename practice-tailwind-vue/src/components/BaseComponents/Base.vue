<script setup lang="ts">
import { computed, reactive } from 'vue';
import { useAuthStore } from "@/stores/auth.js";

import Sidebar from '@/components/BaseComponents/Sidebar.vue';
import adminNavigations from '../../lib/navigations/admin.ts';
import staffNavigations from '../../lib/navigations/staff.ts';

const authStore = useAuthStore();

const logout = () => {
    localStorage.removeItem('is_open');
    authStore.logout();
}

const roleNavMap = {
    admin: adminNavigations,
    staff: staffNavigations
};

const navLinks = reactive({
    main_links: roleNavMap[authStore.user.role] || [],
    bottom_link: {
        name: 'Logout',
        img_src: '/icons/logout.svg',
        action: logout
    }
});

</script>

<template>
    <div class="base-container">
        <Sidebar :nav_links="navLinks" />

        <div class="content-container">
            <div class="content-topbar">
                <div class="flex items-center p-2 rounded-full">
                    <slot name="topbar-content"></slot>
                </div>

                <div class="flex gap-4 items-center h-full mr-1.5">
                    <button class="flex items-center justify-center shrink-0 h-10 rounded-full cursor-pointer border-3 border-transparent transition-all duration-250 ease-in-out hover:shadow-sm hover:border-blue-500">
                        <img src="/icons/settings.svg" class="p-0 m-0 h-full active:bg-blue-200 bg-white rounded-full group-hover:shadow-md" alt=""></img>
                    </button>
                </div>
            </div>
            
            <div class="main-content hide-scrollbar">
                <slot name="main-content"></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>
.hide-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;     /* Firefox */
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;  /* Chrome, Safari, Opera */
}
</style>