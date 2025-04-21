import UserTools from '@/tools/user'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/HomeView.vue'),
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/LoginView.vue'),
    meta: {
      title: 'Login',
    },
  },
  {
    path: '/logout',
    name: 'logout',
    beforeEnter: async (to, from, next) => {
      const result = await UserTools.logout()

      if (result) next('/login')
      else alert('No se ha podido cerrar la sesión, inténtelo más tarde')
    },
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/RegisterView.vue'),
    meta: {
      title: 'Registro',
    },
  },
  {
    path: '/vault',
    name: 'vault',
    component: () => import('@/views/VaultView.vue'),
    meta: {
      title: 'Baúl personal',
    },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Ajustar título pestaña
router.beforeEach((to, from, next) => {
  if (to.meta && to.meta.title) {
    document.title = `${to.meta.title} - Pass Warriors`
  }

  next()
})

export default router
