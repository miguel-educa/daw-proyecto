<script setup>
defineProps({
  modalTitle: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['closeModal'])
</script>

<template>
  <div class="modal is-active">
    <div class="modal-background" @click="emit('closeModal')"></div>
    <div class="modal-card">
      <header v-if="modalTitle" class="modal-card-head p-5">
        <p class="modal-card-title">{{ modalTitle }}</p>
        <button class="delete" aria-label="close" @click="emit('closeModal')"></button>
      </header>
      <section class="modal-card-body">
        <div class="is-flex is-flex-direction-column is-align-items-center justify-content-center">
          <slot name="modal-body" />
        </div>
      </section>
      <footer class="modal-card-foot p-4">
        <div class="buttons is-centered">
          <button class="button is-danger" @click="emit('closeModal')">Cerrar</button>
        </div>
      </footer>
    </div>
  </div>
</template>

<style scoped>
.modal-card {
  max-height: 85vh;
  max-width: 75vw;
  z-index: 20;
}

.modal-card-head {
  background-color: var(--modal-header-footer-background-color);
  box-shadow: var(--box-shadow-0-5-10);
}

.modal-card-foot {
  justify-content: center;
  background-color: var(--modal-header-footer-background-color);
}

.modal-card-body {
  background-color: var(--modal-body-background-color);
}

.button {
  background-color: var(--modal-button-background-color);
  color: var(--modal-button-text-color);
}

.modal-card-title {
  color: var(--modal-header-text-background-color);
  width: 90%;
}
</style>
