import UserTools from '@/tools/user'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/HomeView.vue'),
  },
  {
    path: '/login',
    name: 'Inicio de sesión',
    component: () => import('@/views/LoginView.vue'),
    meta: {
      title: 'Login',
    },
  },
  {
    path: '/logout',
    name: 'Cerrar sesión',
    beforeEnter: async (to, from, next) => {
      const result = await UserTools.logout()

      if (result) next('/login')
      else alert('No se ha podido cerrar la sesión, inténtelo más tarde')
    },
  },
  {
    path: '/pass-generator',
    name: 'Generador de contraseñas',
    component: () => import('@/views/PassGeneratorView.vue'),
    meta: {
      title: 'Generador de contraseñas',
    },
  },
  {
    path: '/register',
    name: 'Registro',
    component: () => import('@/views/RegisterView.vue'),
    meta: {
      title: 'Registro',
    },
  },
  {
    path: '/vault',
    name: 'Baúl personal',
    component: () => import('@/views/VaultView.vue'),
    meta: {
      title: 'Baúl personal',
    },
  },
  {
    path: '/shared-vault',
    name: 'Baúl compartido',
    component: () => import('@/views/SharedVaultView.vue'),
    meta: {
      title: 'Baúl compartido',
    },
  },
  {
    path: '/pass-audit',
    name: '/Auditoría de contraseñas',
    component: () => import('@/views/PassAuditView.vue'),
    meta: {
      title: 'Auditoría de contraseñas',
    },
  },
  {
    path: '/settings',
    name: 'Ajuestes de cuenta',
    component: () => import('@/views/SettingsView.vue'),
    meta: {
      title: 'Ajustes de cuenta',
    },
  },
  {
    path: '/:pathMatch(.*)*',
    name: '404',
    component: () => import('@/views/Error404View.vue'),
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
