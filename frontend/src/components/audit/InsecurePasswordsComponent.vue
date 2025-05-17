<script setup>
import { ref } from 'vue'
import PasswordTools from '@/tools/password.js'

const showResults = ref(false)
const isLoading = ref(false)
const securityRating = ref([])

const checkSecurityPasswords = async () => {
  isLoading.value = true

  securityRating.value = await PasswordTools.getSecurityPasswords()
  console.log(securityRating.value)

  isLoading.value = false
  showResults.value = true
}
</script>

<template>
  <div class="box no-padding audit-container">
    <h1 class="title is-3 is-size-4-touch has-text-centered mt-3 audit-title">
      <span class="icon-text">
        <span class="icon">
          <i class="fa-solid fa-shield-halved"></i>
        </span>
        <span class="ml-3">Seguridad de contraseñas</span>
      </span>
    </h1>

    <template v-if="showResults">
      <template v-if="securityRating.length > 0">
        <template v-for="[secRating, passwords] in securityRating" :key="secRating">
          <div class="column no-padding">
            <h1
              class="is-size-3 is-size-4-touch has-text-centered audit-text"
              :class="{ 'mt-6': secRating != 1 }"
            >
              <span class="icon">
                <i v-if="secRating == 1" class="fa-solid fa-circle-exclamation has-text-danger"></i>
                <i
                  v-else-if="secRating == 2"
                  class="fa-solid fa-triangle-exclamation has-text-warning"
                ></i>
                <i v-else class="fa-solid fa-square-check has-text-success"></i>
              </span>

              Seguridad
              <span
                :class="{
                  'has-text-danger': secRating == 1,
                  'has-text-warning': secRating == 2,
                  'has-text-success': secRating == 3,
                }"
                >{{ secRating == 1 ? 'baja' : secRating == 2 ? 'media' : 'alta' }}</span
              >
              <span class="ml-2">({{ passwords.length }})</span>
            </h1>

            <template v-if="passwords.length > 0">
              <div class="columns is-multiline mt-4">
                <template v-for="([password, items, rating], index) in passwords" :key="password">
                  <div class="column is-half">
                    <div class="box audit-container">
                      <h1 class="is-size-4 audit-text">
                        {{ index + 1 }}. <span class="code-font">{{ password }}</span>
                      </h1>
                      <div class="my-3">
                        <h1 class="is-size-5">
                          <span class="icon-text">
                            <span class="icon">
                              <i class="fa-solid fa-rectangle-list icon-text-color"></i>
                            </span>
                            <span class="audit-text">Resumen</span>
                          </span>
                        </h1>

                        <ul>
                          <li class="ml-3 audit-text">
                            <span class="icon-text">
                              <span class="icon">
                                <i
                                  v-if="rating.length === 1"
                                  class="fa-solid fa-circle-exclamation fa-lg has-text-danger"
                                ></i>
                                <i
                                  v-else-if="rating.length === 2"
                                  class="fa-solid fa-triangle-exclamation fa-lg has-text-warning"
                                ></i>
                                <i
                                  v-else
                                  class="fa-solid fa-square-check fa-lg has-text-success"
                                ></i>
                              </span>
                              <span>Nº de caracteres: {{ password.length }}</span>
                            </span>
                          </li>
                          <li class="ml-3 audit-text">
                            <span v-if="rating.hasLower" class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-square-check fa-lg has-text-success"></i>
                              </span>
                              <span>Contiene minúsculas</span>
                            </span>
                            <span v-else class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-circle-exclamation fa-lg has-text-danger"></i>
                              </span>
                              <span>No contiene 2 o más minúsculas</span>
                            </span>
                          </li>
                          <li class="ml-3 audit-text">
                            <span v-if="rating.hasUpper" class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-square-check fa-lg has-text-success"></i>
                              </span>
                              <span>Contiene mayúsculas</span>
                            </span>
                            <span v-else class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-circle-exclamation fa-lg has-text-danger"></i>
                              </span>
                              <span>No contiene 2 o más mayúsculas</span>
                            </span>
                          </li>
                          <li class="ml-3 audit-text">
                            <span v-if="rating.hasNumber" class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-square-check fa-lg has-text-success"></i>
                              </span>
                              <span>Contiene números</span>
                            </span>
                            <span v-else class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-circle-exclamation fa-lg has-text-danger"></i>
                              </span>
                              <span>No contiene 2 o más números</span>
                            </span>
                          </li>
                          <li class="ml-3 audit-text">
                            <span v-if="rating.hasSpecial" class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-square-check fa-lg has-text-success"></i>
                              </span>
                              <span>Contiene caracteres especiales</span>
                            </span>
                            <span v-else class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-circle-exclamation fa-lg has-text-danger"></i>
                              </span>
                              <span class="is-size-7-touch"
                                >No contiene 2 o más caracteres especiales</span
                              >
                            </span>
                          </li>
                          <li class="ml-3 audit-text">
                            <span v-if="rating.hasRepeatedChar" class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-circle-exclamation fa-lg has-text-danger"></i>
                              </span>
                              <span>Contiene caracteres repetidos</span>
                            </span>
                            <span v-else class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-square-check fa-lg has-text-success"></i>
                              </span>
                              <span>No contiene caracteres repetidos</span>
                            </span>
                          </li>
                          <li class="ml-3 audit-text">
                            <span v-if="rating.hasCharSecuences" class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-circle-exclamation fa-lg has-text-danger"></i>
                              </span>
                              <span>Contiene secuencias de caracteres</span>
                            </span>
                            <span v-else class="icon-text">
                              <span class="icon">
                                <i class="fa-solid fa-square-check fa-lg has-text-success"></i>
                              </span>
                              <span class="is-size-7-touch"
                                >No contiene secuencias de caracteres</span
                              >
                            </span>
                          </li>
                        </ul>
                      </div>

                      <h1 class="is-size-5 audit-text">Utilizada en</h1>
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
                  </div>
                </template>
              </div>
            </template>
            <template v-else>
              <p class="has-text-centered is-size-5">No hay contenido en esta sección</p>
            </template>
          </div>
        </template>
      </template>
      <template v-else>
        <p>No se encontraron contraseñas filtradas</p>
      </template>
    </template>

    <div class="buttons is-centered" :class="{ 'mt-5': showResults }">
      <button
        class="button audit-button"
        :class="{ 'is-loading': isLoading }"
        @click="checkSecurityPasswords"
      >
        {{ showResults ? 'Volver a realizar informe' : 'Realizar informe' }}
      </button>
    </div>
  </div>
</template>

<style scoped>
@media screen and (max-width: 1024px) {
  .no-padding {
    padding-left: 0.3rem !important;
    padding-right: 0.3rem !important;
  }
}

h1 {
  word-break: break-word;
}
</style>
