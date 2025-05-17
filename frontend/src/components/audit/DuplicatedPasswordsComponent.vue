<script setup>
import { ref } from 'vue'
import PasswordTools from '@/tools/password.js'

const showResults = ref(false)
const isLoading = ref(false)
const passwords = ref({})

const checkDuplicatedPasswords = async () => {
  isLoading.value = true

  passwords.value = await PasswordTools.getPasswordsGroupByPassword(true)

  isLoading.value = false
  showResults.value = true
}
</script>

<template>
  <div class="box audit-container">
    <h1 class="title is-3 is-size-5-touch has-text-centered mt-3">
      <span class="icon-text">
        <span class="icon">
          <i class="fa-solid fa-list-ol"></i>
        </span>
        <span class="ml-3">Contraseñas repetidas</span>
      </span>
    </h1>

    <template v-if="showResults">
      <div class="mt-4">
        <template v-if="passwords.length > 0">
          <template v-for="[password, items] in passwords" :key="password">
            <div class="box audit-container">
              <h1 class="is-size-4 audit-text code-font">
                {{ password }}
              </h1>

              <div class="content">
                <ul>
                  <li v-for="item in items" :key="item.id">
                    <a target="_blank" :href="`/vault?passId=${item.id}`">
                      {{ item.name }} ({{ item.folder.name }})
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </template>
        </template>

        <template v-else>
          <p>No se encontraron contraseñas repetidas</p>
        </template>
      </div>
    </template>

    <div class="buttons is-centered" :class="{ 'mt-5': showResults }">
      <button
        class="button audit-button"
        :class="{ 'is-loading': isLoading }"
        @click="checkDuplicatedPasswords"
      >
        {{ showResults ? 'Volver a comprobar' : 'Comprobar' }}
      </button>
    </div>
  </div>
</template>

<style scoped>
h1 {
  word-break: break-word;
}
</style>
