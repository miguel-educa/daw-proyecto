<script setup>
import { ref } from 'vue'
import { ClipboardTools } from '@/tools/clipboard.js'
import AppLayout from '@/layouts/AppLayout.vue'
import SharedVaultComponent from '@/components/shared-vault/SharedVaultComponent.vue'
import NotificationComponent from '@/components/NotificationComponent.vue'

import '@/assets/css/forms.css'
import '@/assets/css/vault.css'

// Notificación
const notificationShow = ref(false)
const notificationSuccess = ref(false)
const notificationType = ref(null)
const notificationMessage = ref('')

/**
 * Esconde la notificación
 */
const hideNotification = () => {
  notificationShow.value = false
  notificationSuccess.value = false
  notificationType.value = null
  notificationMessage.value = ''
}

const handleCopyPassword = async (password) => {
  const result = await ClipboardTools.copyText(password)

  if (result) {
    notificationSuccess.value = true
    notificationType.value = 'is-success'
    notificationMessage.value = 'Contraseña copiada al portapapeles'
  } else {
    notificationSuccess.value = false
    notificationType.value = 'is-danger'
    notificationMessage.value = 'No se ha podido copiar la contraseña en el portapapeles'
  }

  notificationShow.value = true
}

const handleCopyUsername = async (username) => {
  const result = await ClipboardTools.copyText(username)

  if (result) {
    notificationSuccess.value = true
    notificationType.value = 'is-success'
    notificationMessage.value = 'Usuario copiado al portapapeles'
  } else {
    notificationSuccess.value = false
    notificationType.value = 'is-danger'
    notificationMessage.value = 'No se ha podido copiar el usuario en el portapapeles'
  }

  notificationShow.value = true
}

const handlePasswordDeleted = () => {
  notificationShow.value = true
  notificationSuccess.value = true
  notificationType.value = 'is-success'
  notificationMessage.value = 'Contraseña eliminada'
}

const handleDeleteError = (message) => {
  notificationShow.value = true
  notificationSuccess.value = false
  notificationType.value = 'is-danger'
  notificationMessage.value = message
}
</script>

<template>
  <AppLayout :is-user-required="true">
    <template #main>
      <!-- Notificación copiar portapapeles -->
      <NotificationComponent
        v-if="notificationShow"
        :options="{ type: notificationType }"
        :isActive="notificationShow"
        @finish="hideNotification"
      >
        <template #body>
          <!-- Notificación de éxito -->
          <template v-if="notificationSuccess">
            <span class="is-flex is-flex-direction-column is-align-items-center">
              <span class="icon">
                <i class="fa-solid fa-circle-check has-text-black fa-2x"></i>
              </span>
              <span class="has-text-centered mt-3">{{ notificationMessage }}</span>
            </span>
          </template>

          <!-- Notificación de error -->
          <template v-else>
            <span class="is-flex is-flex-direction-column is-align-items-center">
              <span class="icon">
                <i class="fa-solid fa-triangle-exclamation has-text-black fa-2x"></i>
              </span>
              <span class="has-text-centered mt-3">
                {{ notificationMessage }}
              </span>
            </span>
          </template>
        </template>
      </NotificationComponent>

      <SharedVaultComponent
        @copy-username="handleCopyUsername"
        @copy-password="handleCopyPassword"
        @password-deleted="handlePasswordDeleted"
        @delete-error="(message) => handleDeleteError(message)"
      />
    </template>
  </AppLayout>
</template>
