<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore.js'
import config from '@/config.js'
import UserTools from '@/tools/user.js'
import PasswordTools from '@/tools/password.js'
import { ClipboardTools } from '@/tools/clipboard'

const uStore = useUserStore()
const { user } = storeToRefs(uStore)

const username = ref('')
const name = ref('')
const nameError = ref('')
const nameIsLoading = ref(false)

const recuperationCode = ref('')
const recuperationCodeEditedAt = ref('')
const showRecuperationCode = ref(false)
const recuperationCodeError = ref('')
const recuperationCodeIsLoading = ref(false)

const masterPassword = ref('')
const masterPasswordChecker = ref('')
const masterPasswordEditedAt = ref('')
const showPassword = ref(false)
const masterPasswordError = ref('')
const masterPasswordIsLoading = ref(false)

const sessionDuration = ref(config.sessionDuration[2].value)

const totp2faActivated = ref('')
const totp2faActivatedAt = ref('')
const totp2faIsLoading = ref(false)

const secret = ref('')
const qrCodeUrl = ref('')
const secretKey = ref('')

const verify2FACode = ref('')
const verify2FACodeIsLoading = ref(false)
const verify2FACodeError = ref('')

const emit = defineEmits(['userUpdated', 'delete'])

onMounted(() => {
  loadUserConfig()
})

const loadUserConfig = async () => {
  const user = await UserTools.getUserInfo(true)

  username.value = user.username
  name.value = user.name
  recuperationCode.value = user.recuperationCode
  recuperationCodeEditedAt.value = new Date(user.recuperationCodeEditedAt).toLocaleString()
  masterPasswordEditedAt.value = new Date(user.masterPasswordEditedAt).toLocaleString()
  totp2faActivated.value = user.totp2faActivated
  totp2faActivatedAt.value = new Date(user.totp2faActivatedAt).toLocaleString()
  qrCodeUrl.value = ''
}

const generate2FA = async () => {
  totp2faIsLoading.value = true
  const result = await UserTools.generate2FA()
  totp2faIsLoading.value = false

  if (!result) {
    emit('userUpdated', 'Algo falló, no se ha podido generar el código QR', true)
    return
  }

  qrCodeUrl.value = result.qr_code_url
  secretKey.value = result.qr_code_url.match(/%3Fsecret%3D(.*)&size=200x200&ecc=M/)[1]
}

const verify2FA = async () => {
  verify2FACodeError.value = ''
  verify2FACode.value = verify2FACode.value.replaceAll(' ', '')
  if (!/^\d{6}$/.test(verify2FACode.value)) {
    verify2FACodeError.value = 'El código debe ser de 6 dígitos'
    return
  }

  const data = {
    secret: secret.value,
    code: verify2FACode.value,
  }

  verify2FACodeIsLoading.value = true
  const result = await UserTools.verify2FA(data)
  verify2FACodeIsLoading.value = false

  if (!result) {
    verify2FACodeError.value = 'Algo falló, 2FA no habilitado'
    return
  }

  if (result.errors) {
    verify2FACodeError.value = 'Código incorrecto, 2FA no habilitado'
    return
  }

  secretKey.value = ''
  verify2FACode.value = ''
  loadUserConfig()
  emit(
    'userUpdated',
    '2FA activado correctamente. Se pedirá el código temporal cada vez que inicie sesión',
  )
}

const delete2FA = async () => {
  totp2faIsLoading.value = true
  const result = await UserTools.delete2FA()
  totp2faIsLoading.value = false

  if (!result) {
    emit(
      'userUpdated',
      'Se ha producido un error al eliminar la autenticación de doble factor',
      true,
    )
    return
  }

  loadUserConfig()
  emit('userUpdated', 'Autenticación de doble factor eliminada correctamente')
}

const updateSessionDuration = async () => {
  const result = await UserTools.updateSessionDuration({
    session_duration: sessionDuration.value,
  })

  if (!result) {
    emit('userUpdated', 'Se ha producido un error al ampliar la sesión', true)
    return
  }

  loadUserConfig()
  emit('userUpdated', 'Sesión ampliada correctamente')
}

