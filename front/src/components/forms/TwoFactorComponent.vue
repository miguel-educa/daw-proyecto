<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore.js'
import UserTools from '@/tools/user.js'

const router = useRouter()
const uStore = useUserStore()

const authCode = ref('')
const showError = ref(false)

const isLoading = ref(false)

const emit = defineEmits(['close2FA'])

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})

/**
 * Controlador del formulario del doble factor de autenticación
 */
const twoFactorSubmitHandler = async () => {
  showError.value = false
  authCode.value = authCode.value.trim().replaceAll(' ', '')

  if (!/^\d{6}$/.test(authCode.value)) {
    showError.value = true
    return
  }

  isLoading.value = true
  const result = await UserTools.login({
    username: props.user.username,
    master_password: props.user.password,
    session_duration: props.user.sessionDuration,
    two_fa_code: authCode.value,
  })
  isLoading.value = false

  if (!result.id) {
    showError.value = true
    return
  }

  uStore.user = await UserTools.getUserInfo()
  router.push('/vault')
}

/**
 * Emite el evento de cierre del modal
 */
const close = () => {
  emit('close2FA', authCode.value)
}
</script>

<template>
  <div class="modal is-active">
    <div class="modal-background" @click="close"></div>
    <div class="modal-card">
      <!-- Cabecera -->
      <header class="modal-card-head p-5">
        <p class="modal-card-title">Doble factor de autenticación</p>
        <button class="delete is-large" aria-label="close" @click="close"></button>
      </header>

      <!-- Cuerpo -->
      <section class="modal-card-body">
        <div class="is-flex is-flex-direction-column is-align-items-center justify-content-center">
          <form @submit.prevent>
            <!-- Input -->
            <div class="field">
              <label class="label is-size-5-desktop">Código de autenticación</label>
              <div class="control has-icons-left">
                <input
                  type="text"
                  placeholder="Introduzca los 6 dígitos"
                  class="input input-field"
                  :class="{ 'input-field-incorrect': showError }"
                  v-model="authCode"
                  autofocus
                  required
                />
                <span class="icon is-left">
                  <i class="fa-solid fa-shield-halved"></i>
                </span>
              </div>
            </div>

            <!-- Error -->
            <p v-if="showError" class="help is-danger">*Código incorrecto</p>
            <button @click="twoFactorSubmitHandler" hidden></button>
          </form>
        </div>
      </section>

      <!-- Pie -->
      <footer class="modal-card-foot p-4">
        <div class="buttons is-centered">
          <button
            class="button"
            :class="{ 'is-loading': isLoading }"
            @click="twoFactorSubmitHandler"
          >
            Comprobar
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

label {
  font-weight: normal;
}
</style>
