<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import PasswordTools from '@/tools/password.js'
import UserTools from '@/tools/user.js'
import '@/assets/css/forms.css'

const router = useRouter()

const emit = defineEmits(['showRequeriments'])

// Variables formulario
const username = ref('')
const name = ref('')
const masterPass = ref('')
const checkMasterPass = ref('')

// Variables errores
const errorMessage = ref('')
const usernameError = ref('')
const nameError = ref('')
const masterPassError = ref('')
const checkMasterPassError = ref('')

// Variables de estado
const showPassword = ref(false)
const isLoading = ref(false)

// Control de timeouts
let checkUsernameTO = null
let checkNameTO = null
let checkMasterPassTO = null
let checkCheckMasterPassTO = null

/**
 * Controlador del formulario de registro
 */
const registerSubmitHandler = async () => {
  // Comprobar errores
  const error = await checkErrors()

  if (error) return

  // Llamada API
  isLoading.value = true
  const result = await UserTools.register({
    username: username.value,
    name: name.value,
    master_password: masterPass.value,
  })
  isLoading.value = false

  // Error al registrar
  if (!result) {
    errorMessage.value = 'Se ha producido un error al intentar registrarse, inténtelo más tarde'
    return
  }

  sessionStorage.setItem('recuperation-code', result.recuperation_code)

  // Redirigir
  router.push('/vault')
}

/**
 * Comprueba si hay errores en el formulario
 *
 * @returns {boolean} `true` si hay errores, `false` en caso contrario
 */
const checkErrors = async () => {
  resetErrors()

  isLoading.value = true
  let error = false

  if (await !checkUsernameErrors()) error = true

  if (!checkNameErrors()) error = true

  // Contraseña maestra
  if (!checkMasterPassErrors()) error = true

  // Confirmar contraseña
  if (!checkCheckMasterPassErrors()) error = true

  isLoading.value = false
  return error
}

/**
 * Resetea los errores
 */
const resetErrors = () => {
  errorMessage.value = ''
  usernameError.value = ''
  nameError.value = ''
  masterPassError.value = ''
  checkMasterPassError.value = ''
}

/**
 * Comprueba si el nombre de usuario es correcto y está disponible
 *
 * @returns {boolean} `true` si el nombre de usuario es correcto, `false` en caso contrario
 */
const checkUsernameErrors = async () => {
  usernameError.value = ''
  username.value = username.value.trim().replaceAll(' ', '_')

  // Formato
  if (!/^[a-zA-Z][a-zA-Z0-9_]{0,29}$/.test(username.value)) {
    usernameError.value = `Formato incorrecto. Caracteres: ${username.value.length}/30`
    return false
  }

  // Disponibilidad
  const result = await UserTools.existsUsername(username.value)

  if (result === null) {
    usernameError.value =
      'No se puede comprobar la disponibilidad del nombre de usuario, inténtelo más tarde'
    return false
  }

  if (result) {
    usernameError.value = 'El nombre de usuario está siendo utilizado, intente otro'
    return false
  }

  return true
}

/**
 * Manejador nombre usuario
 */
const usernameHandler = () => {
  if (checkUsernameTO) clearTimeout(checkUsernameTO)

  checkUsernameTO = setTimeout(checkUsernameErrors, 1250)
}

/**
 * Comprueba si el nombre es correcto
 *
 * @returns {boolean} `true` si el nombre es correcto, `false` en caso contrario
 */
const checkNameErrors = () => {
  nameError.value = ''
  name.value = name.value.trim()

  if (!/^.{1,50}$/.test(name.value)) {
    nameError.value = `Formato incorrecto. Caracteres: ${name.value.length}/50`
    return false
  }

  return true
}

/**
 * Manejador nombre
 */
const nameHandler = () => {
  if (checkNameTO) clearTimeout(checkNameTO)

  checkNameTO = setTimeout(checkNameErrors, 1250)
}

/**
 * Comprueba si la contraseña maestra es correcta
 *
 * @returns {boolean} `true` si la contraseña es correcta, `false` en caso contrario
 */
const checkMasterPassErrors = () => {
  masterPassError.value = ''
  masterPass.value = masterPass.value.trim()

  if (!PasswordTools.checkMasterPassword(masterPass.value)) {
    masterPassError.value = `No cumple los requisitos mínimos. Caracteres: ${masterPass.value.length}/50`
    return false
  }

  return true
}

/**
 * Manejador contraseña maestra
 */
