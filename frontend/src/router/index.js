import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/LoginView.vue'),
    meta: { title: 'Login – Gestão de Chamados' },
  },
  {
    path: '/',
    name: 'dashboard',
    component: () => import('@/views/DashboardView.vue'),
    meta: { title: 'Dashboard – Gestão de Chamados', requiresAuth: true },
  },
  {
    path: '/chamados',
    name: 'tickets',
    component: () => import('@/views/TicketsView.vue'),
    meta: { title: 'Chamados – Gestão de Chamados', requiresAuth: true },
  },
  {
    path: '/categorias',
    name: 'categories',
    component: () => import('@/views/CategoriesView.vue'),
    meta: { title: 'Categorias – Gestão de Chamados', requiresAuth: true },
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  document.title = to.meta?.title ?? 'Gestão de Chamados'
  
  const isAuthenticated = !!localStorage.getItem('userEmail')
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  } else if (to.path === '/login' && isAuthenticated) {
    next('/')
  } else {
    next()
  }
})

export default router
