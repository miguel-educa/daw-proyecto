<script setup>
import { RouterView } from 'vue-router'
import ThemeTools from '@/tools/theme.js'

ThemeTools.loadTheme()
</script>

<template>
  <RouterView />
</template>
