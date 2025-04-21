import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/HomeView.vue'),
  },
  {
    path: '/pass-generator',
    name: '/pass-generator',
    component: () => import('@/views/PassGeneratorView.vue'),
    meta: {
      title: 'Generador de contraseñas',
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
