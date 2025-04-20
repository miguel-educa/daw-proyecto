<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore.js'
import { navMenuOptions } from '@/config.js'

const route = useRoute()
const uStore = useUserStore()
const { user } = storeToRefs(uStore)

const isMobileMenuOpen = ref(false)

const options = user.value ? navMenuOptions.userLogin : navMenuOptions.anonymousUser
</script>

<template>
  <header>
    <!-- Menú de navegación -->
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <!-- Logo -->
        <RouterLink class="navbar-item" to="/">
          <img src="/img/pass-warriors-logo.png" class="logo-image" />
          <span class="title-font is-size-3 is-size-4-mobile">Pass Warriors</span>
        </RouterLink>

        <!-- Menú Responsive -->
        <button
          role="button"
          class="navbar-burger"
          aria-label="menu"
          aria-expanded="false"
          data-target="navMenu"
          :class="{ 'is-active': isMobileMenuOpen }"
          @click="isMobileMenuOpen = !isMobileMenuOpen"
        >
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </button>
      </div>

      <!-- Elementos de navegación -->
      <div id="navMenu" class="navbar-menu" :class="{ 'is-active': isMobileMenuOpen }">
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <template v-for="(item, i) in options" :key="i">
                <RouterLink
                  v-for="(child, j) in item.children || [item]"
                  :key="j"
                  :to="child.path"
                  :class="[
                    { active: route.path === child.path },
                    item.children ? 'child-link' : 'not-child-link',
                  ]"
                  class="navbar-item is-size-5 is-size-6-mobile"
                >
                  <template v-if="item.children">
                    {{ item.label }} {{ child.label.toLocaleLowerCase() }}
                  </template>
                  <template v-else>
                    {{ child.label }}
                  </template>
                </RouterLink>
              </template>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<style scoped>
header {
  position: sticky;
  top: 0;
  z-index: 10;
}

nav {
  background-color: var(--header-background-color);
  box-shadow: 0px 7px 10px #00000033;
}

nav a.active {
  background-color: var(--header-link-active-background-color);
  color: var(--header-link-active-text-color);
}

a:hover,
.navbar-burger:hover {
  background-color: var(--header-link-hover-background-color);
  color: var(--header-link-hover-color);
}

.navbar-menu {
  background-color: var(--header-background-color);
}

.navbar-item {
  color: var(--header-text-color);
}

.navbar-menu a {
  border-radius: 7px;
}

.logo-image {
  max-height: 4rem;
}

.navbar-burger {
  color: var(--header-text-color);
  box-shadow: none !important;
}

/* Responsive */
@media screen and (max-width: 1023px) {
  .navbar-menu.is-active {
    display: flex;
    flex-direction: column;
    align-items: center;
    border-top: 3px solid var(--header-separator-color);
    position: absolute;
    width: 100%;
    box-shadow: 0px 7px 10px #00000033;
  }

  .navbar-menu .buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }

  .navbar-menu .buttons .navbar-item {
    justify-content: center;
    width: 100%;
    text-align: center;
  }

  .logo-image {
    max-height: 3rem;
  }
}
</style>
