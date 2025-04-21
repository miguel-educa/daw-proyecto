<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import config from '@/config.js'
import UserTools from '@/tools/user.js'
import TwoFactorComponent from '@/components/forms/TwoFactorComponent.vue'
import '@/assets/css/forms.css'

const router = useRouter()

const username = ref('')
const password = ref('')
const sessionDuration = ref(config.sessionDuration[0].value)

const showPassword = ref(false)
const showError = ref(false)
const errorMessage = ref('')
const isLoading = ref(false)
const twoFactorModalActive = ref(false)

/**
 * Controlador del formulario de inicio de sesión
 */
const LoginSubmitHandler = async () => {
  showError.value = false

  // Comprobar campos obligatorios
  if (!username.value || !password.value) {
    showError.value = true
    errorMessage.value = 'Rellene los campos'
    return
  }

  // Llamada API
  isLoading.value = true
  const result = await UserTools.login({
    username: username.value,
    master_password: password.value,
    session_duration: sessionDuration.value,
  })
  isLoading.value = false

  // Error al iniciar
  if (!result) {
    showError.value = true
    errorMessage.value = 'Se ha producido un error al iniciar sesión, inténtelo más tarde'
    return
  }

  // Comprobar 2FA
  if (result.two_fa_enabled) {
    twoFactorModalActive.value = true
    return
  }

  // Comprobar inicio correcto
  if (!result.id) {
    showError.value = true
    errorMessage.value = 'Nombre de usuario o contraseña incorrectos'
    return
  }

  // Redirigir
  router.push('/vault')
}
</script>

<template>
  <!-- Modal de 2FA -->
  <TwoFactorComponent
    v-if="twoFactorModalActive"
    :user="{ username: username, password: password, sessionDuration: sessionDuration }"
    @close2FA="twoFactorModalActive = false"
  />

  <!-- Formulario de inicio de sesión -->
  <div class="container form-container p-5">
    <h1 class="section-title title is-1 has-text-centered is-size-3-mobile">Iniciar sesión</h1>

    <hr />

    <div class="desktop">
      <!-- Error -->
      <p v-if="showError" class="help is-danger">*{{ errorMessage }}</p>

      <form @submit.prevent>
        <!-- Usuario -->
        <div class="field">
          <label class="label is-size-5-desktop">Nombre de usuario</label>
          <div class="control has-icons-left">
            <input
              type="text"
              placeholder="Nombre de usuario"
              class="input input-field"
              :class="{ 'input-field-incorrect': showError }"
              v-model="username"
              required
            />
            <span class="icon is-left">
              <i class="fa-solid fa-at" :class="{ 'icon-error-color': showError }"></i>
            </span>
          </div>
        </div>

        <!-- Contraseña -->
        <div class="field">
          <label class="label is-size-5-desktop">Contraseña</label>
          <div class="field has-addons">
            <div class="control has-icons-left is-expanded">
              <input
                placeholder="Contraseña maestra"
                class="input input-field"
                :type="showPassword ? 'text' : 'password'"
                :class="{ 'input-field-incorrect': showError }"
                v-model="password"
                required
              />
              <span class="icon is-left">
                <i class="fas fa-lock" :class="{ 'icon-error-color': showError }"></i>
              </span>
            </div>

            <!-- Mostrar/Ocultar contraseña -->
            <div class="control">
              <button
                @click="showPassword = !showPassword"
                class="button input-button"
                type="button"
                :title="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
              >
                <span class="icon">
                  <i v-if="showPassword" class="fas fa-eye-slash"></i>
                  <i v-else class="fas fa-eye"></i>
                </span>
              </button>
            </div>
          </div>
        </div>

        <!-- Duración sesión -->
        <div class="field">
          <label class="label is-size-5-desktop">Duración de la sesión</label>

          <div class="control">
            <div class="select">
              <select class="input-field" v-model="sessionDuration">
                <template v-for="item in config.sessionDuration" :key="item.value">
                  <option :value="item.value">{{ item.label }}</option>
                </template>
              </select>
            </div>
          </div>
        </div>

        <!-- Botón -->
        <div class="field mt-6">
          <div class="control has-text-centered">
            <button
              class="button input-button"
              :class="{ 'is-loading': isLoading }"
              @click="LoginSubmitHandler"
            >
              Iniciar sesión
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
@media screen and (min-width: 1024px) {
  .desktop {
    padding: 0 2.25rem;
  }
}

button {
  box-shadow: none !important;
}

.input-field {
  box-shadow: var(--box-shadow-5-5-10);
}
</style>
