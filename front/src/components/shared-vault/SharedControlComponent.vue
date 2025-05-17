<script setup>
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'

const pStore = usePasswordStore()
const { sharedPasswordSearch, sharedPasswordType } = storeToRefs(pStore)

const resetFilter = () => {
  sharedPasswordSearch.value = ''
}

const sharedTypeHandler = (type) => {
  if (sharedPasswordType.value === type) {
    sharedPasswordType.value = ''
    return
  }

  sharedPasswordType.value = type
}
</script>

<template>
  <div class="box pass-container">
    <div class="columns">
      <div class="column is-half">
        <div class="field has-addons">
          <div class="control">
            <button
              class="button button input-button"
              title="Resetear búsqueda"
              @click="resetFilter"
            >
              <span class="icon">
                <i class="fa-solid fa-undo"></i>
              </span>
            </button>
          </div>

          <div class="control is-expanded">
            <input
              class="input input-field"
              type="text"
              placeholder="Buscar por nombre"
              v-model="sharedPasswordSearch"
            />
          </div>
        </div>
      </div>

      <div class="column is-half">
        <div class="buttons is-centered">
          <button
            class="button input-button folder-button"
            :class="{ active: sharedPasswordType === 'own' }"
            @click="sharedTypeHandler('own')"
          >
            <span class="icon-text">
              <span class="icon">
                <i class="fa-solid fa-share-from-square"></i>
              </span>
              <span>Mis contraseñas compartidas</span>
            </span>
          </button>

          <button
            class="button input-button folder-button"
            :class="{ active: sharedPasswordType === 'shared' }"
            @click="sharedTypeHandler('shared')"
          >
            <span class="icon-text">
              <span class="icon">
                <i class="fa-solid fa-download"></i>
              </span>
              <span>Contraseñas compartidas conmigo</span>
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
