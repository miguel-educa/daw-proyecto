<script setup>
import { onMounted, onBeforeUnmount } from 'vue'
import { storeToRefs } from 'pinia'
import { useRoute } from 'vue-router'
import { usePasswordStore } from '@/stores/passwordStore.js'
import SearchPasswordComponent from './SearchPasswordComponent.vue'

const route = useRoute()
const passId = route.query.passId

const pStore = usePasswordStore()
const { passwordsFiltered, folders, sharedPasswordsFiltered } = storeToRefs(pStore)

defineProps({
  isSharedVault: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits([
  'createNewItem',
  'showItem',
  'deleteItem',
  'copyPassword',
  'copyUsername',
])

const toggleOptions = (id) => {
  closeOptions(id)

  const dropdown = document.querySelector(`#options-${id}`)
  dropdown.classList.toggle('is-active')

  // Mostrar hacia arriba o hacia abajo
  const dropdownMenu = dropdown.querySelector('.dropdown-menu')
  const rect = dropdown.getBoundingClientRect()
  const menuHeight = dropdownMenu.offsetHeight
  const spaceBelow = window.innerHeight - rect.bottom

  console.log(
    'rect:',
    rect,
    'menuHeight:',
    menuHeight,
    'spaceBelow:',
    spaceBelow,
    'spaceBelow < menuHeight:',
    spaceBelow < menuHeight,
  )

  if (spaceBelow < menuHeight) {
    dropdown.classList.add('is-up')
  } else {
    dropdown.classList.remove('is-up')
  }
}

const closeOptions = (excludeId) => {
  const dropdowns = document.querySelectorAll('.is-active')

  dropdowns.forEach((dropdown) => {
    if (dropdown.id === `options-${excludeId}`) return

    dropdown.classList.remove('is-active')
  })
}

const handleCopy = (eventName, text) => {
  closeOptions()
  emit(eventName, text)
}

const showItem = (id) => {
  closeOptions()
  emit('showItem', id)
}

const deleteItem = (id) => {
  closeOptions()
  emit('deleteItem', id)
}

const handleClickOutside = (event) => {
  const dropdowns = document.querySelectorAll('.dropdown.is-active')
  dropdowns.forEach((dropdown) => {
    if (!dropdown.contains(event.target)) {
      dropdown.classList.remove('is-active')
    }
  })
}

onMounted(() => {
  if (passId) setTimeout(() => emit('showItem', passId), 1500)
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <div class="box pass-container">
    <h1 class="title is-3 has-text-centered is-hidden-touch">
      Baúl {{ isSharedVault ? 'compartido' : 'personal' }}
    </h1>

    <template v-if="!isSharedVault">
      <SearchPasswordComponent />
    </template>

    <div class="has-text-centered">
      <button
        class="button mb-2 pass-add-button"
        :class="{ 'mt-5': !isSharedVault }"
        @click="emit('createNewItem')"
      >
        <span class="icon-text">
          <span class="icon">
            <i class="fa-solid fa-plus"></i>
          </span>
          <span>Crear nuevo elemento</span>
        </span>
      </button>
    </div>

    <table class="table is-hoverable is-fullwidth">
      <thead>
        <tr>
          <th class="small-column"></th>
          <th></th>
          <th class="small-column"></th>
        </tr>
      </thead>

      <tbody>
        <template
          v-for="item in isSharedVault ? sharedPasswordsFiltered : passwordsFiltered"
          :key="item.id"
        >
          <tr>
            <td class="has-text-centered small-column">
              <span class="icon is-large">
                <template v-if="item.urls">
                  <i class="is-hidden-desktop fa-solid icon-text-color fa-globe fa-lg"></i>
                  <i class="is-hidden-touch fa-solid icon-text-color fa-globe fa-2x"></i>
                </template>
                <template v-else>
                  <i class="is-hidden-desktop icon-text-color fa-solid fa-lock fa-lg"></i>
                  <i class="is-hidden-touch icon-text-color fa-solid fa-lock fa-2x"></i>
                </template>
              </span>
            </td>

            <td>
              <p class="title is-5 is-size-6-mobile table-text">
                <span class="click" @click="showItem(item.id)">{{ item.name }}</span>
              </p>
              <p class="subtitle is-6 is-size-7-mobile mt-2">
                <template v-if="isSharedVault">
                  <template v-if="!item.is_owner"
                    >Compartida por <strong>{{ item.owner_name }}</strong> (@{{
                      item.owner_username
                    }})</template
                  >
                </template>
                <template v-else>
                  {{ folders.find((folder) => folder.id === item.folder_id).name }}
                </template>
              </p>
            </td>

            <td class="has-text-centered small-column">
              <div class="dropdown is-right is-upp" :id="`options-${item.id}`">
                <div class="dropdown-trigger">
                  <span class="icon is-large click" @click="toggleOptions(item.id)">
                    <i
                      class="is-hidden-desktop fa-solid fa-ellipsis-vertical fa-2x icon-text-color"
                    ></i>
                    <i class="is-hidden-touch fa-solid fa-ellipsis fa-2x icon-text-color"></i>
                  </span>
                </div>
                <div class="dropdown-menu" role="menu">
                  <div class="dropdown-content">
                    <a
                      v-if="item.username"
                      class="dropdown-item"
                      @click="handleCopy('copyUsername', item.username)"
                    >
                      <span>Copiar usuario</span>
                      <span class="icon-text">
                        <span class="icon">
                          <i class="fa-solid fa-user"></i>
                        </span>
                      </span>
                    </a>

                    <a
                      v-if="item.password"
                      class="dropdown-item"
                      @click="handleCopy('copyPassword', item.password)"
                    >
                      <span>Copiar contraseña</span>
                      <span class="icon-text">
                        <span class="icon">
                          <i class="fa-solid fa-lock"></i>
                        </span>
                      </span>
                    </a>

                    <a
                      v-if="item.urls"
                      class="dropdown-item"
                      :href="item.urls[0]"
                      target="_blank"
                      @click="closeOptions()"
                    >
                      <span>Abrir URL</span>
                      <span class="icon-text">
                        <span class="icon">
                          <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </span>
                      </span>
                    </a>

                    <a class="dropdown-item" @click="showItem(item.id)">
                      <span>Ver detalles</span>
                    </a>

                    <template v-if="!isSharedVault || item.is_owner">
                      <hr class="dropdown-divider" />

                      <a class="dropdown-item delete-item" @click="deleteItem(item.id)">
                        <span class="icon-text">
                          <span class="icon">
                            <i class="fa-solid fa-trash delete-item"></i>
                          </span>
                          <span>Eliminar</span>
                        </span>
                      </a>
                    </template>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.column {
  border: 1px solid white;
}

.small-column {
  width: 50px;
}

.click {
  cursor: pointer;
}

.delete-item {
  color: red;
}

.dropdown-item {
  padding-inline-end: 0;
}

.dropdown-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 10;
}
</style>