const updateName = async () => {
  nameError.value = ''
  name.value = name.value.trim()

  if (name.value.length === 0) {
    nameError.value = 'El nombre no puede estar vacío'
    return
  }

  if (name.value.length > 50) {
    nameError.value = 'El nombre debe tener menos de 50 caracteres'
    return
  }

  if (name.value === user.value.name) return

  nameIsLoading.value = true
  const result = await UserTools.update({
    name: name.value,
  })

  if (result) {
    loadUserConfig()
    user.value = await UserTools.getUserInfo()
    emit('userUpdated', 'Nombre actualizado correctamente')
  } else nameError.value = 'Se ha producido un error al actualizar el nombre'

  nameIsLoading.value = false
}

const updateRecuperationCode = async () => {
  recuperationCodeError.value = ''
  recuperationCode.value = recuperationCode.value.trim()

  recuperationCodeIsLoading.value = true
  const result = await UserTools.update({
    recuperation_code: true,
  })

  if (result) {
    loadUserConfig()
    emit(
      'userUpdated',
      'Nuevo código de recuperación generado. ¡No debe olvidar el código! Es la única forma de poder recuperar la cuenta',
    )
  } else
    recuperationCodeError.value = 'Se ha producido un error al actualizar el código de recuperación'

  recuperationCodeIsLoading.value = false
}

const updateMasterPassword = async () => {
  masterPasswordError.value = ''
  masterPassword.value = masterPassword.value.trim()

  if (masterPassword.value.length === 0) {
    masterPasswordError.value = 'Los campos son obligatorios'
    return
  }

  if (!PasswordTools.checkMasterPassword(masterPassword.value)) {
    masterPasswordError.value =
      'La contraseña maestra no cumple los requisitos mínimos: Entre 8 y 50 caracteres, con al menos una letra mayúscula, una letra minúscula, un número y un carácter especial (_-,;!.@*&#%+$/\)'
    return
  }

  if (masterPassword.value !== masterPasswordChecker.value) {
    masterPasswordError.value = 'Las contraseñas no coinciden'
    return
  }

  masterPasswordIsLoading.value = true
  const result = await UserTools.update({
    master_password: masterPassword.value,
  })

  if (result) {
    loadUserConfig()
    masterPassword.value = ''
    masterPasswordChecker.value = ''
    emit('userUpdated', 'Contraseña maestra actualizada correctamente')
  } else masterPasswordError.value = 'Se ha producido un error al actualizar la contraseña maestra'

  masterPasswordIsLoading.value = false
}

const copyRecuperationCode = () => {
  if (!ClipboardTools.copyText(recuperationCode.value)) {
    emit(
      'userUpdated',
      'Se ha producido un error al copiar el código de recuperación en el portapapeles',
      true,
    )
    return
  }

  emit('userUpdated', 'Código de recuperación copiado correctamente en el portapapeles')
}

const copySecretKey = () => {
  if (!ClipboardTools.copyText(secretKey.value)) {
    emit('userUpdated', 'Se ha producido un error al copiar la clave en el portapapeles', true)
    return
  }

  emit('userUpdated', 'Clave copiada correctamente en el portapapeles')
}
</script>

