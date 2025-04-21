<script setup>
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'

const emit = defineEmits(['closePasswordHistory'])

const pStore = usePasswordStore()
const { history: passHistory } = storeToRefs(pStore)

const cleanHistoryHandler = () => {
  pStore.clearHistory()
  emit('closePasswordHistory')
}
</script>

<template>
  <div class="modal is-active">
    <div class="modal-background" @click="emit('closePasswordHistory')"></div>
    <div class="modal-card">
      <!-- Cabecera -->
      <header class="modal-card-head p-5">
        <p class="modal-card-title">Historial de contraseñas</p>
        <button
          class="delete is-large"
          aria-label="close"
          @click="emit('closePasswordHistory')"
        ></button>
      </header>

      <!-- Cuerpo -->
      <section class="modal-card-body">
        <div class="is-flex is-flex-direction-column is-align-items-center justify-content-center">
          <template v-if="passHistory.length > 0">
            <template v-for="(pass, index) in passHistory" :key="pass.pass">
              <div class="box is-max-desktop pass-history-item">
                <p class="pass-history-title title code-font is-5 is-size-6-mobile">
                  {{ pass.pass }}
                </p>
                <p
                  class="pass-history-subtitle has-text-centered subtitle is-6 is-size-7-mobile mt-3"
                >
                  {{ index }}. {{ new Date(pass.timestamp).toLocaleString() }}
                </p>
              </div>
            </template>
          </template>
          <template v-else>
            <p class="subtitle is-6 has-text-centered">
              No hay contraseñas almacenadas en el historial
            </p>
          </template>
        </div>
      </section>

      <!-- Pie -->
      <footer class="modal-card-foot p-4">
        <div class="buttons is-centered">
          <button class="button danger" @click="cleanHistoryHandler">
            <span class="icon-text">
              <span class="icon">
                <i class="fas fa-arrows-rotate"></i>
              </span>
              <span>Vaciar</span>
            </span>
          </button>
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
  color: var(--modal-body-text-color);
}

.button {
  background-color: var(--modal-button-background-color);
  color: var(--modal-button-text-color);
  border: none;
}

.modal-card-title {
  color: var(--modal-header-text-background-color);
  width: 90%;
}
</style>
