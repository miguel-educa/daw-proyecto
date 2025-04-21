<script setup>
import { ref, onMounted } from 'vue'

// Variables de entrada
const props = defineProps({
  isActive: {
    type: Boolean,
    default: false,
  },
  options: {
    type: Object,
    default: () => ({}),
  },
})

const isVisible = ref(false)
const emits = defineEmits(['finish'])

// Opciones
const { type = 'is-info', timeIn = 3000, timeOut = 1000 } = props.options

onMounted(async () => {
  // Mostrar y ocultar durante un tiempo
  isVisible.value = true

  setTimeout(() => {
    isVisible.value = false
    setTimeout(() => emits('finish'), timeOut)
  }, timeIn)
})
</script>

<template>
  <div
    class="notification"
    :class="[type, { 'slide-in': isVisible, 'slide-out': !isVisible }]"
    @animationend="handleAnimationEnd"
  >
    <slot name="body" />
  </div>
</template>

<style scoped>
.notification {
  position: fixed;
  left: 50%;
  bottom: 25px;
  transform: translateX(-50%);
  box-shadow: var(--box-shadow-8-8-10);
  z-index: 9999;
  transition:
    transform 0.3s ease-out,
    opacity 0.5s ease-out;
}

.slide-out {
  transform: translateX(-50%) translateY(100%);
  opacity: 0;
}
</style>
