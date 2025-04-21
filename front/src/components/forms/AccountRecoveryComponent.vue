<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import PasswordTools from '@/tools/password.js'
import UserTools from '@/tools/user.js'
import '@/assets/css/forms.css'

const router = useRouter()

const username = ref('')
const recuperationCode = ref('')
const masterPassword = ref('')
const masterPasswordCheck = ref('')

const showPassword = ref(false)

const usernameErrorMessage = ref('')
const recuperationCodeErrorMessage = ref('')
const passwordErrorMessage = ref('')
const passwordCheckErrorMessage = ref('')
const globalErrorMessage = ref('')

const isLoading = ref(false)

const emit = defineEmits(['closeRecuperationCode'])

/**
 * Controlador del formulario del código de recuperación
 */
const recuperationCodeSubmitHandler = async () => {
  // Errores
  resetErrors()

  if (checkErrors()) return

  // Llamada API
  isLoading.value = true
  const result = await UserTools.recuperationCode({
    username: username.value,
    recuperation_code: recuperationCode.value,
    master_password: masterPassword.value,
  })
  isLoading.value = false

  // Comprobar resultado
  if (result === null) {
    globalErrorMessage.value =
      'Se ha producido un error al recuperar la cuenta, inténtelo más tarde'
    return
  }

  if (!result.recuperation_code) {
    recuperationCodeErrorMessage.value = 'Nombre de usuario o código de recuperación incorrectos'
    return
  }

  // Redireccionar
  sessionStorage.setItem('recuperation_code', result.recuperation_code)
  router.push('/vault')
}

/**
 * Comprueba si hay errores en los campos del formulario, mostrando el mensaje de error correspondiente
 *
 * @returns {boolean} `true` si hay errores, `false` en caso contrario
 */
const checkErrors = () => {
  let error = false

  // Formateo
  username.value = username.value.trim()
  recuperationCode.value = recuperationCode.value.trim().toLowerCase()
  masterPassword.value = masterPassword.value.trim()
  masterPasswordCheck.value = masterPasswordCheck.value.trim()

  // Username
  if (!username.value) {
    usernameErrorMessage.value = 'Es obligatorio'
    error = true
  }

  // Código de recuperación
  recuperationCode.value = recuperationCode.value.trim().toLowerCase()
  if (
    !/^[0-9abcdef]{5}-[0-9abcdef]{5}-[0-9abcdef]{5}-[0-9abcdef]{5}$/.test(recuperationCode.value)
  ) {
    recuperationCodeErrorMessage.value =
      'El código no tiene el formato correcto, deben ser 4 conjuntos de números y letras (abcdef) separados por guiones, por ejemplo: 01234-abcde-56abc-efa01'
    error = true
  }

  // Contraseña maestra
  if (!masterPassword.value) {
    passwordErrorMessage.value = 'Es obligatorio'
    passwordCheckErrorMessage.value = 'Es obligatorio'
    error = true
  }

  if (!PasswordTools.checkMasterPassword(masterPassword.value)) {
    passwordErrorMessage.value =
      'Debe tener una longitud entre 8 y 50 caracteres. Debe contener al menos una letra minúscula y una letra mayúscula (alfabeto inglés), un número y alguno de los siguientes símbolos especiales "_-,;!.@*&#%+$/". No se admiten otros caracteres'
    error = true
  }

  // Confirmar contraseña
  if (masterPassword.value !== masterPasswordCheck.value) {
    passwordCheckErrorMessage.value = 'Las contraseñas no coinciden'
    error = true
  }

  return error
}

/**
 * Resetea los mensajes de error
 */
const resetErrors = () => {
  globalErrorMessage.value = ''
  usernameErrorMessage.value = ''
  recuperationCodeErrorMessage.value = ''
  passwordErrorMessage.value = ''
  passwordCheckErrorMessage.value = ''
}

/**
 * Emite el evento de cierre del modal
 */
const close = () => {
  emit('closeRecuperationCode')
}
</script>

