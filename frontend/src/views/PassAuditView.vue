<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import FilteredPasswordsComponent from '@/components/audit/FilteredPasswordsComponent.vue'
import InsecurePasswordsComponent from '@/components/audit/InsecurePasswordsComponent.vue'
import DuplicatedPasswordsComponent from '@/components/audit/DuplicatedPasswordsComponent.vue'
import '@/assets/css/audit.css'
</script>

<template>
  <AppLayout :is-user-required="true">
    <template #main>
      <div class="columns">
        <div class="column">
          <InsecurePasswordsComponent />
        </div>
      </div>

      <br />

      <div class="columns is-multiline">
        <div class="column is-full-touch is-half">
          <DuplicatedPasswordsComponent />
        </div>

        <br />

        <div class="column is-full-touch is-half">
          <FilteredPasswordsComponent />
        </div>
      </div>
    </template>
  </AppLayout>
</template>
