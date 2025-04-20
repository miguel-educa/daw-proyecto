<script setup>
import { useRoute } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import { navMenuOptions } from '@/config'

const route = useRoute()
const uStore = useUserStore()
const user = uStore.user
</script>

<template>
  <aside class="column is-3 p-0 is-flex is-flex-direction-column is-justify-content-space-between">
    <!-- Información del usuario -->
    <div class="user-info has-text-centered py-5 border-bottom">
      <h2 class="title is-4">{{ user.name }}</h2>
      <p class="subtitle is-5">@{{ user.username }}</p>
    </div>

    <!-- Menú de navegación -->
    <nav class="menu">
      <template v-for="(item, i) in navMenuOptions.userLogin" :key="i">
        <div v-if="item.children" class="section-title">{{ item.label }}</div>
        <RouterLink
          v-for="(child, j) in item.children || [item]"
          :key="j"
          :to="child.path"
          :class="[
            { active: route.path === child.path },
            item.children ? 'child-link' : 'not-child-link',
          ]"
        >
          {{ child.label }}
        </RouterLink>
      </template>
    </nav>

    <!-- Logo -->
    <div class="logo py-5">
      <RouterLink class="navbar-item" to="/">
        <img src="/img/pass-warriors-logo.png" class="logo-image" />
        <span class="title-font is-size-2 is-size-3-mobile">Pass Warriors</span>
      </RouterLink>
    </div>
  </aside>
</template>

<style scoped>
aside {
  max-width: 360px;
  min-width: 330px;
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
  background-color: var(--aside-background-color);
  color: var(--aside-text-color);
  display: flex;
  flex-direction: column;
  box-shadow: 7px 0px 10px #00000033;
}

.user-info {
  text-align: center;
}

.user-info h2,
.user-info p {
  color: var(--aside-text-color);
}

nav {
  padding: 2rem 0;
  height: 100%;
  border-top: 10px solid var(--aside-separator-color);
  border-bottom: 10px solid var(--aside-separator-color);
}

.section-title {
  padding: 0.5rem 1rem;
  font-size: 1.5rem;
  cursor: default;
}

nav a {
  display: block;
  width: 100%;
  color: inherit;
  text-decoration: none;
  padding: 0.5rem 1rem;
  transition: var(--transition-background-color), var(--transition-color);
}

nav a.child-link {
  font-size: 1.25rem;
  padding-left: 2.5rem;
}

nav a.not-child-link {
  padding: 0.5rem 1rem;
  font-size: 1.5rem;
}

nav a.active {
  background-color: var(--aside-link-active-background-color);
  color: var(--aside-link-active-text-color);
}

nav a:not(.active):hover {
  background-color: var(--aside-link-hover-background-color);
  color: var(--aside-link-hover-text-color);
}

.logo {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  text-decoration: none;
  transition: var(--transition-opacity);
}

.logo {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  font-size: 1.5rem;
  color: var(--header-text-color);
  transition: var(--transition-opacity);
}

.logo:hover {
  opacity: 0.75;
}

.logo img {
  max-height: 85px;
}
</style>
