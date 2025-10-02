<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps({
    show: { type: Boolean, required: true },
    size: { type: String, default: '' },
});

const emit = defineEmits(["close"]);
</script>

<template>
    <Transition name="fade">
        <div v-show="show" class="modal-container-bg">
            <Transition name="slide">
                <div v-show="show" class="flex flex-col gap-2 bg-white rounded-lg shadow-lg w-full relative" :class="size">
                    <slot name="modal-content"></slot>
                </div>
            </Transition>
        </div>
    </Transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
}

.slide-enter-active, .slide-leave-active {
  transition: transform 0.5s cubic-bezier(.2,.8,.2,1), opacity 0.2s ease;
}
.slide-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}
.slide-enter-to {
  transform: translateY(0);
  opacity: 1;
}
.slide-leave-from {
  transform: translateY(0);
  opacity: 1;
}
.slide-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}
</style>