<script setup>
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import RegisterFormComponent from '@/components/forms/RegisterFormComponent.vue'
import RegisterRequirementsComponent from '@/components/forms/RegisterRequirementsComponent.vue'

const showRequeriments = ref(false)
</script>

<template>
  <AppLayout :is-user-blocked="true">
    <template #main>
      <!-- Modal requisitos -->
      <RegisterRequirementsComponent
        v-if="showRequeriments"
        @close-requeriments="showRequeriments = false"
      />

      <!-- Formulario de registro -->
      <RegisterFormComponent @show-requeriments="showRequeriments = true" />
    </template>
  </AppLayout>
</template>
