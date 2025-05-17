<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import LoginFormComponent from '@/components/forms/LoginFormComponent.vue'
</script>

<template>
  <AppLayout :is-user-blocked="true">
    <template #main>
      <LoginFormComponent />
    </template>
  </AppLayout>
</template>
