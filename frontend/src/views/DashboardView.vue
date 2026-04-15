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

    <!-- Versão Cliente: Cartão de Boas-vindas com Imagem -->
    <div v-if="userRole === 'cliente'" class="grid grid-cols-1 gap-6">
      <div class="relative overflow-hidden rounded-round shadow-2xl min-h-[400px] flex items-center group">
        <!-- Background com Overlay -->
        <div class="absolute inset-0 z-0">
          <img src="/assets/images/cellar_vinhos_cover.jpeg" alt="Cellar Vinhos Background" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-r from-cellar-navy via-cellar-navy/80 to-transparent"></div>
        </div>

        <!-- Conteúdo do Cartão -->
        <div class="relative z-10 p-12 max-w-2xl text-white">
          <span class="inline-block px-4 py-1.5 bg-cellar-orange text-white rounded-pill text-[10px] font-black uppercase tracking-[0.2em] mb-6 shadow-lg">Central de Suporte</span>
          <h2 class="text-5xl font-black mb-4 leading-tight">Olá, como podemos ajudar?</h2>
          <p class="text-lg opacity-90 mb-8 font-medium leading-relaxed">
            Bem-vindo ao portal de atendimento da Cellar Vinhos. <br>
            Nossa equipe técnica está pronta para resolver suas solicitações de hardware e software.
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 backdrop-blur-md border border-white/20">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </div>
              <div>
                <h4 class="font-bold text-white uppercase text-xs tracking-widest mb-1">Como Abrir</h4>
                <p class="text-xs text-white/70">Vá na aba "Chamados" e clique no botão laranja no topo.</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 backdrop-blur-md border border-white/20">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <h4 class="font-bold text-white uppercase text-xs tracking-widest mb-1">Prazo Médio</h4>
                <p class="text-xs text-white/70">Resposta em até 4h para emergências críticas.</p>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-6 pt-6 border-t border-white/10">
            <router-link to="/chamados" class="bg-cellar-orange hover:bg-white hover:text-cellar-navy text-white px-8 py-4 rounded-pill font-black transition-all shadow-xl uppercase tracking-widest text-xs">Meus Chamados</router-link>
            <div class="flex flex-col">
              <span class="text-[10px] text-white/50 uppercase font-black">Emergências TI</span>
              <span class="text-lg font-bold">(11) 9999-9999</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Versão Analista: KPIs e Detalhes -->
    <template v-if="userRole === 'analista'">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <router-link to="/chamados" class="bg-white rounded-round shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-2xl transition-all hover:-translate-y-2 duration-300 cursor-pointer ring-1 ring-slate-100 hover:ring-cellar-navy/20">
          <div class="absolute -right-6 -top-6 w-32 h-32 bg-cellar-navy/5 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-150 transition-all duration-700"></div>
          <div class="flex items-center gap-2 z-10 mb-3">
            <div class="w-10 h-10 bg-cellar-navy/10 rounded-xl flex items-center justify-center group-hover:bg-cellar-navy group-hover:text-white transition-colors">
              <svg class="w-5 h-5 text-cellar-navy group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-sm font-semibold text-cellar-gray uppercase tracking-wider">Total de Chamados</p>
          </div>
          <h3 class="text-5xl font-bold text-cellar-navy z-10">
            <span v-if="loading">—</span>
            <span v-else>{{ stats.total }}</span>
          </h3>
          <p class="text-xs text-cellar-gray mt-2 z-10 font-medium group-hover:text-cellar-navy transition-colors">↗ Detalhes da Operação</p>
        </router-link>

        <div class="bg-white rounded-round shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-2xl transition-all hover:-translate-y-2 duration-300 ring-1 ring-slate-100 hover:ring-cellar-orange/20">
          <div class="absolute -right-6 -top-6 w-32 h-32 bg-cellar-orange/5 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-150 transition-all duration-700"></div>
          <div class="flex items-center gap-2 z-10 mb-3">
            <div class="w-10 h-10 bg-cellar-orange/10 rounded-xl flex items-center justify-center group-hover:bg-cellar-orange group-hover:text-white transition-colors">
              <svg class="w-5 h-5 text-cellar-orange group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-cellar-gray uppercase tracking-wider">Aguardando</p>
          </div>
          <h3 class="text-5xl font-bold text-cellar-orange z-10">
            <span v-if="loading">—</span>
            <span v-else>{{ stats.abertos }}</span>
          </h3>
          <p class="text-xs text-cellar-gray mt-2 z-10 font-medium">Chamados pendentes</p>
        </div>

        <div class="bg-white rounded-round shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-2xl transition-all hover:-translate-y-2 duration-300 ring-1 ring-slate-100 hover:ring-cellar-navyMid/20">
          <div class="absolute -right-6 -top-6 w-32 h-32 bg-cellar-navyMid/5 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-150 transition-all duration-700"></div>
          <div class="flex items-center gap-2 z-10 mb-3">
            <div class="w-10 h-10 bg-cellar-navyMid/10 rounded-xl flex items-center justify-center group-hover:bg-cellar-navyMid group-hover:text-white transition-colors">
              <div class="w-2.5 h-2.5 bg-cellar-navyMid group-hover:bg-white rounded-full animate-pulse transition-colors"></div>
            </div>
            <p class="text-sm font-semibold text-cellar-gray uppercase tracking-wider">Em Progresso</p>
          </div>
          <h3 class="text-5xl font-bold text-cellar-navyMid z-10">
            <span v-if="loading">—</span>
            <span v-else>{{ stats.em_progresso }}</span>
          </h3>
          <p class="text-xs text-cellar-gray mt-2 z-10 font-medium">Equipe atuando agora</p>
        </div>

        <div class="bg-cellar-wine text-white rounded-round shadow-xl p-6 flex flex-col relative overflow-hidden group hover:shadow-2xl transition-all hover:-translate-y-2 duration-300">
          <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
          <div class="flex items-center gap-2 z-10 mb-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:bg-white group-hover:text-cellar-wine transition-colors">
              <svg class="w-5 h-5 text-white group-hover:text-cellar-wine transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold opacity-90 uppercase tracking-wider">Finalizados</p>
          </div>
          <h3 class="text-5xl font-bold z-10">
            <span v-if="loading">—</span>
            <span v-else>{{ stats.resolvidos }}</span>
          </h3>
          <p class="text-xs opacity-70 mt-2 z-10 font-medium">Sucesso no atendimento</p>
        </div>
      </div>

      <!-- Analista: Graficos Visuais de Distribuição -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-round shadow-sm border border-slate-100 p-8">
          <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-50 pb-4">Eficiência do Time</h4>
          <div class="space-y-6">
            <div v-for="stat in [
              { label: 'Abertos', count: stats.abertos, color: 'bg-orange-400', pct: (stats.abertos/stats.total)*100 },
              { label: 'Em Progresso', count: stats.em_progresso, color: 'bg-blue-500', pct: (stats.em_progresso/stats.total)*100 },
              { label: 'Resolvidos', count: stats.resolvidos, color: 'bg-emerald-500', pct: (stats.resolvidos/stats.total)*100 }
            ]" :key="stat.label">
              <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-bold text-slate-700">{{ stat.label }}</span>
                <span class="text-xs font-black text-slate-400">{{ isNaN(stat.pct) ? 0 : Math.round(stat.pct) }}%</span>
              </div>
              <div class="w-full h-3 bg-slate-50 rounded-full overflow-hidden">
                <div :class="stat.color" class="h-full rounded-full transition-all duration-1000" :style="{ width: (isNaN(stat.pct) ? 0 : stat.pct) + '%' }"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-round shadow-sm border border-slate-100 p-8 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-cellar-navy/5 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-cellar-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <h4 class="text-xl font-black text-cellar-navy uppercase">Taxa de Resolução</h4>
            <p class="text-3xl font-black text-cellar-orange mt-2">{{ isNaN(stats.resolvidos/stats.total) ? 0 : Math.round((stats.resolvidos/stats.total)*100) }}%</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">Taxa de chamados finalizados vs. abertos</p>
        </div>
      </div>

      <!-- Chamados recentes (Analista) -->
      <div class="bg-white rounded-round shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
          <h2 class="text-lg font-bold text-cellar-navy uppercase tracking-wider">Chamados Recentes</h2>
          <router-link to="/chamados" class="text-sm font-bold text-cellar-wine hover:text-cellar-orange transition uppercase tracking-widest">Ver todos →</router-link>
        </div>
        <div v-if="loading" class="p-10 text-center text-cellar-gray text-sm font-medium">Carregando...</div>
        <ul v-else-if="recentTickets.length > 0" class="divide-y divide-slate-50">
          <li v-for="ticket in recentTickets" :key="ticket.id" class="px-8 py-5 flex items-center gap-4 hover:bg-slate-50 transition-colors group">
            <div :class="statusDotClass(ticket.status)" class="w-3 h-3 rounded-full flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-bold text-cellar-dark truncate group-hover:text-cellar-orange transition-colors">{{ ticket.title }}</p>
              <p class="text-xs text-cellar-gray mt-1 font-medium tracking-wide">{{ ticket.ticket_number || `#${ticket.id}` }} · {{ formatDate(ticket.created_at) }}</p>
            </div>
            <span :class="statusBadgeClass(ticket.status)" class="text-xs font-bold px-3 py-1.5 rounded-pill border flex-shrink-0 uppercase tracking-widest">{{ statusLabel(ticket.status) }}</span>
          </li>
        </ul>
      </div>
    </template>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const toast = useToast()
const userEmail = ref(localStorage.getItem('userEmail'))
const userRole = ref(localStorage.getItem('userRole'))

const tickets = ref([])
const loading = ref(true)

const API_URL = 'http://localhost:8000/api/v1'

const myTickets = computed(() => {
  return tickets.value
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
