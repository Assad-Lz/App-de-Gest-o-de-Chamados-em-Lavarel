<template>
  <div class="min-h-screen bg-slate-50 flex flex-col font-sans text-slate-800">
    <!-- Navbar Premium -->
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center gap-3">
             <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-inner flex items-center justify-center text-white font-bold text-xl ring-2 ring-blue-100 ring-offset-2">
              GC
            </div>
            <span class="font-extrabold text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">Gestão de Chamados</span>
          </div>
          <div class="flex items-center space-x-2 md:space-x-8">
            <router-link to="/" class="px-3 py-2 text-slate-600 hover:text-blue-600 font-semibold text-sm transition-all rounded-lg hover:bg-blue-50" active-class="text-blue-700 bg-blue-50/80 ring-1 ring-blue-100">Visão Geral</router-link>
            <router-link to="/tickets" class="px-3 py-2 text-slate-600 hover:text-blue-600 font-semibold text-sm transition-all rounded-lg hover:bg-blue-50" active-class="text-blue-700 bg-blue-50/80 ring-1 ring-blue-100">Chamados</router-link>
            <router-link to="/categories" class="px-3 py-2 text-slate-600 hover:text-blue-600 font-semibold text-sm transition-all rounded-lg hover:bg-blue-50" active-class="text-blue-700 bg-blue-50/80 ring-1 ring-blue-100">Categorias</router-link>
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
  </div>
</template>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(12px);
}
</style>
