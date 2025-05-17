<script setup>
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'

const emit = defineEmits([
  'createNewFolder',
  'showFolder',
  'deleteFolder',
  'copyPassword',
  'copyUsername',
])

const pStore = usePasswordStore()
const { foldersIdSelected, folders } = storeToRefs(pStore)

const toggleFolder = (id) => {
  if (!foldersIdSelected.value.includes(id)) {
    foldersIdSelected.value.push(id)
  } else {
    foldersIdSelected.value.splice(foldersIdSelected.value.indexOf(id), 1)
  }
}

const showFolder = (id) => {
  emit('showFolder', id)
}

const deleteFolder = (id) => {
  emit('deleteFolder', id)
}
</script>

<template>
  <div class="box pass-container">
    <h1 class="title is-3 has-text-centered is-size-3-mobile">Carpetas</h1>

    <template v-for="item in folders" :key="item.id">
      <div class="buttons mt-3">
        <!-- Primer botón: ocupará el espacio restante -->
        <button
          class="button folder-button is-flex-grow-1"
          :class="{
            active: foldersIdSelected.includes(item.id),
            'limit-width': !item.notEdit,
            'is-fullwidth': item.notEdit,
          }"
          @click="toggleFolder(item.id)"
        >
          <span class="icon-text has-text-left">
            <span class="icon">
              <template v-if="foldersIdSelected.includes(item.id)">
                <i class="fa-solid fa-folder-open fa-lg"></i>
              </template>
              <template v-else>
                <i class="fa-solid fa-folder fa-lg"></i>
              </template>
            </span>
            <span class="folder-name">
              {{ item.name }}
            </span>
          </span>
        </button>

        <!-- Editar -->
        <button
          v-if="!item.notEdit"
          class="button folder-button folder-edit-button"
          @click="showFolder(item.id)"
        >
          <span class="icon">
            <i class="fa-solid fa-pencil"></i>
          </span>
        </button>

        <!-- Eliminar -->
        <button
          v-if="!item.notDelete"
          class="button folder-button folder-delete-button"
          @click="deleteFolder(item.id)"
        >
          <span class="icon">
            <i class="fa-solid fa-trash"></i>
          </span>
        </button>
      </div>
    </template>

    <button class="button is-fullwidth folder-add-button" @click="emit('createNewFolder')">
      <span class="icon-text">
        <span class="icon">
          <i class="fa-solid fa-plus"></i>
        </span>
        <span>Añadir carpeta</span>
      </span>
    </button>
  </div>
</template>

<style scoped>
.folder-button {
  justify-content: flex-start;
  flex-wrap: wrap;
}

.limit-width {
  max-width: calc(100% - 110px);
}

.folder-name {
  white-space: normal;
  word-wrap: break-word;
  overflow-wrap: anywhere;
  margin-left: 10px;
}

.icon-text {
  display: block;
}
</style>
