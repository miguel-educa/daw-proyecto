<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore.js'
import UserTools from '@/tools/user.js'
import ThemeTools from '@/tools/theme.js'
import HeaderComponent from '@/components/HeaderComponent.vue'
import AsideComponent from '@/components/AsideComponent.vue'
import LoadingComponent from '@/components/LoadingComponent.vue'

const router = useRouter()
const uStore = useUserStore()
const { user, isUserLogged } = storeToRefs(uStore)

const props = defineProps({
  isUserRequired: {
    type: Boolean,
    default: false,
  },
  isUserBlocked: {
    type: Boolean,
    default: false,
  },
})

const loading = ref(!isUserLogged.value && !sessionStorage.getItem('anonymous-user'))

// Responsive
const RESPONSIVE_RESOLUTION = 1024

const isMobile = ref(window.innerWidth < RESPONSIVE_RESOLUTION)
const handleResize = () => {
  isMobile.value = window.innerWidth < RESPONSIVE_RESOLUTION
}

onMounted(async () => {
  window.addEventListener('resize', handleResize)
  handleResize()

  user.value = await UserTools.getUserInfo()
  loading.value = false

  if (props.isUserRequired && !uStore.isUserLogged) {
    router.push('/login')
  }

  if (props.isUserBlocked && uStore.isUserLogged) {
    router.push('/vault')
  }
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<template>
  <!-- Loading -->
  <LoadingComponent v-if="loading" />

  <!-- Layout -->
  <div v-else class="layout" :class="{ columns: !isMobile && uStore.isUserLogged }">
    <!-- Header -->
    <HeaderComponent v-if="isMobile || !uStore.isUserLogged" />

    <!-- Barra lateral y Main -->
    <template v-if="!isMobile && uStore.isUserLogged">
      <!-- Barra lateral izquierda -->
      <template v-if="ThemeTools.getAside() === 'left'">
        <AsideComponent />
        <main class="column">
          <slot name="main" />
        </main>
      </template>

      <!-- Barra lateral derecha -->
      <template v-else>
        <main class="column">
          <slot name="main" />
        </main>
        <AsideComponent />
      </template>
    </template>

    <!-- Main -->
    <template v-else>
      <main>
        <slot name="main" />
      </main>
    </template>
  </div>
</template>

<style scoped>
.layout {
  margin: 0;
}
</style>
