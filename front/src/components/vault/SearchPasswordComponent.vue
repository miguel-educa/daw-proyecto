<script setup>
import { storeToRefs } from 'pinia'
import { usePasswordStore } from '@/stores/passwordStore.js'

const pStore = usePasswordStore()
const { passwordSearch } = storeToRefs(pStore)

const resetFilter = () => {
  passwordSearch.value = ''
}
</script>

<template>
  <div class="field has-addons">
    <div class="control">
      <button class="button input-button" title="Resetear búsqueda" @click="resetFilter">
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
        v-model="passwordSearch"
      />
    </div>
  </div>
</template>
