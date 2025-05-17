<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import UserTools from '@/tools/user.js'
import AppLayout from '@/layouts/AppLayout.vue'
import PreferenceComponent from '@/components/settings/PreferenceComponent.vue'
import AccountComponent from '@/components/settings/AccountComponent.vue'
import NotificationComponent from '@/components/NotificationComponent.vue'
import ModalComponent from '@/components/ModalComponent.vue'
import '@/assets/css/forms.css'
import '@/assets/css/settings.css'

const router = useRouter()

const showUserUpdateNotification = ref(false)
const userUpdateNotificationMessage = ref('')
const userUpdateNotificationSuccess = ref(false)

const showDeleteModal = ref(false)
const deleteType = ref(null)
const deleteIsLoading = ref(false)

const showUserUpdateNotificationHandler = (message, isError = false) => {
  showUserUpdateNotification.value = true
  userUpdateNotificationMessage.value = message
  userUpdateNotificationSuccess.value = !isError
}

const hideNotification = () => {
  showUserUpdateNotification.value = false
  userUpdateNotificationMessage.value = ''
  userUpdateNotificationSuccess.value = false
}

const showDeleteModalHandler = (type) => {
  console.log('delete', type)
  const types = ['account', 'vault', 'shared-vault']

  if (!types.includes(type)) return
  showDeleteModal.value = true
  deleteType.value = type
}

const closeDeleteModalHandler = () => {
  showDeleteModal.value = false
  deleteType.value = null
}

const deleteHandler = async () => {
  deleteIsLoading.value = true
  const result = await UserTools.delete(deleteType.value)
  deleteIsLoading.value = false

  if (deleteType.value === 'account' && result) {
    router.push('/login')
    sessionStorage.setItem('anonymous-user', 'true')
    return
  }

  if (!result) {
    showUserUpdateNotificationHandler('Se ha producido un error al vaciar el baúl', true)
    return
  }

  showUserUpdateNotificationHandler('Baúl vaciado correctamente')
  closeDeleteModalHandler()
  deleteType.value = null
}
</script>

<template>
  <AppLayout :is-user-required="true">
    <template #main>
      <!-- Notificación actualización -->
      <NotificationComponent
        v-if="showUserUpdateNotification"
        :options="{ type: userUpdateNotificationSuccess ? 'is-success' : 'is-danger' }"
        :isActive="showUserUpdateNotification"
        @finish="hideNotification"
      >
        <template #body>
          <template v-if="userUpdateNotificationSuccess">
            <span class="is-flex is-flex-direction-column is-align-items-center">
              <span class="icon">
                <i class="fa-solid fa-circle-check has-text-black fa-2x"></i>
              </span>
              <span class="has-text-centered mt-3">{{ userUpdateNotificationMessage }}</span>
            </span>
          </template>

          <template v-else>
            <span class="is-flex is-flex-direction-column is-align-items-center">
              <span class="icon">
                <i class="fa-solid fa-triangle-exclamation has-text-black fa-2x"></i>
              </span>
              <span class="has-text-centered mt-3">{{ userUpdateNotificationMessage }}</span>
            </span>
          </template>
        </template>
      </NotificationComponent>

      <!-- Modal de confirmación de eliminación -->
      <ModalComponent
        v-if="showDeleteModal"
        :modal-title="`${deleteType === 'account' ? 'Eliminar cuenta' : deleteType === 'vault' ? 'Vaciar baúl personal' : 'Vaciar baúl compartido'}`"
        @close-modal="closeDeleteModalHandler"
      >
        <template #modal-body>
          <h1 class="title is-4">
            <template v-if="deleteType === 'account'">Eliminar cuenta</template>
            <template v-else-if="deleteType === 'vault'">Vaciar baúl personal</template>
            <template v-else-if="deleteType === 'shared-vault'"> Vaciar baúl compartido </template>
          </h1>
          <section>
            <p class="subtitle is-6">
              <template v-if="deleteType === 'account'">
                ¿Desea <strong>eliminar</strong> la cuenta? Todos los datos, incluyendo carpetas y
                contraseñas, serán <strong>eliminados permanentemente</strong> y no se podrán
                recuperar. Tampoco se podrá iniciar sesión en esta cuenta.
              </template>

              <template v-if="deleteType === 'vault'">
                ¿Desea <strong>vaciar</strong> el baúl personal? Todas las carpetas y contraseñas
                serán <strong>eliminadas permanentemente</strong> y no se podrán recuperar.
              </template>

              <template v-if="deleteType === 'shared-vault'">
                ¿Desea <strong>vaciar</strong> el baúl compartido? Todas las contraseñas compartidas
                serán <strong>eliminadas permanentemente</strong> y no se podrán recuperar. Tanto
                usted como los usuarios compartidos perderán el acceso a ellas. Las contraseñas que
                otros usuarios hayan compartido con usted seguirán disponibles.
              </template>
            </p>

            <p class="subtitle is-6 has-text-centered">
              <button class="button is-danger" @click="deleteHandler">
                <template v-if="deleteType === 'account'">Confirmar eliminación</template>
                <template v-else>Confirmar y vaciar</template>
              </button>
            </p>
          </section>
        </template>
      </ModalComponent>

      <PreferenceComponent />

      <AccountComponent
        @user-updated="showUserUpdateNotificationHandler"
        @delete="showDeleteModalHandler"
      />
    </template>
  </AppLayout>
</template>
