<script setup lang="ts">
import { useRoute } from 'vue-router';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const route = useRoute();
const recordID = route.params.id;
const header = { 
    title: 'Users',
    bread_crumbs: [
        {name: "Users", path: '/users'},
        {name: "Edit",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/users/show/' + recordID" class="btn-danger"> Cancel </router-link>
                        <button class="btn-primary"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="h-full py-4 mb-2 bg-white rounded-lg shadow">02</div>
        </template>
    </Base>
</template>

<style scoped></style>