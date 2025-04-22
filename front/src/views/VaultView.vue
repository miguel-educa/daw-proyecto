<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore'
import { ClipboardTools } from '@/tools/clipboard'
import AppLayout from '@/layouts/AppLayout.vue'
import ModalComponent from '@/components/ModalComponent.vue'
import NotificationComponent from '@/components/NotificationComponent.vue'
import VaultComponent from '@/components/vault/VaultComponent.vue'

const uStore = useUserStore()

// Variables reactivas
const { user } = storeToRefs(uStore)

// Modal de recuperación de cuenta
const showRecoveryModal = ref(false)
const recCode = ref(null)

// Notificación
const notificationShow = ref(false)
const notificationSuccess = ref(false)
const notificationType = ref(null)

/**
 * Cierra el modal de recuperación
 */
const closeRecoveryModalHandlet = () => {
  showRecoveryModal.value = false
  sessionStorage.removeItem('recuperation-code')
}

/**
 * Copia el código de recuperación al portapapeles
 */
const copyRecoveryCodeHandler = async () => {
  const result = await ClipboardTools.copyText(recCode.value)

  if (result) {
    notificationSuccess.value = true
    notificationType.value = 'is-success'
  } else {
    notificationSuccess.value = false
    notificationType.value = 'is-danger'
  }
  notificationShow.value = true
}

/**
 * Esconde la notificación
 */
const hideNotification = () => {
  notificationShow.value = false
  notificationSuccess.value = false
  notificationType.value = null
}

onMounted(() => {
  recCode.value = sessionStorage.getItem('recuperation-code')
  if (recCode.value) {
    showRecoveryModal.value = true
  }
})
</script>

<template>
  <AppLayout :is-user-required="true">
    <template #main>
      <!-- Modal recovery code -->
      <ModalComponent
        v-if="showRecoveryModal"
        :modal-title="`¡Bienvenido ${user.name}!`"
        @close-modal="closeRecoveryModalHandlet"
      >
        <template #modal-body>
          <h1 class="title is-4">Código de recuperación</h1>
          <section>
            <p class="subtitle is-6">Su código de recuperación de cuenta es:</p>

            <p class="subtitle is-6 has-text-centered">
              <strong class="code-font is-size-4">{{ recCode }}</strong>
            </p>

            <p class="subtitle is-6 has-text-centered">
              <button class="button is-link is-medium" @click="copyRecoveryCodeHandler">
                Copiar código
              </button>
            </p>

            <p class="subtitle is-6">
              ¡<strong class="is-size-5">Guárdelo</strong> en un lugar seguro! Es la única forma de
              recuperar su cuenta. Podrá verlo siempre que desee desde los
              <strong>ajustes</strong> de la cuenta
            </p>
          </section>
        </template>
      </ModalComponent>

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
              <span class="has-text-centered mt-3">Código copiado en el portapapeles</span>
            </span>
          </template>

          <!-- Notificación de error -->
          <template v-else>
            <span class="is-flex is-flex-direction-column is-align-items-center">
              <span class="icon">
                <i class="fa-solid fa-triangle-exclamation has-text-black fa-2x"></i>
              </span>
              <span class="has-text-centered mt-3">
                No se ha podido copiar el código en el portapapeles
              </span>
            </span>
          </template>
        </template>
      </NotificationComponent>

      <!-- Baúl -->
      <VaultComponent />
    </template>
  </AppLayout>
</template>
