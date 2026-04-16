<template>
  <div class="min-h-screen bg-slate-50 flex flex-col font-sans text-slate-800">
    <nav v-if="!isLoginRoute" class="bg-cellar-navy shadow-lg sticky top-0 z-50">
      <div class="cellar-container">
        <div class="flex justify-between h-20 items-center">
          <div class="flex items-center gap-4">
             <router-link to="/" class="hover:scale-105 transition-transform">
               <img src="/assets/images/logo_nova.jpeg" alt="Cellar Vinhos Logo" class="h-12 w-auto rounded-full border-2 border-white/20">
             </router-link>
          </div>
          <div class="flex items-center space-x-2 md:space-x-8">
            <router-link to="/" class="px-3 py-2 text-white/80 hover:text-white font-medium text-sm transition-all rounded-pill hover:bg-white/10 uppercase tracking-wider" active-class="text-white bg-cellar-wine font-bold">Painel</router-link>
            <router-link to="/chamados" class="px-3 py-2 text-white/80 hover:text-white font-medium text-sm transition-all rounded-pill hover:bg-white/10 uppercase tracking-wider" active-class="text-white bg-cellar-wine font-bold">Chamados</router-link>
            <router-link v-if="userRole === 'analista'" to="/categorias" class="px-3 py-2 text-white/80 hover:text-white font-medium text-sm transition-all rounded-pill hover:bg-white/10 uppercase tracking-wider" active-class="text-white bg-cellar-wine font-bold">Categorias</router-link>
            <button @click="logout" class="px-4 py-2 text-white bg-cellar-orange/90 hover:bg-cellar-orange font-bold text-sm transition-all rounded-pill ml-4 uppercase tracking-wider">Sair</button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content com Transição Glassmorphism -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-full">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Footer Global -->
    <footer class="bg-white border-t border-slate-100 py-8 mt-auto">
      <div class="cellar-container">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
          <div class="text-cellar-gray text-[10px] font-black uppercase tracking-[0.2em] opacity-60">
            © 2026 Cellar Vinhos · Gestão Integrada
          </div>
          <div class="flex items-center gap-6">
            <router-link to="/privacidade" class="text-cellar-gray hover:text-cellar-navy text-xs font-bold uppercase tracking-widest transition-colors">Privacidade</router-link>
            <router-link to="/termos" class="text-cellar-gray hover:text-cellar-navy text-xs font-bold uppercase tracking-widest transition-colors">Termos</router-link>
            <a v-if="!isLoginRoute" href="/backend/openapi.yml" target="_blank" class="text-cellar-gray hover:text-cellar-orange text-xs font-bold uppercase tracking-widest transition-colors">Docs API</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const isLoginRoute = computed(() => route.path === '/login')
const userRole = ref(localStorage.getItem('userRole'))

// Sincroniza o papel do usuário quando a rota muda (útil após login)
watch(() => route.path, () => {
  userRole.value = localStorage.getItem('userRole')
})

onMounted(() => {
  userRole.value = localStorage.getItem('userRole')
})

const logout = () => {
  localStorage.removeItem('userEmail')
  localStorage.removeItem('userRole')
  userRole.value = null
  router.push('/login')
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(12px);
}
</style>
