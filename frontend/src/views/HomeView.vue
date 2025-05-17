<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import HomeComponent from '@/components/HomeComponent.vue'
import '@/assets/css/home.css'
</script>

<template>
  <AppLayout>
    <template #main>
      <!-- Contenido del main -->
      <HomeComponent />
    </template>
  </AppLayout>
</template>
