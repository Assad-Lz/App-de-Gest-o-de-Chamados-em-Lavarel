<template>
  <div class="flex items-center justify-center min-h-[0vh] mt-10">
    <div class="bg-white p-8 rounded-round shadow-xl border border-slate-100 max-w-xl w-full relative overflow-hidden">
      <!-- Elemento decorativo circular (sutil) -->
      <div class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-cellar-navy/5 to-cellar-wine/5 rounded-full blur-3xl pointer-events-none"></div>

      <div class="flex justify-center mb-8 relative z-10">
         <img src="/assets/images/logo_nova.jpeg" alt="Cellar Vinhos" class="h-24 w-auto rounded-full border-4 border-cellar-wine/10 shadow-lg">
      </div>
      
      <h2 class="text-3xl font-bold text-center text-cellar-navy mb-3 uppercase tracking-wider">Gestão Interna</h2>
      <p class="text-center text-cellar-gray mb-10 text-sm font-medium tracking-wide">Acesso restrito. Selecione seu perfil de usuário.</p>

      <div class="space-y-5 relative z-10">
        <!-- Client Login -->
        <button @click="login('cliente@cellarvinhos.com', 'cliente')" class="w-full relative group bg-white hover:bg-slate-50 text-left p-5 rounded-round border-2 border-slate-100 hover:border-cellar-orange transition-all shadow-sm hover:shadow-md">
          <div class="font-bold text-cellar-navy group-hover:text-cellar-orange text-lg">Área do Cliente (Colaborador)</div>
          <div class="text-sm text-cellar-gray mt-1 font-medium">Abertura e acompanhamento de tickets de suporte.</div>
          <div class="mt-3 text-xs font-mono bg-slate-50 inline-block px-3 py-1.5 rounded-pill text-cellar-navyMid border border-slate-100">cliente@cellarvinhos.com</div>
        </button>

        <!-- Analyst Login -->
        <button @click="login('ti@cellarvinhos.com', 'analista')" class="w-full relative group bg-cellar-navy text-white hover:bg-cellar-navyMid text-left p-5 rounded-round border-2 border-transparent transition-all shadow-md hover:shadow-lg">
          <div class="font-bold text-white text-lg">Área de T.I (Analista)</div>
          <div class="text-sm text-white/70 mt-1 font-medium">Gestão completa, resolução e administração de tickets.</div>
          <div class="mt-3 text-xs font-mono bg-white/10 inline-block px-3 py-1.5 rounded-pill text-white/90 border border-white/20">ti@cellarvinhos.com</div>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'

const router = useRouter()
const toast = useToast()

const login = (email, role) => {
  localStorage.setItem('userEmail', email)
  localStorage.setItem('userRole', role)
  toast.success(`Logado com sucesso como ${role}!`)
  router.push('/')
}
</script>