const checkMasterPassHandler = () => {
  if (checkMasterPassTO) clearTimeout(checkMasterPassTO)

  checkMasterPassTO = setTimeout(() => {
    checkMasterPassErrors()
    checkCheckMasterPassErrors()
  }, 500)
}

/**
 * Comprueba si la contraseña maestra y la de confirmación son iguales
 *
 * @returns {boolean} `true` si son iguales, `false` en caso contrario
 */
const checkCheckMasterPassErrors = () => {
  checkMasterPassError.value = ''
  checkMasterPass.value = checkMasterPass.value.trim()

  if (masterPass.value !== checkMasterPass.value) {
    checkMasterPassError.value = 'Las contraseñas no coinciden'
    return false
  }

  return true
}

/**
 * Manejador confirmación contraseña maestra
 */
const checkCheckMasterPassHandler = () => {
  if (checkCheckMasterPassTO) clearTimeout(checkCheckMasterPassTO)

  checkCheckMasterPassTO = setTimeout(checkCheckMasterPassErrors, 500)
}
</script>

<template>
  <!-- Formulario de inicio de sesión -->
  <div class="container form-container p-5">
    <h1 class="section-title title is-1 has-text-centered is-size-3-mobile">Registrarse</h1>

    <hr />

    <div class="desktop">
      <!-- Error -->
      <p v-if="errorMessage" class="help is-danger">*{{ errorMessage }}</p>

      <form @submit.prevent>
        <!-- Nombre de usuario -->
        <div class="field">
          <label class="label is-size-5-desktop">Nombre de usuario</label>
          <div class="control has-icons-left">
            <input
              type="text"
              placeholder="Nombre de usuario"
              class="input input-field"
              :class="{ 'input-field-incorrect': usernameError }"
              v-model="username"
              @input="usernameHandler"
              required
            />
            <span class="icon is-left">
              <i class="fa-solid fa-at" :class="{ 'icon-error-color': usernameError }"></i>
            </span>
          </div>

          <!-- Error -->
          <p v-if="usernameError" class="help is-danger">*{{ usernameError }}</p>
        </div>

        <!-- Nombre -->
        <div class="field">
          <label class="label is-size-5-desktop">Nombre</label>
          <div class="control has-icons-left">
            <input
              type="text"
              placeholder="Nombre"
              class="input input-field"
              :class="{ 'input-field-incorrect': nameError }"
              v-model="name"
              @input="nameHandler"
              required
            />
            <span class="icon is-left">
              <i class="fa-solid fa-user-tag" :class="{ 'icon-error-color': nameError }"></i>
            </span>
          </div>

          <!-- Error -->
          <p v-if="nameError" class="help is-danger">*{{ nameError }}</p>
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
                :class="{ 'input-field-incorrect': masterPassError }"
                v-model="masterPass"
                @input="checkMasterPassHandler"
                required
              />
              <span class="icon is-left">
                <i class="fas fa-lock" :class="{ 'icon-error-color': masterPassError }"></i>
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

        <!-- Error -->
        <p v-if="masterPassError" class="help is-danger">*{{ masterPassError }}</p>

        <!-- Confirmar Contraseña -->
        <div class="field">
          <label class="label is-size-5-desktop">Confirmar contraseña</label>
          <div class="field has-addons">
            <div class="control has-icons-left is-expanded">
              <input
                placeholder="Contraseña maestra"
                class="input input-field"
                :type="showPassword ? 'text' : 'password'"
                :class="{ 'input-field-incorrect': checkMasterPassError }"
                v-model="checkMasterPass"
                @input="checkCheckMasterPassHandler"
                required
              />
              <span class="icon is-left">
                <i
                  class="fas fa-lock-open"
                  :class="{ 'icon-error-color': checkMasterPassError }"
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
        </div>

        <!-- Error -->
        <p v-if="checkMasterPassError" class="help is-danger">*{{ checkMasterPassError }}</p>

        <!-- Botón -->
        <div class="field mt-6">
          <div class="control has-text-centered">
            <p><a @click="emit('showRequeriments')">Mostrar requisitos</a></p>
            <p class="mb-2">
              ¿Ya tiene cuenta? <RouterLink to="/login">Iniciar sesión</RouterLink>
            </p>

            <button
              class="button input-button"
              :class="{ 'is-loading': isLoading }"
              @click="registerSubmitHandler"
            >
              Registrarse
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

.input-field {
  box-shadow: var(--box-shadow-5-5-10);
}

p {
  color: var(--form-text-color);
}
</style>
