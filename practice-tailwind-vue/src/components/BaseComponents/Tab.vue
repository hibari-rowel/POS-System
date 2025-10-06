<script setup lang="ts">
import { inject, computed } from "vue"

const activeTab = inject("activeTab");
const setActiveTab = inject("setActiveTab");

const props = defineProps({
    name: {type: String, required: true},
    label: {type: String, required: true},
});


const isActive = computed(() => activeTab.value === props.name)
</script>

<template>
    <template v-if="$slots.default">
        <div v-show="isActive">
            <slot></slot>
        </div>
    </template>

    <template v-else>
        <button class="tab-navigation" @click="setActiveTab(props.name)" :class="isActive ? 'active-tab-navigation' : 'inactive-tab-navigation'">
            {{ label }}
        </button>
    </template>
</template>

<style scoped>
</style>