<template>
  <div class="space-y-8 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-cellar-navy tracking-tight uppercase">Dashboard</h1>
        <p class="text-cellar-gray mt-1 text-sm font-medium">
          Bem-vindo(a), <strong class="text-cellar-dark">{{ userEmail }}</strong>
          <span class="ml-2 text-xs bg-cellar-navyMid/10 text-cellar-navyMid font-bold px-3 py-1 rounded-pill border border-cellar-navyMid/20 uppercase tracking-widest">{{ userRole }}</span>
        </p>
      </div>
      <button @click="refresh" class="h-12 w-12 bg-white rounded-full shadow-sm flex items-center justify-center text-cellar-orange ring-1 ring-slate-100 cursor-pointer hover:bg-slate-50 transition-all hover:scale-110" title="Atualizar dados">
        <svg xmlns="http://www.w3.org/2000/svg" :class="['h-6 w-6 transition-transform', loading && 'animate-spin']" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>

    <!-- Cards de KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

      <router-link to="/chamados" class="bg-white rounded-round shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300 cursor-pointer">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-cellar-navy/5 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-125 transition-all duration-500"></div>
        <div class="flex items-center gap-2 z-10 mb-3">
          <div class="w-10 h-10 bg-cellar-navy/10 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-cellar-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          </div>
          <p class="text-sm font-semibold text-cellar-gray uppercase tracking-wider">Total de Chamados</p>
        </div>
        <h3 class="text-5xl font-bold text-cellar-navy z-10">
          <span v-if="loading">—</span>
          <span v-else>{{ stats.total }}</span>
        </h3>
        <p class="text-xs text-cellar-gray mt-2 z-10 font-medium">↗ Ver todos os chamados</p>
      </router-link>

      <div class="bg-white rounded-round shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-cellar-orange/5 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-125 transition-all duration-500"></div>
        <div class="flex items-center gap-2 z-10 mb-3">
          <div class="w-10 h-10 bg-cellar-orange/10 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-cellar-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <p class="text-sm font-semibold text-cellar-gray uppercase tracking-wider">Aguardando</p>
        </div>
        <h3 class="text-5xl font-bold text-cellar-orange z-10">
          <span v-if="loading">—</span>
          <span v-else>{{ stats.abertos }}</span>
        </h3>
        <p class="text-xs text-cellar-gray mt-2 z-10 font-medium">Chamados na fila de espera</p>
      </div>

      <div class="bg-white rounded-round shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-cellar-navyMid/5 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-125 transition-all duration-500"></div>
        <div class="flex items-center gap-2 z-10 mb-3">
          <div class="w-10 h-10 bg-cellar-navyMid/10 rounded-xl flex items-center justify-center">
            <div class="w-2.5 h-2.5 bg-cellar-navyMid rounded-full animate-pulse"></div>
          </div>
          <p class="text-sm font-semibold text-cellar-gray uppercase tracking-wider">Em Progresso</p>
        </div>
        <h3 class="text-5xl font-bold text-cellar-navyMid z-10">
          <span v-if="loading">—</span>
          <span v-else>{{ stats.em_progresso }}</span>
        </h3>
        <p class="text-xs text-cellar-gray mt-2 z-10 font-medium">Chamados em atendimento</p>
      </div>

      <div class="bg-cellar-wine text-white rounded-round shadow-md p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="flex items-center gap-2 z-10 mb-3">
          <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <p class="text-sm font-semibold opacity-90 uppercase tracking-wider">Finalizados</p>
        </div>
        <h3 class="text-5xl font-bold z-10">
          <span v-if="loading">—</span>
          <span v-else>{{ stats.resolvidos }}</span>
        </h3>
        <p class="text-xs opacity-70 mt-2 z-10 font-medium">Encerrados com sucesso</p>
      </div>
    </div>

    <!-- Chamados recentes -->
    <div class="bg-white rounded-round shadow-sm border border-slate-100 overflow-hidden">
      <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-cellar-navy uppercase tracking-wider">
          {{ userRole === 'analista' ? 'Chamados Recentes' : 'Meus Chamados Recentes' }}
        </h2>
        <router-link to="/chamados" class="text-sm font-bold text-cellar-wine hover:text-cellar-orange transition uppercase tracking-widest">
          Ver todos →
        </router-link>
      </div>

      <div v-if="loading" class="p-10 text-center text-cellar-gray text-sm font-medium">Carregando...</div>

      <div v-else-if="recentTickets.length === 0" class="p-10 text-center text-cellar-gray text-sm font-medium">
        Nenhum chamado encontrado.
        <router-link v-if="userRole === 'cliente'" to="/chamados" class="text-cellar-orange font-bold ml-1 uppercase">Abrir seu primeiro chamado →</router-link>
      </div>

      <ul v-else class="divide-y divide-slate-50">
        <li
          v-for="ticket in recentTickets"
          :key="ticket.id"
          class="px-8 py-5 flex items-center gap-4 hover:bg-slate-50 transition-colors group"
        >
          <div :class="statusDotClass(ticket.status)" class="w-3 h-3 rounded-full flex-shrink-0"></div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-cellar-dark truncate group-hover:text-cellar-orange transition-colors">{{ ticket.title }}</p>
            <p class="text-xs text-cellar-gray mt-1 font-medium tracking-wide">{{ ticket.ticket_number || `#${ticket.id}` }} · {{ formatDate(ticket.created_at) }}</p>
          </div>
          <span :class="statusBadgeClass(ticket.status)" class="text-xs font-bold px-3 py-1.5 rounded-pill border flex-shrink-0 uppercase tracking-widest">
            {{ statusLabel(ticket.status) }}
          </span>
          <router-link to="/chamados" class="text-slate-300 hover:text-cellar-orange transition-colors flex-shrink-0 p-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </router-link>
        </li>
      </ul>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const toast = useToast()
