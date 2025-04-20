<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import ThemeTools from '@/tools/theme.js'

const changeThemeDark = () => {
  ThemeTools.setTheme('dark')
}

const changeThemeLight = () => {
  ThemeTools.setTheme('light')
}
</script>

<template>
  <AppLayout>
    <template #main>
      <!-- Contenido del main -->
      <h1>Home</h1>
      <h4>Tema</h4>
      <button class="button is-dark" @click="changeThemeDark">Cambiar a oscuro</button>
      <button class="button is-light" @click="changeThemeLight">Cambiar a claro</button>
    </template>
  </AppLayout>
</template>