<template>
  <div class="box setting-container">
    <h1 class="title is-4 has-text-centered audit-title">
      <span class="icon-text">
        <span class="icon">
          <i class="fa-solid fa-user-shield"></i>
        </span>
        <span>Cuenta y seguridad</span>
      </span>
    </h1>

    <div class="columns is-multiline">
      <div class="column is-half">
        <div class="box setting-container">
          <h2 class="title is-5 setting-title">Datos del usuario</h2>

          <div class="field">
            <label class="label setting-text">Nombre de usuario (no editable)</label>
            <div class="control has-icons-left">
              <input type="text" class="input input-field" v-model="username" readonly />
              <span class="icon is-left">
                <i class="fa-solid fa-at form-icon"></i>
              </span>
            </div>
          </div>

          <label class="label setting-text">Nombre</label>
          <form @submit.prevent>
            <div class="field has-addons">
              <div class="control has-icons-left is-expanded">
                <input
                  type="text"
                  class="input input-field"
                  placeholder="Cambiar nombre"
                  v-model="name"
                />
                <span class="icon is-left">
                  <i
                    class="fa-solid fa-user-tag form-icon"
                    :class="{ 'icon-error-color': nameError }"
                  ></i>
                </span>
              </div>

              <div class="control">
                <button
                  class="button input-button"
                  :class="{ 'is-loading': nameIsLoading }"
                  @click="updateName"
                >
                  Cambiar
                </button>
              </div>
            </div>
          </form>
          <p class="help is-danger" v-if="nameError">*{{ nameError }}</p>
        </div>
      </div>

      <div class="column is-half">
        <div class="box setting-container">
          <h2 class="title is-5 setting-title">Código de recuperación</h2>

          <form @submit.prevent>
            <div class="field has-addons">
              <div class="control has-icons-left is-expanded">
                <input
                  :type="showRecuperationCode ? 'text' : 'password'"
                  v-model="recuperationCode"
                  class="input input-field"
                  readonly
                />
                <span class="icon is-left">
                  <i class="fa-solid fa-shield-halved form-icon"></i>
                </span>
              </div>
              <div class="control">
                <button class="button input-button" @click="copyRecuperationCode">
                  <span class="icon">
                    <i class="fas fa-copy"></i>
                  </span>
                </button>
              </div>
            </div>
            <p class="help is-danger" v-if="recuperationCodeError">*{{ recuperationCodeError }}</p>
            <p class="help setting-text">Última modificación: {{ recuperationCodeEditedAt }}</p>

            <div class="buttons is-centered mt-3">
              <a class="button input-button" @click="showRecuperationCode = !showRecuperationCode">
                <span class="icon-text">
                  <span class="icon">
                    <i v-if="showRecuperationCode" class="fas fa-eye-slash"></i>
                    <i v-else class="fas fa-eye"></i>
                  </span>
                  <span>
                    {{ showRecuperationCode ? 'Ocultar' : 'Mostrar' }}
                  </span>
                </span>
              </a>

              <button
                class="button input-button"
                :class="{ 'is-loading': recuperationCodeIsLoading }"
                @click="updateRecuperationCode"
              >
                Generar nuevo código
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="column is-half">
        <div class="box setting-container">
          <h2 class="title is-5 setting-title">Contraseña maestra</h2>

          <form @submit.prevent>
            <div class="field">
              <div class="control has-icons-left">
                <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="masterPassword"
                  class="input input-field"
                  placeholder="Nueva contraseña maestra"
                /><span class="icon is-left">
                  <i class="fas fa-lock form-icon"></i>
                </span>
              </div>
            </div>

            <div class="field">
              <div class="control has-icons-left">
                <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="masterPasswordChecker"
                  class="input input-field"
                  placeholder="Confirmar contraseña maestra"
                /><span class="icon is-left">
                  <i class="fas fa-lock-open form-icon"></i>
                </span>
              </div>
            </div>

            <p class="help is-danger" v-if="masterPasswordError">*{{ masterPasswordError }}</p>
            <p class="help setting-text">Última modificación: {{ masterPasswordEditedAt }}</p>

            <div class="buttons is-centered mt-3">
              <a class="button input-button" @click="showPassword = !showPassword">
                <span class="icon-text">
                  <span class="icon">
                    <i v-if="showPassword" class="fas fa-eye-slash"></i>
                    <i v-else class="fas fa-eye"></i>
                  </span>
                  <span>
                    {{ showPassword ? 'Ocultar' : 'Mostrar' }}
                  </span>
                </span>
              </a>

              <button
                class="button input-button"
                :class="{ 'is-loading': masterPasswordIsLoading }"
                @click="updateMasterPassword"
              >
                Cambiar contraseña
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="column is-half">
        <div class="box setting-container">
          <h2 class="title is-5 setting-title">Cambiar duración de la sesión actual</h2>

          <div class="field">
            <div class="control">
              <div class="select is-fullwidth">
                <select class="input-field" v-model="sessionDuration">
                  <template v-for="item in config.sessionDuration" :key="item.value">
                    <option :value="item.value">{{ item.label }}</option>
                  </template>
                </select>
              </div>
            </div>
          </div>

          <div class="buttons is-centered mt-3">
            <button class="button input-button" @click="updateSessionDuration">
              Cambiar duración
            </button>
          </div>
        </div>
      </div>

      <div class="column is-full">
        <div class="box setting-container">
          <h2 class="title is-5 setting-title">Doble factor de autenticación</h2>
          <p v-if="totp2faActivated" class="help setting-text">
            Doble factor mediante código temporal <strong class="setting-text">activado</strong
            ><br />
            Fecha de activación: {{ totp2faActivatedAt }}
          </p>
          <p v-else class="help setting-text">
            El doble factor de autenticación mediante código temporal no está activado
          </p>
          <div class="buttons mt-3">
            <button
              v-if="totp2faActivated"
              class="button is-danger"
              :class="{ 'is-loading': totp2faIsLoading }"
              @click="delete2FA"
            >
              Eliminar
            </button>
            <button
              v-if="!qrCodeUrl && !totp2faActivated"
              class="button input-button"
              :class="{ 'is-loading': totp2faIsLoading }"
              @click="generate2FA"
            >
              Activar
            </button>
          </div>

          <template v-if="qrCodeUrl">
            <div class="columns is-vcentered">
              <div class="column is-half">
                <p class="setting-text">
                  Escanee el siguiente código QR con su aplicación de autenticación<br />(<u
                    >Google Authenticator</u
                  >, <u>Microsoft Authenticator</u>...)
                </p>
                <div class="has-text-centered">
                  <img
                    :src="qrCodeUrl"
                    alt="Código QR para activar la autenticación de doble factor"
                    class="mt-2"
                  />
                </div>
                <p class="mb-2 setting-text">
                  Si no puede excanear el QR, introduzca manualmente la siguiente clave en la
                  apliación
                </p>
                <div class="field has-addons">
                  <div class="control is-expanded">
                    <input type="text" class="input input-field" v-model="secretKey" readonly />
                  </div>
                  <div class="control">
                    <button class="button input-button" @click="copySecretKey">
                      <span class="icon">
                        <i class="fas fa-copy"></i>
                      </span>
                    </button>
                  </div>
                </div>
              </div>

              <div class="column is-half">
                <div class="box setting-container">
                  <form @submit.prevent>
                    <p class="setting-text">
                      Introduzca el código de 6 cifras generado en la aplicación para confirmar la
                      activación:
                    </p>
                    <div class="field has-addons mt-3">
                      <div class="control is-expanded">
                        <input
                          type="text"
                          v-model="verify2FACode"
                          class="input input-field"
                          placeholder="000000"
                        />
                      </div>

                      <div class="control">
                        <button
                          class="button input-button"
                          :class="{ 'is-loading': verify2FACodeIsLoading }"
                          @click="verify2FA"
                        >
                          Verificar
                        </button>
                      </div>
                    </div>
                    <p class="help is-danger" v-if="verify2FACodeError">
                      *{{ verify2FACodeError }}
                    </p>
                  </form>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <div class="column is-full">
        <div class="box setting-container">
          <h2 class="title is-4 setting-title">Zona peligrosa</h2>
          <div class="buttons is-centered">
            <button class="button is-warning" @click="emit('delete', 'vault')">
              Vaciar baúl personal
            </button>
            <button class="button is-warning" @click="emit('delete', 'shared-vault')">
              Vaciar baúl compartido
            </button>
            <button class="button is-danger" @click="emit('delete', 'account')">
              Eliminar la cuenta
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
