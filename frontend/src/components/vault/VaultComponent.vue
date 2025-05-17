<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'
import PasswordTools from '@/tools/password.js'
import FolderTools from '@/tools/folder.js'

import DeletePasswordComponent from '@/components/vault/DeletePasswordComponent.vue'
import FoldersComponent from '@/components/vault/FoldersComponent.vue'
import FolderFormComponent from '@/components/vault/FolderFormComponent.vue'
import PassVaultComponent from '@/components/vault/PassVaultComponent.vue'
import PasswordFormComponent from '@/components/vault/PasswordFormComponent.vue'
import DeleteFolderComponent from './DeleteFolderComponent.vue'

const pStore = usePasswordStore()
const { passwords, folders } = storeToRefs(pStore)

const showPasswordForm = ref(false)
const showDeletePassword = ref(false)
const itemId = ref(null)

const showFolderForm = ref(false)
const showDeleteFolder = ref(false)
const folderId = ref(null)

const emit = defineEmits([
  'copyPassword',
  'copyUsername',
  'passwordDeleted',
  'folderDeleted',
  'deleteError',
])

const handleShowNewItem = () => {
  showPasswordForm.value = true
  itemId.value = null
}

const handleDeleteItem = (id) => {
  showDeletePassword.value = true
  itemId.value = id
}

const handleShowItem = (id) => {
  showPasswordForm.value = true
  itemId.value = id
}

const handleItemDeleted = () => {
  showDeletePassword.value = false
  itemId.value = null
  emit('passwordDeleted')
}

const handleDeleteError = (message) => {
  showDeletePassword.value = false
  itemId.value = null
  emit('deleteError', message)
}

const handleShowNewFolder = () => {
  showFolderForm.value = true
  folderId.value = null
}

const handleShowFolder = (id) => {
  showFolderForm.value = true
  folderId.value = id
}

const handleDeleteFolder = (id) => {
  showDeleteFolder.value = true
  folderId.value = id
}

const handleFolderDeleted = () => {
  showDeleteFolder.value = false
  folderId.value = null
  emit('folderDeleted')
}

onMounted(async () => {
  folders.value = await FolderTools.getFolders()
  passwords.value = await PasswordTools.getPasswords()
})
</script>

<template>
  <template v-if="showPasswordForm">
    <PasswordFormComponent @close="showPasswordForm = false" :password-id="itemId" />
  </template>

  <template v-if="showDeletePassword">
    <DeletePasswordComponent
      @close="showDeletePassword = false"
      @deleted="handleItemDeleted"
      @error="(message) => handleDeleteError(message)"
      :password-id="itemId"
    />
  </template>

  <template v-if="showFolderForm">
    <FolderFormComponent @close="showFolderForm = false" :folder-id="folderId" />
  </template>

  <template v-if="showDeleteFolder">
    <DeleteFolderComponent
      @close="showDeleteFolder = false"
      @deleted="handleFolderDeleted"
      @error="(message) => handleDeleteError(message)"
      :folder-id="folderId"
    />
  </template>

  <h1 class="title is-3 has-text-centered is-hidden-desktop">Baúl personal</h1>

  <div class="columns is-desktop is-5">
    <div class="column is-full-touch is-one-third">
      <FoldersComponent
        @create-new-folder="handleShowNewFolder"
        @show-folder="(id) => handleShowFolder(id)"
        @delete-folder="(id) => handleDeleteFolder(id)"
      />
    </div>

    <div class="column is-full-touch">
      <PassVaultComponent
        @create-new-item="handleShowNewItem"
        @show-item="(id) => handleShowItem(id)"
        @delete-item="(id) => handleDeleteItem(id)"
        @copy-username="(e) => emit('copyUsername', e)"
        @copy-password="(e) => emit('copyPassword', e)"
      />
    </div>
  </div>
</template>

<style scoped></style>
