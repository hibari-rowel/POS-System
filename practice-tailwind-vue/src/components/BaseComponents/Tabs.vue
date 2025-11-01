<script setup lang="ts">
import { ref, provide } from "vue"

const props = defineProps({
    defaultTab: { type: String, required: true, }
});

const activeTab = ref(props.defaultTab);

provide("activeTab", activeTab);
provide("setActiveTab", (name: string) => (activeTab.value = name));
</script>

<template>
    <!-- navigation -->
    <div class="tab-navigation-container">
        <slot name="tabs"></slot>
    </div>

    <!-- content -->
    <div class="pt-4 px-4">
        <slot></slot>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.tab-navigation-container {
    @apply flex border-b border-gray-200 gap-0.5;
}
</style>