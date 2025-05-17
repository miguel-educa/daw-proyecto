<script setup>
import { ref } from 'vue'
import { usePasswordStore } from '@/stores/passwordStore.js'
import { storeToRefs } from 'pinia'
import FolderTools from '@/tools/folder.js'

const props = defineProps({
  folderId: {
    type: String,
    required: true,
  },
})

const pStore = usePasswordStore()
const { folders, foldersIdSelected, passwords } = storeToRefs(pStore)

const isLoading = ref(false)

const deletePassword = async () => {
  console.log(props.folderId)
  const passwordIndex = folders.value.findIndex((item) => item.id === props.folderId)

  if (passwordIndex === -1) {
    emit('error', 'Carpeta no encontrada')
    return
  }

  isLoading.value = true
  const result = await FolderTools.delete({ id: folders.value[passwordIndex].id })
  isLoading.value = false

  if (!result) {
    console.error('Error al eliminar la carpeta')
    emit('error', 'Error al eliminar la carpeta')
    return
  }

  folders.value.splice(passwordIndex, 1)

  foldersIdSelected.value = foldersIdSelected.value.filter((item) => item !== props.folderId)

  passwords.value = passwords.value.map((item) => {
    if (item.folder_id === props.folderId) {
      item.folder_id = null
    }

    return item
  })

  emit('deleted')
}

const emit = defineEmits(['close', 'deleted', 'error'])
</script>

<template>
  <div class="modal is-active">
    <div class="modal-background" @click="emit('close')"></div>
    <div class="modal-card">
      <header class="modal-card-head p-5">
        <p class="modal-card-title">¿Eliminar carpeta?</p>
        <button class="delete" aria-label="close" @click="emit('close')"></button>
      </header>
      <section class="modal-card-body">
        <div class="is-flex is-flex-direction-column is-align-items-center justify-content-center">
          <p class="is-size-5 subtitle has-text-centered">
            ¿Está seguro? Esta acción <strong>no se puede deshacer</strong>.
          </p>
          <p class="is-size-5 subtitle has-text-centered">
            Los elementos almacenados en la carpeta eliminada serán cambiados a "<strong
              >Sin carpeta</strong
            >"
          </p>
        </div>
      </section>
      <footer class="modal-card-foot p-4">
        <div class="buttons is-centered">
          <button class="button is-danger" @click="emit('close')">Cancelar</button>
          <button
            class="button danger"
            :class="{ 'is-loading': isLoading }"
            @click="deletePassword"
          >
            <span class="icon-text">
              <span class="icon">
                <i class="fa-solid fa-trash"></i>
              </span>
              <span>Eliminar</span>
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
