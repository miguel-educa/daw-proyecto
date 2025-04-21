<script setup>
import { storeToRefs } from 'pinia'
import { PassCharacters } from '@/tools/passGenerator.js'
import { usePasswordStore } from '@/stores/passwordStore.js'

const pStore = usePasswordStore()

// Constantes
const MAX_CHARS_MIN_COUNT = PassCharacters.maxCharsMinCount

// Campos reactivos
const { lowerCharCount, upperCharCount, numberCharCount, specialCharCount } = storeToRefs(pStore)

// Objeto char counts
const charCountsObject = {
  lower: lowerCharCount,
  upper: upperCharCount,
  number: numberCharCount,
  special: specialCharCount,
}

/**
 * Validar contador caracteres mínimos
 *
 * @param {Number} value Valor actual
 */
const validateMinCharCount = (value) => {
  const val = parseInt(value)

  if (isNaN(val) || val < 0) return 0
  if (val > MAX_CHARS_MIN_COUNT) return MAX_CHARS_MIN_COUNT

  return val
}

/**
 * Forzar una minúscula como caracter mínimo
 */
const checkCharMin = () => {
  if (
    lowerCharCount.value === 0 &&
    upperCharCount.value === 0 &&
    numberCharCount.value === 0 &&
    specialCharCount.value === 0
  ) {
    lowerCharCount.value = 1
    document.querySelector('#min-char-lower').checked = true
  }

  pStore.validatePasswordLength()
  pStore.saveConfig()
}

/**
 * Cambiar contador de caracteres mínimos
 *
 * @param {Number} value Valor actual
 * @param {Object} charObject Objeto de caracteres
 */
const changeCharCountHandler = (value, charObject) => {
  charObject.value = validateMinCharCount(parseInt(value))

  checkCharMin()
}

/**
 * Incrementar contador de caracteres mínimos
 *
 * @param {Object} charObject Objeto de caracteres
 */
const icrementCharHandler = (charObject) => {
  if (charObject.value < MAX_CHARS_MIN_COUNT) charObject.value++

  checkCharMin()
}

/**
 * Decrementar contador de caracteres mínimos
 *
 * @param {Object} charObject Objeto de caracteres
 */
const decrementCharHandler = (charObject) => {
  if (charObject.value > 0) charObject.value--

  checkCharMin()
}
</script>

