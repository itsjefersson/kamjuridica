import { createRouter, createWebHistory } from 'vue-router'
import PublicLayout from '@/components/layout/PublicLayout.vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

import { useAuth } from '@/composables/useAuth'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      component: PublicLayout,
      children: [
        { path: '', name: 'home', component: () => import('@/views/HomeView.vue') },
        { path: 'servicios', name: 'services', component: () => import('@/views/public/ServicesView.vue') },
        { path: 'sobre-mi', name: 'about', component: () => import('@/views/public/AboutView.vue') },
        { path: 'articulos', name: 'articles', component: () => import('@/views/public/ArticlesView.vue') },
        { path: 'articulos/:slug', name: 'article-detail', component: () => import('@/views/public/ArticleDetailView.vue') },
        { path: 'contacto', name: 'contact', component: () => import('@/views/public/ContactView.vue') },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/admin/LoginView.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/admin',
      component: AdminLayout,
      meta: { requiresAuth: true },
      children: [
        { path: '', name: 'dashboard', component: () => import('@/views/admin/DashboardView.vue') },
        { path: 'articles', name: 'admin-articles', component: () => import('@/views/admin/ArticlesAdminView.vue') },
        { path: 'articles/new', name: 'admin-article-create', component: () => import('@/views/admin/ArticleFormView.vue') },
        { path: 'articles/:id/edit', name: 'admin-article-edit', component: () => import('@/views/admin/ArticleFormView.vue') },
        { path: 'social-posts', name: 'admin-social-posts', component: () => import('@/views/admin/SocialPostsAdminView.vue') },
        { path: 'settings', name: 'admin-settings', component: () => import('@/views/admin/SettingsAdminView.vue') },
      ],
    },
  ],
})

// Protección de rutas: redirige según el estado de autenticación.
router.beforeEach(async (to) => {
  const { isAuthenticated, checkSession } = useAuth()

  if (to.meta.requiresAuth && !isAuthenticated()) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }

  if (to.meta.requiresAuth && isAuthenticated()) {
    // Valida la sesión contra el backend al entrar a una ruta protegida.
    const valid = await checkSession()
    if (!valid) {
      return { name: 'login', query: { redirect: to.fullPath } }
    }
  }

  if (to.meta.guestOnly && isAuthenticated()) {
    return { name: 'dashboard' }
  }
})

export default router
