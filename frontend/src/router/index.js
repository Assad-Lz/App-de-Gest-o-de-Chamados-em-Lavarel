/**
 * Roteador Vue Router – definição das rotas da SPA.
 *
 * Cada rota corresponde a uma view principal do sistema.
 * O uso de lazy loading (import dinâmico) melhora a performance
 * carregando o código somente quando a rota é acessada.
 */
import { createRouter, createWebHistory } from 'vue-router'

/**
 * Definição das rotas da aplicação.
 * Todas as views são carregadas de forma lazy (code splitting).
 */
const routes = [
  {
    path: '/',
    name: 'dashboard',
    component: () => import('@/views/DashboardView.vue'),
    meta: { title: 'Dashboard – Gestão de Chamados' },
  },
  {
    path: '/chamados',
    name: 'tickets',
    component: () => import('@/views/TicketsView.vue'),
    meta: { title: 'Chamados – Gestão de Chamados' },
  },
  {
    path: '/categorias',
    name: 'categories',
    component: () => import('@/views/CategoriesView.vue'),
    meta: { title: 'Categorias – Gestão de Chamados' },
  },
  {
    // Rota catch-all: redireciona páginas não encontradas para o dashboard
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/**
 * Guard de navegação: atualiza o título da página conforme a rota.
 */
router.afterEach((to) => {
  document.title = to.meta?.title ?? 'Gestão de Chamados'
})

export default router