<template>
  <section class="is-flex is-flex-direction-column is-align-items-center mt-6">
    <div class="character-options">
      <!-- Título -->
      <h1 class="has-text-centered is-size-3 is-size-4-mobile">Caracteres mínimos</h1>

      <hr />

      <div class="columns">
        <!-- Minúsculas -->
        <div class="column">
          <!-- Título y checkbox -->
          <label class="label is-size-4 is-size-5-mobile label-checkbox">
            <input
              type="checkbox"
              id="min-char-lower"
              :checked="lowerCharCount > 0"
              @change="
                (event) =>
                  changeCharCountHandler(event.target.checked ? 1 : 0, charCountsObject.lower)
              "
            />
            <span>Minúsculas</span>
          </label>

          <!-- Botones e input -->
          <div class="field has-addons is-flex flex-direction-row is-justify-content-center">
            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{
                  'input-button-blocked': lowerCharCount <= 0 || pStore.getCharsMinCount() === 1,
                }"
                :title="`Disminuir cantidad mínima de caracteres en minúsculas`"
                @click="decrementCharHandler(charCountsObject.lower)"
              >
                <i class="fas fa-minus"></i>
              </button>
            </div>

            <div class="control">
              <input
                class="input input-field input-short has-text-centered"
                type="text"
                v-model="lowerCharCount"
                @blur="
                  (event) => changeCharCountHandler(event.target.value, charCountsObject.lower)
                "
              />
            </div>

            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{
                  'input-button-blocked': lowerCharCount >= MAX_CHARS_MIN_COUNT,
                }"
                :title="`Aumentar cantidad mínima de caracteres en minúsculas`"
                @click="icrementCharHandler(charCountsObject.lower)"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Mayúsculas -->
        <div class="column">
          <!-- Título y checkbox -->
          <label class="label is-size-4 is-size-5-mobile label-checkbox">
            <input
              type="checkbox"
              :checked="upperCharCount > 0"
              @change="
                (event) =>
                  changeCharCountHandler(event.target.checked ? 1 : 0, charCountsObject.upper)
              "
            />
            <span>Mayúsculas</span>
          </label>

          <!-- Botones e input -->
          <div class="field has-addons is-flex flex-direction-row is-justify-content-center">
            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{ 'input-button-blocked': upperCharCount <= 0 }"
                :title="`Disminuir cantidad mínima de caracteres en mayúsculas`"
                @click="decrementCharHandler(charCountsObject.upper)"
              >
                <i class="fas fa-minus"></i>
              </button>
            </div>

            <div class="control">
              <input
                class="input input-field input-short has-text-centered"
                type="text"
                v-model="upperCharCount"
                @blur="
                  (event) => changeCharCountHandler(event.target.value, charCountsObject.upper)
                "
              />
            </div>

            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{
                  'input-button-blocked': upperCharCount >= MAX_CHARS_MIN_COUNT,
                }"
                :title="`Aumentar cantidad mínima de caracteres en mayúsculas`"
                @click="icrementCharHandler(charCountsObject.upper)"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="columns">
        <!-- Números -->
        <div class="column">
          <!-- Título y checkbox -->
          <label class="label is-size-4 is-size-5-mobile label-checkbox">
            <input
              type="checkbox"
              :checked="numberCharCount > 0"
              @change="
                (event) =>
                  changeCharCountHandler(event.target.checked ? 1 : 0, charCountsObject.number)
              "
            />
            <span>Números</span>
          </label>

          <!-- Botones e input -->
          <div class="field has-addons is-flex flex-direction-row is-justify-content-center">
            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{ 'input-button-blocked': numberCharCount <= 0 }"
                :title="`Disminuir cantidad mínima de números`"
                @click="decrementCharHandler(charCountsObject.number)"
              >
                <i class="fas fa-minus"></i>
              </button>
            </div>

            <div class="control">
              <input
                class="input input-field input-short has-text-centered"
                type="text"
                v-model="numberCharCount"
                @blur="
                  (event) => changeCharCountHandler(event.target.value, charCountsObject.number)
                "
              />
            </div>

            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{
                  'input-button-blocked': numberCharCount >= MAX_CHARS_MIN_COUNT,
                }"
                :title="`Aumentar cantidad mínima de números`"
                @click="icrementCharHandler(charCountsObject.number)"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Caracteres especiales -->
        <div class="column">
          <!-- Título y checkbox -->
          <label
            class="label is-size-4 is-size-5-mobile label-checkbox"
            :title="`Caracreres especiales: ${PassCharacters.getByType('special').join('')}`"
          >
            <input
              type="checkbox"
              :checked="specialCharCount > 0"
              @change="
                (event) =>
                  changeCharCountHandler(event.target.checked ? 1 : 0, charCountsObject.special)
              "
            />
            <span>Especiales</span>
          </label>

          <!-- Botones e input -->
          <div class="field has-addons is-flex flex-direction-row is-justify-content-center">
            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{ 'input-button-blocked': specialCharCount <= 0 }"
                :title="`Disminuir cantidad mínima de caracteres especiales`"
                @click="decrementCharHandler(charCountsObject.special)"
              >
                <i class="fas fa-minus"></i>
              </button>
            </div>

            <div class="control">
              <input
                class="input input-field input-short has-text-centered"
                type="text"
                v-model="specialCharCount"
                @blur="
                  (event) => changeCharCountHandler(event.target.value, charCountsObject.special)
                "
              />
            </div>

            <div class="control">
              <button
                class="button input-button is-medium"
                :class="{
                  'input-button-blocked': specialCharCount >= MAX_CHARS_MIN_COUNT,
                }"
                :title="`Aumentar cantidad mínima de caracteres especiales`"
                @click="icrementCharHandler(charCountsObject.special)"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
