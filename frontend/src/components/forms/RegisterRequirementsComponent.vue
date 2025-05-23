<script setup>
import { defineEmits } from 'vue'

const emit = defineEmits(['closeRequeriments'])
</script>

<template>
  <div class="modal is-active">
    <div class="modal-background" @click="emit('closeRequeriments')"></div>
    <div class="modal-content">
      <div class="container" style="width: 75%">
        <div class="notification is-info mt-3">
          <h1 class="title is-5">
            <span class="icon">
              <i class="fas fa-at"></i>
            </span>
            Nombre de usuario
          </h1>
          <p>
            El <strong>nombre de usuario</strong> debe tener una longitud entre <strong>1</strong> y
            <strong>30</strong> caracteres (ambos incluidos). Sólo se admiten
            <strong>letras</strong> del alfabeto inglés, tanto <strong>minúsculas</strong> como
            <strong>mayúsculas</strong>, y <strong>barra baja</strong> (_). No puede comenzar
            directamente con una barra baja o con un número. No se diferencian las mayúsculas de
            minúsculas. No puede haber <strong>dos nombres</strong> de usuario
            <strong>iguales</strong>
          </p>
        </div>
        <div class="notification is-info mt-3">
          <h1 class="title is-5">
            <span class="icon">
              <i class="fas fa-user-tag"></i>
            </span>
            Nombre
          </h1>
          <p>
            El <strong>nombre</strong> debe tener una longitud entre <strong>1</strong> y
            <strong>50</strong> caracteres (ambos incluidos). Se admite cualquier carácter
          </p>
        </div>
        <div class="notification is-info mt-3">
          <h1 class="title is-5">
            <span class="icon">
              <i class="fas fa-lock"></i>
            </span>
            Contraseña maestra
          </h1>
          <p>
            La <strong>contraseña</strong> debe tener una longitud entre <strong>8</strong> y
            <strong>50</strong>
            caracteres. Debe contener al menos una letra
            <strong>minúscula</strong> y una letra <strong>mayúscula</strong> (alfabeto inglés), un
            <strong>número</strong> y alguno de los siguientes símbolos especiales
            <strong>_-,;!.@*&#%+$/\</strong>. No se admiten otros caracteres
          </p>
        </div>
      </div>
    </div>
    <button
      class="modal-close is-large"
      aria-label="close"
      @click="emit('closeRequeriments')"
    ></button>
  </div>
</template>

<style scoped>
i {
  color: #252525aa;
}
</style>
