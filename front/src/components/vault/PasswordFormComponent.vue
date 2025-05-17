<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'
import { useUserStore } from '@/stores/userStore'
import PasswordTools from '@/tools/password.js'
import UserTools from '@/tools/user.js'
import '@/assets/css/forms.css'

const pStore = usePasswordStore()
const { passwords, sharedPasswords, folders } = storeToRefs(pStore)

const uStore = useUserStore()
const { user } = storeToRefs(uStore)

const title = ref('Añadir nuevo elemento')
const buttonText = ref('Añadir elemento')

const name = ref('')
const folder = ref('')
const username = ref('')
const password = ref('')
const urls = ref([''])
const notes = ref('')

const showPassword = ref(false)
const nameError = ref('')
const usernameError = ref('')
const passwordError = ref('')
const notesError = ref('')
const errorMessage = ref('')
const isLoading = ref(false)

const ownerName = ref('')
const ownerUsername = ref('')
const isOwner = ref(true)

const searchUser = ref('')
const usersFound = ref([])
const usersShared = ref([])
const searchTimeOut = ref(null)

const props = defineProps({
  passwordId: {
    type: String,
    default: null,
  },
  isSharedPassword: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['close'])

onMounted(async () => {
  if (!props.passwordId) return

  let editPassword
  if (props.isSharedPassword) {
    editPassword = sharedPasswords.value.find((item) => item.id === props.passwordId)
  } else {
    editPassword = passwords.value.find((item) => item.id === props.passwordId)
  }

  title.value = `Detalles de ${editPassword.name}`
  buttonText.value = 'Editar elemento'

  name.value = editPassword.name
  folder.value = editPassword.folder_id ?? ''
  username.value = editPassword.username
  password.value = editPassword.password
  urls.value = editPassword.urls ? [...editPassword.urls] : []
  if (urls.value.length === 0 || urls.value[urls.value.length - 1] !== '') urls.value.push('')
  notes.value = editPassword.notes

  if (props.isSharedPassword) {
    isOwner.value = editPassword.is_owner
    ownerUsername.value = editPassword.owner_username
    ownerName.value = editPassword.owner_name
    usersShared.value = await PasswordTools.getSharedPasswordUsers(editPassword.id)
  }
})

/**
 * Controlador del formulario de inicio de sesión
 */
const handleSubmit = async () => {
  nameError.value = ''

  // Comprobar campos obligatorios
  if (!name.value) {
    nameError.value = 'El campo es obligatorio'
    return
  }

  let error = false
  // Comprobar campos
  // name
  name.value = name.value?.trim() ?? null
  if (name.value && name.value.length > 50) {
    nameError.value = '50 caracteres máximo'
    error = true
  }

  // username
  username.value = username.value?.trim() ?? null
  if (username.value && username.value.length > 50) {
    usernameError.value = '50 caracteres máximo'
    error = true
  }

  // password
  password.value = password.value?.trim() ?? null
  if (password.value && password.value.length > 100) {
    passwordError.value = '100 caracteres máximo'
    error = true
  }

  // notes
  notes.value = notes.value?.trim() ?? null
  if (notes.value && notes.value.length > 16383) {
    notesError.value = '16383 caracteres máximo'
    error = true
  }

  if (error) return

  // Validar URLs
  const dataUrl = urls.value
    .filter((url) => url !== '')
    .map((url) => {
      if (url.startsWith('http://') || url.startsWith('https://')) return url.trim()

      return `https://${url.trim()}`
    })

  // Comprobar si los datos editados son iguales a los actuales
  let editPassword = props.isSharedPassword
    ? sharedPasswords.value.find((item) => item.id === props.passwordId)
    : passwords.value.find((item) => item.id === props.passwordId)

  if (
    editPassword &&
    editPassword.name?.toLowerCase() === name.value?.toLowerCase() &&
    editPassword.username?.toLowerCase() === username.value?.toLowerCase() &&
    editPassword.password === password.value &&
    ((!editPassword.urls && dataUrl.length === 0) ||
      editPassword.urls?.every((url, index) => url === urls.value[index])) &&
    editPassword.notes?.toLowerCase() === notes.value?.toLowerCase() &&
    ((!props.isSharedPassword &&
      editPassword.folder_id == (folder.value === '' ? null : folder.value)) ||
      (props.isSharedPassword && usersShared.value.length === 0))
  )
    return

  // Llamada API
  isLoading.value = true

  const data = {
    name: name.value,
    folder_id: folder.value === '' ? null : folder.value,
    username: username.value === '' ? null : username.value,
    password: password.value === '' ? null : password.value,
    urls: dataUrl.length === 0 ? null : dataUrl,
    notes: notes.value === '' ? null : notes.value,
  }

  // Llamada API
  let result
  if (props.passwordId) {
    data.id = props.passwordId
    result = await PasswordTools.update(data)
  } else {
    result = await PasswordTools.add(data)
  }

  // 2ª llamada API
  let result2
  if (props.isSharedPassword) {
    const data2 = {
      shared_password_id: props.passwordId ?? result.id,
      shared_users: usersShared.value,
    }
    console.log(data2)

    result2 = await PasswordTools.updateSharedPasswordUsers(data2)
  }

  isLoading.value = false
  // Error al iniciar
  if ((!result && !result2) || (result && !result.id && (result2 || !result2.updated))) {
    errorMessage.value = 'Se ha producido un error al añadir la contraseña, inténtelo más tarde'
    return
  }

  passwords.value = await PasswordTools.getPasswords()
  sharedPasswords.value = await PasswordTools.getSharedPasswords()

  // Cerrar modal
  emit('close')
}

const handleUrlFormat = (index) => {
  let value = urls.value[index].trim()

  if (value && !value.startsWith('http://') && !value.startsWith('https://'))
    value = `https://${value}`

  urls.value[index] = value
}

// Función para agregar un nuevo campo de URL cuando se llena el anterior
const handleUrlInput = (index) => {
  if (urls.value.length > 0 && urls.value[index] === '') {
    urls.value.splice(index, 1)
    return
  }

  if (index > 3 || index + 1 < urls.value.length) return

  urls.value.push('')
}

const handleSearchUser = async () => {
  if (!searchUser.value) {
    usersFound.value = []
    return
  }

  if (searchTimeOut.value) clearTimeout(searchTimeOut.value)

  searchTimeOut.value = setTimeout(async () => {
    const users = await UserTools.getUsersByUsername(searchUser.value)
    usersFound.value = users.filter((u) => {
      return (
        u.username !== user.value.username &&
        !usersShared.value.some((su) => su.shared_user_username === u.username)
      )
    })
  }, 500)
}

const handleAddUser = (username) => {
  const index = usersFound.value.findIndex((u) => u.username === username)
  const userFound = usersFound.value[index]

  if (userFound) {
    usersShared.value.push({
      shared_user_username: userFound.username,
      shared_user_name: userFound.name,
    })

    usersFound.value.splice(index, 1)
  }
}

const handleDeleteUser = (username) => {
  const index = usersShared.value.findIndex((u) => u.shared_user_username === username)
  const userFound = usersShared.value[index]

  if (userFound) {
    usersShared.value.splice(index, 1)
    handleSearchUser()
  }
}
</script>

<template>
  <div class="modal is-active">
    <div class="modal-background" @click="emit('close')"></div>
    <div class="modal-card">
      <!-- Cabecera -->
      <header class="modal-card-head p-5">
        <p class="modal-card-title">{{ title }}</p>
        <button class="delete is-large" aria-label="close" @click="emit('close')"></button>
      </header>

      <!-- Cuerpo -->
      <form @submit.prevent>
        <section class="modal-card-body p-5">
          <div class="desktop">
            <template v-if="!isOwner">
              <h1 class="title is-5">Compartida por {{ ownerName }} (@{{ ownerUsername }})</h1>
              <hr />
            </template>

            <!-- Error -->
            <p v-if="errorMessage" class="help is-danger">*{{ errorMessage }}</p>

            <!-- Carpeta -->
            <template v-if="!isSharedPassword">
              <div class="field">
                <label class="label is-size-5-desktop">Carpeta</label>

                <div class="control">
                  <div class="select">
                    <select class="input-field" v-model="folder">
                      <template v-for="item in folders" :key="item.id">
                        <option :value="item.id ?? ''">{{ item.name }}</option>
                      </template>
                    </select>
                  </div>
                </div>
              </div>
            </template>

            <!-- Usuarios a compartir -->
            <template v-if="isOwner && isSharedPassword">
              <div class="field">
                <label class="label is-size-5-desktop">Usuarios a compartir*</label>
                <div class="control">
                  <input
                    type="text"
                    placeholder="Introduce el @ de un usuario"
                    class="input input-field"
                    v-model="searchUser"
                    @input="handleSearchUser"
                  />
                </div>

                <div class="box users-container mt-3">
                  <template v-if="usersFound.length > 0">
                    <div class="buttons is-centered">
                      <template v-for="u in usersFound" :key="u.username">
                        <a
                          class="button users-search"
                          title="Click para compartir"
                          @click="handleAddUser(u.username)"
                        >
                          {{ u.name }} (@{{ u.username }})
                        </a>
                      </template>
                    </div>
                  </template>
                  <template v-else-if="searchUser">
                    <p class="has-text-danger">*No se encontraron usuarios</p>
                  </template>
                  <template v-else>
                    <p class="">
                      Introduzca un nombre de usuario para comenzar la búsqueda. Haga click en los
                      usuarios con los que desee compartir la contraseña
                    </p>
                  </template>
                </div>

                <label class="label is-size-5-desktop">Compartido con</label>
                <div class="box users-container mt-3">
                  <template v-if="usersShared.length > 0">
                    <div class="buttons is-centered">
                      <template v-for="u in usersShared" :key="u.shared_user_username">
                        <a
                          class="button users-shared"
                          title="Click para eliminar"
                          @click="handleDeleteUser(u.shared_user_username)"
                        >
                          {{ u.shared_user_name }} (@{{ u.shared_user_username }})
                        </a>
                      </template>
                    </div>
                  </template>
                  <template v-else>
                    <p class="has-text-danger">
                      *Debe seleccionar al menos un usuario para compartir
                    </p>
                  </template>
                </div>
              </div>

              <hr />
            </template>

            <!-- Nombre -->
            <div class="field">
              <label class="label is-size-5-desktop">Nombre del elemento*</label>
              <div class="control">
                <input
                  type="text"
                  placeholder="Nombre"
                  class="input input-field"
                  :class="{ 'input-field-incorrect': nameError }"
                  v-model="name"
                  :readonly="!isOwner"
                  required
                />

                <!-- Error -->
                <p v-if="nameError" class="help is-danger">*{{ nameError }}</p>
              </div>
            </div>

            <!-- Nombre de usuario -->
            <div class="field">
              <label class="label is-size-5-desktop">Nombre de usuario</label>
              <div class="control has-icons-left">
                <input
                  type="text"
                  placeholder="Nombre de usuario"
                  class="input input-field"
                  v-model="username"
                  :readonly="!isOwner"
                />
                <span class="icon is-left">
                  <i class="fa-solid fa-at form-icon"></i>
                </span>
              </div>

              <!-- Error -->
              <p v-if="usernameError" class="help is-danger">*{{ usernameError }}</p>
            </div>

            <!-- Contraseña -->
            <div class="field">
              <label class="label is-size-5-desktop">Contraseña</label>
              <div class="field has-addons">
                <div class="control has-icons-left is-expanded">
                  <input
                    placeholder="Contraseña"
                    class="input input-field"
                    :type="showPassword ? 'text' : 'password'"
                    v-model="password"
                    :readonly="!isOwner"
                  />
                  <span class="icon is-left">
                    <i class="fas fa-lock form-icon"></i>
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

                <!-- Error -->
                <p v-if="passwordError" class="help is-danger">*{{ passwordError }}</p>
              </div>

              <template v-if="isOwner">
                <a href="/pass-generator" target="_blank" class="is-size-7">Abrir generador</a>
              </template>
            </div>

            <!-- URLs -->
            <template v-if="isOwner || urls.some((url) => url !== '')">
              <label class="label is-size-5-desktop">URLs</label>
              <template v-for="(url, index) in urls" :key="index">
                <div
                  v-if="index < 5 && (isOwner || url !== '')"
                  class="field has-addons"
                  :class="{ 'mt-2': index > 0 }"
                >
                  <div class="control has-icons-left is-expanded">
                    <span class="icon is-left">
                      <i class="fa-solid fa-link form-icon"></i>
                    </span>
                    <input
                      class="input input-field"
                      v-model="urls[index]"
                      :placeholder="'URL ' + (index + 1)"
                      @input="handleUrlInput(index)"
                      @blur="handleUrlFormat(index)"
                      :readonly="!isOwner"
                    />
                  </div>
                  <div v-if="urls[index]" class="control">
                    <a
                      class="button input-button"
                      type="button"
                      title="Abrir enlace en nueva pestaña"
                      target="_blank"
                      :href="urls[index]"
                    >
                      <span class="icon">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                      </span>
                    </a>
                  </div>
                </div>
              </template>
            </template>

            <!-- Notas -->
            <div class="field">
              <label class="label is-size-5-desktop">Notas</label>
              <div class="control">
                <textarea
                  class="textarea has-fixed-size input-field"
                  placeholder="Añadir notas"
                  v-model="notes"
                  :readonly="!isOwner"
                ></textarea>
              </div>

              <!-- Error -->
              <p v-if="notesError" class="help is-danger">*{{ notesError }}</p>
            </div>
          </div>
        </section>

        <!-- Pie -->
        <template v-if="isOwner">
          <footer class="modal-card-foot p-4">
            <button class="button" :class="{ 'is-loading': isLoading }" @click="handleSubmit">
              {{ buttonText }}
            </button>
          </footer>
        </template>
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

.modal-card-body {
  overflow-y: auto;
  max-height: 75dvh;
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

.input-field {
  box-shadow: var(--box-shadow-5-5-10);
}

p {
  color: var(--form-text-color);
}

.icon {
  z-index: 5 !important;
}

.label {
  color: var(--form-text-color);
}

.users-container {
  background-color: transparent;
  border: 1px solid var(--pg-text-color);
  box-shadow: var(--box-shadow-0-5-10);
}

.users-search {
  background-color: var(--pg-button-background-color);
  box-shadow: var(--box-shadow-5-5-10);
  color: var(--form-button-text-color);
}

.users-shared {
  background-color: var(--pg-button-background-color);
  box-shadow: var(--box-shadow-5-5-10);
  color: var(--form-button-text-color);
}
</style>
