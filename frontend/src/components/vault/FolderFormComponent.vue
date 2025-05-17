<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'
import FolderTools from '@/tools/folder.js'
import '@/assets/css/forms.css'

const pStore = usePasswordStore()
const { folders } = storeToRefs(pStore)

const title = ref('Crear nueva carpeta')
const buttonText = ref('Crear carpeta')

const name = ref('')

const nameError = ref('')
const errorMessage = ref('')
const isLoading = ref(false)

const props = defineProps({
  folderId: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['close'])

onMounted(() => {
  if (!props.folderId) return

  const password = folders.value.find((item) => item.id === props.folderId)

  title.value = `Detalles de ${password.name}`
  buttonText.value = 'Editar carpeta'

  name.value = password.name
})

/**
 * Controlador del formulario de inicio de sesión
 */
const handleSubmit = async () => {
  nameError.value = ''
  name.value = name.value.trim()

  // Comprobar campos obligatorios
  if (!name.value) {
    nameError.value = 'El campo es obligatorio'
    return
  }

  // Comprobar si el nombre editado es igual al nombre actual
  const editFolder = folders.value.find((item) => item.id === props.folderId)

  if (editFolder && editFolder.name.toLowerCase() === name.value.toLowerCase()) {
    return
  }

  // Comprobar si el nombre existe
  if (folders.value.some((item) => item.name.toLowerCase() === name.value.toLowerCase())) {
    nameError.value = 'Existe una carpeta con este nombre'
    return
  }

  // Llamada API
  isLoading.value = true

  const data = {
    name: name.value,
  }

  let result
  if (props.folderId) {
    data.id = props.folderId
    result = await FolderTools.update(data)
  } else {
    result = await FolderTools.add(data)
  }

  // Error al iniciar
  if (!result) {
    isLoading.value = false
    errorMessage.value = 'Se ha producido un error al añadir la contraseña, inténtelo más tarde'
    return
  }

  // Comprobar resultado
  if (!result.id) {
    console.error(result)
    return
  }

  // Actualizar o añadir
  folders.value = await FolderTools.getFolders()
  isLoading.value = false

  // Cerrar modal
  emit('close')
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
            <!-- Error -->
            <p v-if="errorMessage" class="help is-danger">*{{ errorMessage }}</p>

            <!-- Nombre -->
            <div class="field">
              <label class="label is-size-5-desktop">Nombre</label>
              <div class="control">
                <input
                  type="text"
                  placeholder="Nombre de la carpeta"
                  class="input input-field"
                  :class="{ 'input-field-incorrect': nameError }"
                  v-model="name"
                  required
                />

                <!-- Error -->
                <p v-if="nameError" class="help is-danger">*{{ nameError }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Pie -->
        <footer class="modal-card-foot p-4">
          <button class="button" :class="{ 'is-loading': isLoading }" @click="handleSubmit">
            {{ buttonText }}
          </button>
        </footer>
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

.input-field {
  box-shadow: var(--box-shadow-5-5-10);
}

p {
  color: var(--form-text-color);
}

.modal-card-foot {
  justify-content: center;
}

.icon {
  z-index: 5 !important;
}

.label {
  color: var(--form-text-color);
}
</style>
