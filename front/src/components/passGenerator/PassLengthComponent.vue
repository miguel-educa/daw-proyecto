<script setup>
import { storeToRefs } from 'pinia'
import { PassCharacters } from '@/tools/passGenerator.js'
import { usePasswordStore } from '@/stores/passwordStore.js'

const pStore = usePasswordStore()

// Constantes
const MIN_PASSWORD_LENGTH = PassCharacters.minPasswordLength
const MAX_PASSWORD_LENGTH = PassCharacters.maxPasswordLength

// Campos reactivos
const { passwordLength } = storeToRefs(pStore)
MIN_PASSWORD_LENGTH < pStore.getCharsMinCount() ? MIN_PASSWORD_LENGTH : pStore.getCharsMinCount()

/**
 * Cambiar longitud password mediante input
 */
const changeLengthHandler = () => {
  const length = parseInt(pStore.passwordLength)

  if (isNaN(length)) {
    const charsCount = pStore.getCharsMinCount()

    pStore.passwordLength = charsCount < MIN_PASSWORD_LENGTH ? MIN_PASSWORD_LENGTH : charsCount

    return
  }

  pStore.passwordLength = length
  pStore.validatePasswordLength()
  pStore.saveConfig()
}

/**
 * Icrementar longitud password mediante botón
 */
const incrementLengthHandler = () => {
  if (pStore.passwordLength >= MAX_PASSWORD_LENGTH) return

  pStore.passwordLength++

  pStore.saveConfig()
}

/**
 * Decrementar longitud password mediante botón
 */
const decrementLengthHandler = () => {
  if (
    pStore.passwordLength <= MIN_PASSWORD_LENGTH ||
    pStore.passwordLength <= pStore.getCharsMinCount()
  )
    return

  pStore.passwordLength--

  pStore.saveConfig()
}
</script>

<template>
  <div class="columns">
    <div class="column">
      <!-- Título -->
      <label class="label is-size-4 is-size-5-mobile has-text-centered">Longitud</label>

      <!-- Longitud -->
      <div class="field has-addons is-flex flex-direction-row is-justify-content-center">
        <!-- Botón decrementar -->
        <div class="control">
          <button
            class="button input-button is-medium"
            :class="{
              'input-button-blocked':
                passwordLength <= MIN_PASSWORD_LENGTH ||
                passwordLength <= pStore.getCharsMinCount(),
            }"
            :title="`Disminuir longitud de la contraseña`"
            @click="decrementLengthHandler"
          >
            <i class="fas fa-minus"></i>
          </button>
        </div>

        <!-- Input -->
        <div class="control">
          <input
            class="input input-field has-text-centered"
            type="text"
            v-model="passwordLength"
            @blur="changeLengthHandler"
          />
        </div>

        <!-- Botón incrementar -->
        <div class="control">
          <button
            class="button input-button is-medium"
            :class="{ 'input-button-blocked': passwordLength >= MAX_PASSWORD_LENGTH }"
            :title="`Aumentar longitud de la contraseña`"
            @click="incrementLengthHandler"
          >
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
