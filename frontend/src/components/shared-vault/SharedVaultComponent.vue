<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'
import PasswordTools from '@/tools/password.js'

import DeletePasswordComponent from '@/components/vault/DeletePasswordComponent.vue'
import PassVaultComponent from '@/components/vault/PassVaultComponent.vue'
import PasswordFormComponent from '@/components/vault/PasswordFormComponent.vue'
import SharedControlComponent from './SharedControlComponent.vue'

const pStore = usePasswordStore()
const { sharedPasswords } = storeToRefs(pStore)

const showPasswordForm = ref(false)
const showDeletePassword = ref(false)
const itemId = ref(null)

const emit = defineEmits(['copyPassword', 'copyUsername', 'passwordDeleted', 'deleteError'])

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

onMounted(async () => {
  sharedPasswords.value = await PasswordTools.getSharedPasswords()
})
</script>

<template>
  <template v-if="showDeletePassword">
    <DeletePasswordComponent
      @close="showDeletePassword = false"
      @deleted="handleItemDeleted"
      @error="(message) => handleDeleteError(message)"
      :password-id="itemId"
    />
  </template>

  <template v-if="showPasswordForm">
    <PasswordFormComponent
      :is-shared-password="true"
      @close="showPasswordForm = false"
      :password-id="itemId"
    />
  </template>

  <template v-if="showDeletePassword">
    <DeletePasswordComponent
      @close="showDeletePassword = false"
      @deleted="handleItemDeleted"
      @error="(message) => handleDeleteError(message)"
      :password-id="itemId"
    />
  </template>

  <h1 class="title is-3 has-text-centered is-hidden-desktop">Baúl compartido</h1>

  <SharedControlComponent />

  <PassVaultComponent
    :is-shared-vault="true"
    @create-new-item="handleShowNewItem"
    @show-item="(id) => handleShowItem(id)"
    @delete-item="(id) => handleDeleteItem(id)"
    @copy-username="(e) => emit('copyUsername', e)"
    @copy-password="(e) => emit('copyPassword', e)"
  />
</template>

<style scoped></style>
