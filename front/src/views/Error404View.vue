<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import '@/assets/css/404.css'
</script>

<template>
  <AppLayout>
    <template #main>
      <div class="not-found">
        <h1 class="is-size-1">¡Oops! Página no encontrada</h1>
        <p class="is-size-4 mt-6 mb-3">Parece que la página solicitada no existe</p>
        <RouterLink to="/" class="is-size-5" title="Ir a la página de inicio"
          >Volver al inicio</RouterLink
        >
      </div>
    </template>
  </AppLayout>
</template>

<style scoped>
.not-found {
  background-color: var(--nf-container-background-color);
  color: var(--nf-text-color);
  border: var(--nf-border);
  border-radius: 12px;
  padding: 2rem;
  text-align: center;
  width: fit-content;
  margin: 3rem auto;
  box-shadow: var(--box-shadow-8-8-10);
}
</style>
