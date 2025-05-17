<script setup>
import { ref } from 'vue'
import ThemeTools from '@/tools/theme'

const theme = ref(ThemeTools.getCurrentTheme())
const aside = ref(ThemeTools.getAside())

const changeTheme = () => {
  if (theme.value === 'dark') {
    ThemeTools.setTheme('light')
    theme.value = 'light'
  } else {
    ThemeTools.setTheme('dark')
    theme.value = 'dark'
  }
}

const changeAside = () => {
  if (aside.value === 'right') {
    ThemeTools.setAside('left')
    aside.value = 'left'
  } else {
    ThemeTools.setAside('right')
    aside.value = 'right'
  }
}
</script>

<template>
  <div class="box setting-container">
    <h1 class="title is-4 has-text-centered">
      <span class="icon-text">
        <span class="icon">
          <i class="fa-solid fa-wand-magic-sparkles"></i>
        </span>
        <span>Diseño</span>
      </span>
    </h1>

    <div class="buttons is-centered">
      <button class="button setting-button" @click="changeTheme">
        <span class="icon-text">
          <span class="icon">
            <i class="fa-solid fa-palette"></i>
          </span>
          <span>Cambiar el tema a modo {{ theme === 'dark' ? 'claro' : 'oscuro' }}</span>
        </span>
      </button>
      <button class="button setting-button is-hidden-touch" @click="changeAside">
        <span class="icon-text">
          <span class="icon">
            <i v-if="aside === 'left'" class="fa-solid fa-arrow-right"></i>
            <i v-else class="fa-solid fa-arrow-left"></i>
          </span>
          <span>Cambiar barra lateral a la {{ aside === 'left' ? 'derecha' : 'izquierda' }}</span>
        </span>
      </button>
    </div>
  </div>
</template>

<style scoped>
.aaaa {
  border: 1px solid white;
}
</style>
