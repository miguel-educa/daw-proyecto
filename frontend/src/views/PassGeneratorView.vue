<script setup>
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import NotificationComponent from '@/components/NotificationComponent.vue'
import PassGeneratorComponent from '@/components/passGenerator/PassGeneratorComponent.vue'
import PassHistoryComponent from '@/components/passGenerator/PassHistoryComponent.vue'
import '@/assets/css/pass-generator.css'

const showPasswordHistory = ref(false)

const notificationShow = ref(false)
const notificationSuccess = ref(false)
const notificationType = ref(null)

/**
 * Manejador para mostrar una notificación de éxito
 */
const notificationSuccessHandler = () => {
  showNotification({
    success: true,
    type: 'is-success',
  })
}

/**
 * Manejador para mostrar una notificación de error
 */
const notificationErrorHandler = () => {
  showNotification({
    success: false,
    type: 'is-danger',
  })
}

/**
 * Muestra la notificación
 *
 * @param {{ success: boolean, type: string }} options Objeto con las opciones de la notificación
 * - `success` (`Boolean`): Indica si la notificación es de éxito (`true`) o error (`false`)
 * - `type` (`String`): Tipo de notificación (`'is-success'` o `'is-danger'`)
 */
const showNotification = (options) => {
  notificationShow.value = true
  notificationSuccess.value = options.success
  notificationType.value = options.type
}

/**
 * Esconde la notificación
 */
const hideNotification = () => {
  notificationShow.value = false
  notificationSuccess.value = false
  notificationType.value = null
}
</script>

<template>
  <AppLayout>
    <template #main>
      <!-- Historial de contraseñas -->
      <PassHistoryComponent
        v-if="showPasswordHistory"
        :modalTitle="'Historial de contraseñas'"
        @close-password-history="showPasswordHistory = false"
      />

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
              <span class="has-text-centered mt-3"> Contraseña copiada en el portapapeles </span>
            </span>
          </template>

          <!-- Notificación de error -->
          <template v-else>
            <span class="is-flex is-flex-direction-column is-align-items-center">
              <span class="icon">
                <i class="fa-solid fa-triangle-exclamation has-text-black fa-2x"></i>
              </span>
              <span class="has-text-centered mt-3">
                No se ha podido copiar la contraseña en el portapapeles
              </span>
            </span>
          </template>
        </template>
      </NotificationComponent>

      <!-- Generador -->
      <h1 class="section-title title is-1 has-text-centered is-size-3-mobile mb-6">
        Generador de contraseñas
      </h1>

      <PassGeneratorComponent
        @show-password-history="showPasswordHistory = true"
        @show-notification-success="notificationSuccessHandler"
        @show-notification-error="notificationErrorHandler"
      />
    </template>
  </AppLayout>
</template>

<style scoped>
.section-title {
  color: var(--pg-title-color);
}
</style>
