<script setup>
import { ref } from 'vue'
import { PassCharacters } from '@/tools/passGenerator.js'
import { usePasswordStore } from '@/stores/passwordStore.js'
import { ClipboardTools } from '@/tools/clipboard.js'
import PassLengthComponent from './PassLengthComponent.vue'
import CharMinComponent from './CharControlsComponent.vue'

const pStore = usePasswordStore()

// Campos reactivos
const password = ref('')

// Emits
const emit = defineEmits([
  'showPasswordHistory',
  'showNotificationSuccess',
  'showNotificationError',
])

/**
 * Generador de password
 */
const generatePassword = () => {
  const pass = [] // Caracteres password
  const chars = [] // Caracteres disponibles

  // Añadir caracteres disponibles y mínimos
  const addRandomChars = (type, count) => {
    if (count == 0) return

    const currentChars = PassCharacters.getByType(type)
    chars.push(...currentChars)

    for (let i = 0; i < count; i++) {
      pass.push(currentChars[Math.floor(Math.random() * currentChars.length)])
    }
  }

  addRandomChars('lower', pStore.lowerCharCount)
  addRandomChars('upper', pStore.upperCharCount)
  addRandomChars('number', pStore.numberCharCount)
  addRandomChars('special', pStore.specialCharCount)

  // Rellenar password faltante
  while (pass.length < pStore.passwordLength) {
    pass.push(chars[Math.floor(Math.random() * chars.length)])
  }

  // Desordenar y convertir a String
  pass.sort(() => Math.random() - 0.5)
  password.value = pass.join('')

  // Almacenar en historial
  pStore.addHistory(password.value)
}

/**
 * Copiar al portapapeles
 */
const copyPasswordHandler = () => {
  ClipboardTools.copyText(password.value)
    .then((result) => {
      if (!result) throw new Error('Error al copiar al portapapeles')
      emit('showNotificationSuccess')
    })
    .catch((e) => {
      console.error(e)
      emit('showNotificationError')
    })
}

// Generar contraseña al inicio
generatePassword()
</script>

<template>
  <div class="container pass-generator-container p-5">
    <!-- Título -->
    <label class="label is-size-4 is-size-5-mobile has-text-centered">Contraseña generada</label>

    <!-- Contraseña generada-->
    <div class="field has-addons">
      <div class="control is-expanded">
        <input class="input input-field code-font" type="text" v-model="password" readonly />
      </div>

      <!-- Botón copiar -->
      <div class="control">
        <button
          class="button input-button is-medium"
          title="Copiar contraseña al portapapeles"
          @click="copyPasswordHandler"
        >
          <i class="fas fa-copy"></i>
        </button>
      </div>
    </div>

    <!-- Botón generar -->
    <div class="buttons is-centered">
      <button
        class="button input-button"
        title="Generar nueva contraseña"
        @click="generatePassword"
      >
        <span class="icon-text">
          <span class="icon">
            <i class="fa-solid fa-repeat"></i>
          </span>
          <span>Generar contraseña</span>
        </span>
      </button>

      <!-- Botón historial -->
      <button
        class="button input-button history-button"
        title="Ver historial de contraseñas generadas"
        @click="emit('showPasswordHistory')"
      >
        <span class="icon">
          <i class="fa-solid fa-clock-rotate-left"></i>
        </span>
      </button>
    </div>

    <hr />

    <PassLengthComponent />

    <CharMinComponent />
  </div>
</template>
