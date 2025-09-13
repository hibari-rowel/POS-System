<script setup lang="ts">
import _ from 'lodash';

defineProps({
    'header': Object,
});
</script>

<template>
    <div class="flex flex-col gap-2 py-2 mb-4">
        <div class="flex flex-row justify-between">
            <span class="font-bold text-3xl items-center"> {{ header?.title }} </span>
            <slot name="right-side"></slot>
        </div>

        <nav class="bread-crumb">
            <ol class="bread-crumb-list">
                <li class="bread-crumb-list-item">
                    <router-link :to="'/dashboard'" class="flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <img src="/icons/home.svg" class="w-4 h-4 mr-1" alt="home"/>
                        Home
                    </router-link>
                </li>

                <li class="bread-crumb-list-item" v-for="(bread_crumb, index) in header?.bread_crumbs">
                    <img src="/icons/arrow_right.svg" class="w-5 h-5 mx-1" alt="arrow right"/>

                    <span v-if="_.isEmpty(bread_crumb.path)" class="flex items-center text-sm text-gray-900 font-semibold">
                        {{ bread_crumb.name }}
                    </span>
                    
                    <router-link v-else :to="bread_crumb.path" class="flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        {{ bread_crumb.name }}
                    </router-link>
                </li>
            </ol>
        </nav>
    </div>
</template>

<style scoped>
</style>