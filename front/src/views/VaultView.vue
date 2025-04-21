<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
</script>

<template>
  <AppLayout :is-user-required="true">
    <template #main>
      <h1>Baúl personal</h1>
    </template>
  </AppLayout>
</template>