<template>
  <div class="modal is-active">
    <div class="modal-background" @click="close"></div>
    <div class="modal-card">
      <!-- Cabecera -->
      <header class="modal-card-head p-5">
        <p class="modal-card-title">Recuperar cuenta</p>
        <button class="delete is-large" aria-label="close" @click="close"></button>
      </header>

      <!-- Cuerpo -->
      <section class="modal-card-body">
        <form @submit.prevent>
          <!-- Input -->
          <div class="field">
            <label class="label is-size-5-desktop">Nombre de usuario</label>
            <div class="control has-icons-left">
              <input
                type="text"
                placeholder="Nombre de usuario"
                class="input input-field"
                :class="{ 'input-field-incorrect': usernameErrorMessage }"
                v-model="username"
                required
              />
              <span class="icon is-left">
                <i class="fa-solid fa-at" :class="{ 'icon-error-color': usernameErrorMessage }"></i>
              </span>
            </div>

            <!-- Error -->
            <p v-if="usernameErrorMessage" class="help is-danger">*{{ usernameErrorMessage }}</p>
          </div>

          <div class="field">
            <label class="label is-size-5-desktop">Código de recuperación</label>
            <div class="control has-icons-left">
              <input
                type="text"
                placeholder="Código"
                class="input input-field"
                :class="{
                  'input-field-incorrect': recuperationCodeErrorMessage,
                }"
                v-model="recuperationCode"
                required
              />
              <span class="icon is-left">
                <i
                  class="fa-solid fa-shield-halved"
                  :class="{
                    'icon-error-color': recuperationCodeErrorMessage,
                  }"
                ></i>
              </span>
            </div>

            <!-- Error -->
            <p v-if="recuperationCodeErrorMessage" class="help is-danger">
              *{{ recuperationCodeErrorMessage }}
            </p>
          </div>

          <div class="field">
            <label class="label is-size-5-desktop">Nueva contraseña</label>
            <div class="field has-addons">
              <div class="control has-icons-left is-expanded">
                <input
                  placeholder="Contraseña"
                  class="input input-field"
                  :type="showPassword ? 'text' : 'password'"
                  :class="{
                    'input-field-incorrect': passwordErrorMessage || passwordCheckErrorMessage,
                  }"
                  v-model="masterPassword"
                  required
                />
                <span class="icon is-left">
                  <i
                    class="fas fa-lock"
                    :class="{
                      'icon-error-color': passwordErrorMessage || passwordCheckErrorMessage,
                    }"
                  ></i>
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

            <!-- Error -->
            <p v-if="passwordErrorMessage" class="help is-danger">*{{ passwordErrorMessage }}</p>
          </div>

          <div class="field">
            <label class="label is-size-5-desktop">Confirmar contraseña</label>
            <div class="field has-addons">
              <div class="control has-icons-left is-expanded">
                <input
                  placeholder="Contraseña"
                  class="input input-field"
                  :type="showPassword ? 'text' : 'password'"
                  :class="{
                    'input-field-incorrect': passwordCheckErrorMessage,
                  }"
                  v-model="masterPasswordCheck"
                  required
                />
                <span class="icon is-left">
                  <i
                    class="fas fa-lock-open"
                    :class="{ 'icon-error-color': passwordCheckErrorMessage }"
                  ></i>
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

            <!-- Error -->
            <p v-if="passwordCheckErrorMessage" class="help is-danger">
              *{{ passwordCheckErrorMessage }}
            </p>
          </div>

          <button @click="recuperationCodeSubmitHandler" hidden></button>
        </form>

        <!-- Error -->
        <p v-if="globalErrorMessage" class="help is-danger">*{{ globalErrorMessage }}</p>
      </section>

      <!-- Pie -->
      <footer class="modal-card-foot p-4">
        <div class="buttons is-centered">
          <button
            class="button button-submit"
            :class="{ 'is-loading': isLoading }"
            @click="recuperationCodeSubmitHandler"
          >
            Recuperar
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

.button-submit {
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
  color: var(--form-text-color);
}

.input-field {
  box-shadow: var(--box-shadow-5-5-10);
}
</style>
