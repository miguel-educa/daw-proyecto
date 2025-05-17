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
          <template v-for="(item, i) in options" :key="i">
            <template v-if="item.children">
              <div class="navbar-item has-dropdown is-hoverable">
                <div class="navbar-item is-size-5 is-size-6-mobile is-hidden-touch">
                  <a
                    class="navbar-item"
                    :class="{
                      active: item.children.some((child) => route.path === child.path),
                    }"
                    style="cursor: default"
                  >
                    {{ item.label }}
                  </a>
                </div>

                <div class="navbar-dropdown">
                  <RouterLink
                    v-for="(child, j) in item.children || [item]"
                    :key="j"
                    :to="child.path"
                    :class="[{ active: route.path === child.path }]"
                    class="navbar-item is-size-5 is-size-6-mobile"
                  >
                    <span class="mobile-visible">{{ item.label }}</span>
                    <span>{{ child.label }}</span>
                  </RouterLink>
                </div>
              </div>
            </template>
            <template v-else>
              <div class="navbar-item">
                <RouterLink
                  :to="item.path"
                  :class="[{ active: route.path === item.path }]"
                  class="navbar-item is-size-5 is-size-6-mobile"
                >
                  {{ item.label }}
                </RouterLink>
              </div>
            </template>
          </template>
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

nav,
.navbar-dropdown {
  background-color: var(--header-background-color);
  box-shadow: 0px 7px 10px #00000033;
  padding: 0;
}

.navbar-dropdown a {
  background-color: transparent !important;
  color: var(--header-text-color) !important;
}

nav a.active,
.navbar-dropdown a.active {
  background-color: var(--header-link-active-background-color) !important;
  color: var(--header-link-active-text-color) !important;
}

a:hover,
.navbar-dropdown a:hover,
.navbar-burger:hover {
  background-color: var(--header-link-hover-background-color) !important;
  color: var(--header-link-hover-color) !important;
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

.mobile-visible {
  display: none;
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

  .navbar-menu .navbar-item {
    justify-content: center;
    width: 100%;
    text-align: center;
  }

  .logo-image {
    max-height: 3rem;
  }

  .mobile-visible {
    display: inline-block;
    margin-right: 0.5rem;
  }

  .mobile-visible + span {
    text-transform: lowercase;
  }

  .navbar-dropdown {
    box-shadow: none;
  }
}
</style>