const userEmail = localStorage.getItem('userEmail')
const userRole = localStorage.getItem('userRole')

const tickets = ref([])
const loading = ref(true)

const API_URL = 'http://localhost:8000/api/v1'

const myTickets = computed(() => {
  if (userRole === 'analista') return tickets.value
  return tickets.value.filter(t => t.created_by === userEmail)
})

const stats = computed(() => ({
  total: myTickets.value.length,
  abertos: myTickets.value.filter(t => t.status === 'aberto').length,
  em_progresso: myTickets.value.filter(t => t.status === 'em_progresso').length,
  resolvidos: myTickets.value.filter(t => t.status === 'resolvido').length,
}))

const recentTickets = computed(() => myTickets.value.slice(0, 6))

const formatDate = (d) => {
  if (!d) return '—'
  return new Date(d).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

const statusLabel = (s) => ({ aberto: 'Aguardando', em_progresso: 'Em Progresso', resolvido: 'Resolvido' }[s] || s)
const statusDotClass = (s) => ({ aberto: 'bg-cellar-orange', em_progresso: 'bg-cellar-navyMid animate-pulse', resolvido: 'bg-emerald-500' }[s] || 'bg-slate-300')
const statusBadgeClass = (s) => ({
  aberto: 'bg-orange-50 border-orange-200 text-cellar-orange',
  em_progresso: 'bg-blue-50 border-blue-200 text-cellar-navyMid',
  resolvido: 'bg-emerald-50 border-emerald-200 text-emerald-700',
}[s] || 'bg-slate-50 border-slate-200 text-slate-600')

const fetchTickets = async () => {
  loading.value = true
  try {
    const { data } = await axios.get(`${API_URL}/tickets`)
    tickets.value = data.data
  } catch {
    toast.error('Falha ao carregar dados do dashboard.')
  } finally {
    loading.value = false
  }
}

const refresh = () => fetchTickets()

onMounted(() => {
  fetchTickets()
})
</script>
