<template>
  <div class="space-y-6 animate-in fade-in duration-500 relative">
    
    <!-- Header Premium -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
      <div class="flex items-center gap-4 flex-1">
        <div v-if="userRole === 'analista' && selectedTickets.length > 0" class="animate-in slide-in-from-left duration-300">
           <button
            @click="bulkDelete"
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all shadow-md font-bold text-xs uppercase"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Excluir ({{ selectedTickets.length }})
          </button>
        </div>
        <div v-else class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
          <div>
            <h1 class="text-3xl font-bold text-cellar-navy tracking-tight uppercase">
              {{ userRole === 'analista' ? 'Gerenciamento' : 'Meus Chamados' }}
            </h1>
          </div>
          
          <!-- Lupa de Busca (Analista) -->
          <div v-if="userRole === 'analista'" class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input
              v-model="searchQuery"
              @input="debouncedFetch"
              type="text"
              placeholder="Buscar por ID, Protocolo, Nome ou E-mail..."
              class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-pill bg-white text-sm focus:outline-none focus:ring-2 focus:ring-cellar-navy focus:border-transparent transition-all"
            >
          </div>
        </div>
      </div>
      <button
        v-if="userRole === 'cliente'"
        @click="showCreateModal = true"
        class="flex items-center gap-2 bg-cellar-navy hover:bg-cellar-navyMid text-white px-6 py-3 rounded-pill transition-all hover:scale-105 cellar-btn shadow-md whitespace-nowrap"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Abrir Solicitação
      </button>
    </div>

    <!-- Filtros rápidos por status -->
    <div class="flex items-center gap-2 flex-wrap">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Filtrar:</span>
      <button
        v-for="f in statusFilters"
        :key="f.value"
        @click="activeFilter = f.value"
        :class="[
          'px-3 py-1.5 rounded-full text-xs font-bold border transition-all',
          activeFilter === f.value
            ? f.activeClass
            : 'bg-white border-slate-200 text-slate-500 hover:border-slate-300'
        ]"
      >
        {{ f.label }}
        <span class="ml-1 opacity-70">({{ countByStatus(f.value) }})</span>
      </button>
    </div>

    <!-- Lista de Chamados -->
    <div class="bg-white rounded-round shadow-sm border border-slate-100 overflow-hidden">
      <div v-if="loading" class="p-12 text-center">
        <div class="inline-flex items-center gap-3 text-cellar-gray">
          <svg class="animate-spin h-6 w-6 text-cellar-navyMid" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="font-medium">Carregando chamados...</span>
        </div>
      </div>

      <div v-else-if="filteredTickets.length === 0" class="p-16 text-center flex flex-col items-center gap-3">
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center">
          <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <p class="text-cellar-gray font-semibold">Nenhum chamado {{ activeFilter !== 'todos' ? 'com este status' : '' }}</p>
        <p class="text-slate-400 text-sm">{{ userRole === 'cliente' ? 'Clique em "Abrir Solicitação" para criar seu primeiro chamado.' : 'Não há chamados correspondentes ao filtro.' }}</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
          <thead class="bg-slate-50/60">
            <tr>
              <th v-if="userRole === 'analista'" class="px-6 py-4 text-left">
                <input
                  type="checkbox"
                  class="w-4 h-4 rounded text-cellar-navy border-slate-300 focus:ring-cellar-navy"
                  :checked="isAllSelected"
                  @change="toggleSelectAll"
                >
              </th>
              <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Protocolo</th>
              <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Assunto</th>
              <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
              <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Autor(a)</th>
              <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Setor</th>
              <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Data</th>
              <th class="px-6 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-slate-50">
            <tr
              v-for="ticket in filteredTickets"
              :key="ticket.id"
              class="hover:bg-blue-50/30 transition-colors group cursor-pointer"
              @click="openDetail(ticket)"
            >
              <td v-if="userRole === 'analista'" class="px-6 py-4" @click.stop>
                <input
                  type="checkbox"
                  class="w-4 h-4 rounded text-cellar-navy border-slate-300 focus:ring-cellar-navy"
                  :value="ticket.id"
                  v-model="selectedTickets"
                >
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="font-mono font-black text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">
                  {{ ticket.ticket_number || `#${ticket.id}` }}
                </span>
              </td>
              <td class="px-6 py-4 max-w-xs">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-1">{{ ticket.title }}</span>
                  <span class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ ticket.description }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <StatusBadge :status="ticket.status" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                    {{ (ticket.user_name?.[0] || 'S').toUpperCase() }}
                  </div>
                  <div class="flex flex-col">
                    <span class="text-sm font-bold text-slate-700 truncate max-w-[150px]">{{ ticket.user_name || 'Usuário' }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">{{ ticket.user_email || ticket.created_by }}</span>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-black uppercase tracking-widest">
                  {{ ticket.department || 'Geral' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-medium">
                {{ formatDate(ticket.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right" @click.stop>
                <div class="flex justify-end items-center gap-2">
                  <button
                    @click="openDetail(ticket)"
                    class="px-3 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-100"
                  >
                    Ver Detalhes
                  </button>
                  <button
                    v-if="userRole === 'analista'"
                    @click="openEditModal(ticket)"
                    class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition border border-transparent hover:border-emerald-100"
                    title="Editar chamado"
                  >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button
                    v-if="userRole === 'analista'"
                    @click.stop="deleteTicket(ticket.id)"
                    class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition border border-transparent hover:border-red-100"
                    title="Excluir chamado"
                  >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ===================== MODAL CRIAR CHAMADO ===================== -->
    <Transition name="modal">
      <div v-if="showCreateModal" class="fixed inset-0 bg-cellar-navy/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-round w-full max-w-xl shadow-2xl p-8 relative transform">
          <button @click="showCreateModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-cellar-dark bg-slate-100 hover:bg-slate-200 p-2 rounded-full transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>

          <div class="mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg mb-4">
              <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800">Nova Solicitação de Suporte</h3>
            <p class="text-slate-500 text-sm mt-1">Nossa equipe de T.I. responderá o mais rápido possível.</p>
          </div>

          <form @submit.prevent="createTicket" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Seu Nome <span class="text-red-500">*</span></label>
                <input
                  v-model="form.user_name"
                  required
                  type="text"
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-orange/10 focus:border-cellar-orange outline-none transition-all font-medium text-slate-800"
                  placeholder="Seu nome completo"
                >
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Seu E-mail <span class="text-red-500">*</span></label>
                <input
                  v-model="form.user_email"
                  required
                  type="email"
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-orange/10 focus:border-cellar-orange outline-none transition-all font-medium text-slate-800"
                  placeholder="exemplo@cellarvinhos.com"
                >
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Setor / Departamento <span class="text-red-500">*</span></label>
                <input
                  v-model="form.department"
                  required
                  type="text"
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-orange/10 focus:border-cellar-orange outline-none transition-all font-medium text-slate-800"
                  placeholder="Ex: Comercial, Logística..."
                >
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Categoria <span class="text-red-500">*</span></label>
                <select
                  v-model="form.category_id"
                  required
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-orange/10 focus:border-cellar-orange outline-none transition-all font-medium text-slate-700 cursor-pointer"
                >
                  <option value="" disabled>Selecione...</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-bold text-slate-700 mb-2">Assunto / Título <span class="text-red-500">*</span></label>
              <input
                v-model="form.title"
                required
                type="text"
                maxlength="255"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-orange/10 focus:border-cellar-orange outline-none transition-all font-medium text-slate-800"
                placeholder="Ex: Teclado quebrado, Acesso ao sistema negado..."
              >
            </div>

            <div>
              <label class="block text-sm font-bold text-slate-700 mb-2">Descrição Detalhada <span class="text-red-500">*</span></label>
              <textarea
                v-model="form.description"
                required
                rows="4"
                maxlength="5000"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-orange/10 focus:border-cellar-orange outline-none transition-all font-medium text-slate-800 resize-none"
                placeholder="Descreva o problema com o máximo de detalhes possível..."
              ></textarea>
              <p class="text-xs text-slate-400 mt-1 text-right">{{ form.description.length }}/5000</p>
            </div>

            <div class="flex gap-3 pt-2">
              <button
                type="button"
                @click="showCreateModal = false"
                class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition"
              >
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="flex-1 py-3 bg-gradient-to-r from-cellar-orange to-orange-500 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none cellar-btn"
              >
                <span v-if="submitting" class="flex items-center justify-center gap-2">
                  <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                  Enviando...
                </span>
                <span v-else>🚀 Enviar Chamado</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>

    <!-- ===================== MODAL EDITAR CHAMADO (Analista) ===================== -->
    <Transition name="modal">
      <div v-if="showEditModal && selectedTicket" class="fixed inset-0 bg-cellar-navy/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-round w-full max-w-2xl shadow-2xl p-8 relative max-h-[90vh] overflow-y-auto custom-scrollbar">
          <button @click="closeEdit" class="absolute top-5 right-5 text-slate-400 hover:text-cellar-dark bg-slate-100 hover:bg-slate-200 p-2 rounded-full transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>

          <div class="border-b border-slate-100 pb-5 mb-6">
            <span class="inline-block text-xs font-black font-mono tracking-widest text-indigo-500 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100 mb-3">
              {{ selectedTicket.ticket_number || `#${selectedTicket.id}` }}
            </span>
            <h3 class="text-xl font-extrabold text-slate-800">Editar Chamado</h3>
            <p class="text-sm text-slate-500 mt-1">Relatado por <strong>{{ selectedTicket.created_by }}</strong></p>
          </div>

          <form @submit.prevent="updateTicketInfo" class="space-y-5">
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-2">Título</label>
              <input
                v-model="selectedTicket.title"
                type="text"
                required
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-800"
              >
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Categoria</label>
                <select v-model="selectedTicket.category_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none font-medium text-slate-700 cursor-pointer focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500">
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Status do Atendimento</label>
                <select v-model="selectedTicket.status" class="w-full px-4 py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl outline-none font-bold cursor-pointer focus:ring-4 focus:ring-blue-500/20">
                  <option value="aberto">⏳ Aguardando Fila</option>
                  <option value="em_progresso">🔄 Em Progresso</option>
                  <option value="resolvido">✅ Resolvido</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-bold text-slate-700 mb-2">Descrição</label>
              <textarea v-model="selectedTicket.description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none font-medium text-slate-800 resize-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-between items-center pt-2">
              <p class="text-xs text-slate-400">
                Criado em {{ formatDate(selectedTicket.created_at) }}
                <span v-if="selectedTicket.updated_at"> · Atualizado {{ formatDate(selectedTicket.updated_at) }}</span>
              </p>
              <div class="flex gap-3">
                <button type="button" @click="closeEdit" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">Cancelar</button>
                <button type="submit" :disabled="submitting" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition disabled:opacity-50">
                  {{ submitting ? 'Salvando...' : 'Salvar Alterações' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </Transition>

    <!-- ===================== MODAL DETALHE CHAMADO (Cliente / Leitura) ===================== -->
    <Transition name="modal">
      <div v-if="showDetailModal && selectedTicket" class="fixed inset-0 bg-cellar-navy/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-round w-full max-w-2xl shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh]">

          <!-- Header colorido por status -->
          <div :class="statusHeaderClass(selectedTicket.status)" class="px-8 py-6 text-white relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute -right-2 -bottom-8 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <button @click="showDetailModal = false" class="absolute top-4 right-4 text-white/70 hover:text-white bg-white/10 p-2 rounded-full transition">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="relative z-10">
              <span class="text-xs font-black font-mono tracking-widest opacity-80 bg-white/10 px-2 py-1 rounded">{{ selectedTicket.ticket_number || `#${selectedTicket.id}` }}</span>
              <h3 class="text-2xl font-extrabold mt-3 leading-tight">{{ selectedTicket.title }}</h3>
              <div class="flex items-center gap-3 mt-2 text-sm opacity-80">
                <span>Relatado por <strong>{{ selectedTicket.created_by }}</strong></span>
                <span>·</span>
                <span>{{ formatDate(selectedTicket.created_at) }}</span>
              </div>
            </div>
          </div>

          <div class="p-8 space-y-6 overflow-y-auto flex-1 custom-scrollbar">
            <!-- Status atual -->
            <div class="flex items-center gap-3">
              <span class="text-sm font-bold text-slate-500">Status atual:</span>
              <StatusBadge :status="selectedTicket.status" size="lg" />
            </div>

            <!-- Descrição -->
            <div>
              <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Descrição</h4>
              <div class="bg-slate-50 rounded-xl p-4 text-slate-700 font-medium text-sm leading-relaxed whitespace-pre-wrap">{{ selectedTicket.description }}</div>
            </div>

            <!-- Informações adicionais -->
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Categoria</p>
                <p class="font-semibold text-slate-700">{{ getCategoryName(selectedTicket.category_id) }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Última Atualização</p>
                <p class="font-semibold text-slate-700">{{ selectedTicket.updated_at ? formatDate(selectedTicket.updated_at) : 'Ainda não atualizado' }}</p>
              </div>
            </div>

            <!-- Linha de progresso do status -->
            <div>
              <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Ciclo do Chamado</h4>
              <div class="flex items-center gap-0">
                <div v-for="(step, i) in statusSteps" :key="step.value" class="flex items-center flex-1">
                  <div class="flex flex-col items-center flex-1">
                    <div :class="[
                      'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all',
                      isStatusReached(selectedTicket.status, step.value)
                        ? step.activeClass
                        : 'bg-slate-100 text-slate-400'
                    ]">{{ i + 1 }}</div>
                    <p class="text-xs font-medium mt-1 text-center" :class="isStatusReached(selectedTicket.status, step.value) ? 'text-slate-700' : 'text-slate-400'">{{ step.label }}</p>
                  </div>
                  <div v-if="i < statusSteps.length - 1" :class="[
                    'h-0.5 flex-1 -mt-5 transition-all',
                    isStatusAfter(selectedTicket.status, step.value) ? 'bg-blue-400' : 'bg-slate-100'
                  ]"></div>
                </div>
              </div>
            </div>

            <!-- Follow-up (Comunicação) -->
            <div class="space-y-4 pt-6 mt-6 border-t border-slate-100">
              <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Histórico de Follow-ups
              </h4>

              <div v-if="selectedTicket.comments?.length" class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                <div 
                  v-for="comment in selectedTicket.comments" 
                  :key="comment.id"
                  class="p-4 rounded-round border border-slate-100 transition-all hover:border-slate-200 shadow-sm"
                  :class="comment.author_role === 'analista' ? 'bg-cellar-navy/5 ml-4' : 'bg-cellar-orange/5 mr-4'"
                >
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-black uppercase tracking-wider" :class="comment.author_role === 'analista' ? 'text-cellar-navy' : 'text-cellar-orange'">
                      {{ comment.author_name }} · {{ comment.author_role === 'analista' ? 'Suporte' : 'Requisitante' }}
                    </span>
                    <span class="text-[10px] text-slate-400 font-bold">{{ formatDate(comment.created_at) }}</span>
                  </div>
                  <p class="text-sm text-slate-700 font-medium leading-relaxed">{{ comment.comment }}</p>
                </div>
              </div>
              <div v-else class="text-center py-8 bg-slate-50 rounded-round border border-dashed border-slate-200">
                <p class="text-xs text-slate-400 font-black uppercase tracking-widest">Nenhuma mensagem registrada</p>
              </div>

              <!-- Novo Comentário -->
              <div class="mt-6">
                <div class="flex flex-col gap-2">
                  <textarea
                    v-model="newComment"
                    rows="2"
                    placeholder="Escreva uma mensagem ou follow-up aqui..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-navy/10 focus:border-cellar-navy outline-none transition-all font-medium text-sm text-slate-800 resize-none"
                  ></textarea>
                  <div class="flex justify-end">
                    <button 
                      @click="addComment"
                      :disabled="!newComment.trim() || submittingComment"
                      class="px-5 py-2 bg-cellar-navy text-white text-xs font-black uppercase tracking-widest rounded-pill hover:bg-cellar-navyMid transition-all disabled:opacity-50"
                    >
                      {{ submittingComment ? 'Enviando...' : 'Enviar Mensagem' }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
              <button
                v-if="userRole === 'analista'"
                @click="showDetailModal = false; openEditModal(selectedTicket)"
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100"
              >
                ✏️ Editar este Chamado
              </button>
              <button @click="showDetailModal = false" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">
                Fechar
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, onMounted, computed, defineComponent, h } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'

const toast = useToast()
const router = useRouter()
const userRole = ref(localStorage.getItem('userRole'))
const userEmail = ref(localStorage.getItem('userEmail'))

const tickets = ref([])
const categories = ref([])
const loading = ref(true)
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showDetailModal = ref(false)
const submitting = ref(false)
const selectedTicket = ref(null)
const selectedTickets = ref([])
const activeFilter = ref('todos')
const searchQuery = ref('')
const newComment = ref('')
const submittingComment = ref(false)

const form = ref({
  title: '',
  description: '',
  category_id: '',
  user_name: '',
  user_email: userEmail || '',
  department: ''
})

const API_URL = 'http://localhost:8000/api/v1'

// ── Filtros de status ──────────────────────────────────────
const statusFilters = [
  { value: 'todos', label: 'Todos', activeClass: 'bg-slate-800 text-white border-slate-800' },
  { value: 'aberto', label: 'Aguardando', activeClass: 'bg-orange-500 text-white border-orange-500' },
  { value: 'em_progresso', label: 'Em Progresso', activeClass: 'bg-blue-600 text-white border-blue-600' },
  { value: 'resolvido', label: 'Resolvidos', activeClass: 'bg-emerald-600 text-white border-emerald-600' },
]

const statusSteps = [
  { value: 'aberto', label: 'Aberto', activeClass: 'bg-orange-100 text-orange-600' },
  { value: 'em_progresso', label: 'Em Progresso', activeClass: 'bg-blue-100 text-blue-600' },
  { value: 'resolvido', label: 'Resolvido', activeClass: 'bg-emerald-100 text-emerald-700' },
]

const statusOrder = ['aberto', 'em_progresso', 'resolvido']
const isStatusReached = (current, step) => statusOrder.indexOf(current) >= statusOrder.indexOf(step)
const isStatusAfter = (current, step) => statusOrder.indexOf(current) > statusOrder.indexOf(step)

// ── Dados filtrados ────────────────────────────────────────
const myTickets = computed(() => {
  if (userRole.value === 'analista') return tickets.value
  return tickets.value.filter(t => t.created_by === userEmail.value)
})

const filteredTickets = computed(() => {
  if (activeFilter.value === 'todos') return myTickets.value
  return myTickets.value.filter(t => t.status === activeFilter.value)
})

const countByStatus = (filter) => {
  if (filter === 'todos') return myTickets.value.length
  return myTickets.value.filter(t => t.status === filter).length
}

// ── Helpers ────────────────────────────────────────────────
const formatDate = (dateStr) => {
  if (!dateStr) return '—'
  const date = new Date(dateStr)
  const now = new Date()
  const diffInSeconds = Math.floor((now - date) / 1000)

  if (diffInSeconds < 60) return 'agora mesmo'
  if (diffInSeconds < 3600) return `há ${Math.floor(diffInSeconds / 60)} min`
  if (diffInSeconds < 86400) return `há ${Math.floor(diffInSeconds / 3600)}h`
  
  return date.toLocaleString('pt-BR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  })
}

const isAllSelected = computed(() => {
  return filteredTickets.value.length > 0 && selectedTickets.value.length === filteredTickets.value.length
})

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedTickets.value = []
  } else {
    selectedTickets.value = filteredTickets.value.map(t => t.id)
  }
}

const getCategoryName = (categoryId) => {
  const cat = categories.value.find(c => c.id === categoryId)
  return cat?.name || 'Sem categoria'
}

const statusHeaderClass = (status) => {
  const map = {
    aberto: 'bg-gradient-to-r from-orange-500 to-amber-500',
    em_progresso: 'bg-gradient-to-r from-blue-600 to-indigo-600',
    resolvido: 'bg-gradient-to-r from-emerald-500 to-teal-600',
  }
  return map[status] || 'bg-gradient-to-r from-slate-600 to-slate-700'
}

// ── API Calls ──────────────────────────────────────────────
const fetchCategories = async () => {
  try {
    const { data } = await axios.get(`${API_URL}/categories`)
    categories.value = data.data
  } catch {
    toast.error('Falha ao carregar categorias. Verifique o servidor.')
  }
}

const fetchTickets = async () => {
  loading.value = true
  try {
    const params = {}
    if (activeFilter.value !== 'todos') params.status = activeFilter.value
    if (searchQuery.value) params.search = searchQuery.value

    const { data } = await axios.get(`${API_URL}/tickets`, { params })
    tickets.value = data.data
  } catch {
    toast.error('Erro ao listar chamados. Tente novamente.')
  } finally {
    loading.value = false
  }
}

let debounceTimer = null
const debouncedFetch = () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(fetchTickets, 500)
}

const createTicket = async () => {
  if (!form.value.category_id) {
    toast.warning('Selecione uma categoria para o chamado.')
    return
  }
  submitting.value = true
  try {
    await axios.post(`${API_URL}/tickets`, {
      ...form.value,
      category_id: parseInt(form.value.category_id),
      created_by: form.value.user_email || userEmail.value
    })
    toast.success('Chamado aberto com sucesso! Nossa equipe irá analisar em breve. 🎉')
    showCreateModal.value = false
    form.value = { title: '', description: '', category_id: '', user_name: '', user_email: userEmail || '', department: '' }
    await fetchTickets()
  } catch (error) {
    const msg = error.response?.data?.message || 'Erro ao abrir chamado. Tente novamente.'
    toast.error(msg)
  } finally {
    submitting.value = false
  }
}

const updateTicketInfo = async () => {
  submitting.value = true
  try {
    await axios.put(`${API_URL}/tickets/${selectedTicket.value.id}`, {
      title: selectedTicket.value.title,
      description: selectedTicket.value.description,
      category_id: selectedTicket.value.category_id,
      status: selectedTicket.value.status
    })
    toast.success('Chamado atualizado com sucesso! ✅')
    await fetchTickets()
    closeEdit()
  } catch (error) {
    const msg = error.response?.data?.message || 'Não foi possível salvar as alterações.'
    toast.error(msg)
  } finally {
    submitting.value = false
  }
}

const deleteTicket = async (id) => {
  if (!confirm('Tem certeza que deseja excluir este chamado permanentemente?')) return

  try {
    await axios.delete(`${API_URL}/tickets/${id}`)
    toast.success('Chamado excluído com sucesso!')
    await fetchTickets()
  } catch (error) {
    const msg = error.response?.data?.message || 'Erro ao excluir chamado.'
    toast.error(msg)
  }
}

const addComment = async () => {
  if (!newComment.value.trim()) return
  submittingComment.value = true
  try {
    await axios.post(`${API_URL}/tickets/${selectedTicket.value.id}/comments`, {
      comment: newComment.value,
      author_name: userRole.value === 'analista' ? 'Suporte' : (localStorage.getItem('userEmail')?.split('@')[0] || 'Cliente'),
      author_role: userRole.value
    })
    
    // Recarregar ticket para ver novo comentário
    const { data } = await axios.get(`${API_URL}/tickets/${selectedTicket.value.id}`)
    selectedTicket.value = data.data
    newComment.value = ''
    toast.success('Mensagem enviada!')
  } catch (error) {
    toast.error('Erro ao enviar mensagem.')
  } finally {
    submittingComment.value = false
  }
}

const bulkDelete = async () => {
  if (!confirm(`Tem certeza que deseja excluir ${selectedTickets.value.length} chamados permanentemente?`)) return

  try {
    await axios.post(`${API_URL}/tickets/bulk-delete`, { ids: selectedTickets.value })
    toast.success('Chamados excluídos com sucesso!')
    selectedTickets.value = []
    await fetchTickets()
  } catch (error) {
    const msg = error.response?.data?.message || 'Erro ao excluir chamados.'
    toast.error(msg)
  }
}

// ── Modals ─────────────────────────────────────────────────
const openDetail = (ticket) => {
  selectedTicket.value = JSON.parse(JSON.stringify(ticket))
  showDetailModal.value = true
  showEditModal.value = false
}

const openEditModal = (ticket) => {
  selectedTicket.value = JSON.parse(JSON.stringify(ticket))
  showEditModal.value = true
  showDetailModal.value = false
}

const closeEdit = () => {
  showEditModal.value = false
  selectedTicket.value = null
}

// ── Lifecycle ──────────────────────────────────────────────
onMounted(() => {
  if (!userEmail.value) {
    router.push('/login')
    return
  }
  fetchCategories()
  fetchTickets()
})
</script>

<!-- StatusBadge como componente inline -->
<script>
export default {
  components: {
    StatusBadge: {
      props: {
        status: { type: String, required: true },
        size: { type: String, default: 'sm' }
      },
      setup(props) {
        const config = {
          aberto: {
            label: 'Aguardando',
            class: 'bg-orange-100 border border-orange-200 text-orange-700',
            dot: 'bg-orange-400'
          },
          em_progresso: {
            label: 'Em Progresso',
            class: 'bg-blue-100 border border-blue-200 text-blue-700',
            dot: 'bg-blue-500 animate-pulse'
          },
          resolvido: {
            label: 'Resolvido',
            class: 'bg-emerald-100 border border-emerald-200 text-emerald-700',
            dot: 'bg-emerald-500'
          },
        }
        return () => {
          const c = config[props.status] || config.aberto
          const sizeClass = props.size === 'lg' ? 'px-4 py-2 text-sm' : 'px-3 py-1 text-xs'
          return h('span', {
            class: `${c.class} ${sizeClass} inline-flex items-center gap-1.5 rounded-full font-bold`
          }, [
            h('span', { class: `w-1.5 h-1.5 rounded-full ${c.dot}` }),
            c.label
          ])
        }
      }
    }
  }
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: all 0.25s ease;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.96);
}
</style>
