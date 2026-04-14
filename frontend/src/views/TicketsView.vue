<template>
  <div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Chamados</h1>
        <p class="text-slate-500 mt-1">Gerencie e responda os tickets de suporte.</p>
      </div>
      <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-md shadow-blue-200 transition-all flex items-center gap-2 hover:-translate-y-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Chamado
      </button>
    </div>

    <!-- Filtros Glassmorphism -->
    <div class="bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center ring-1 ring-slate-900/5 hover:shadow-md transition-all">
      <div class="flex-1 w-full relative group">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500 transition-colors" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
        </div>
        <input type="text" class="block w-full pl-11 pr-4 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all text-slate-700" placeholder="Pesquisar protocolo, erro ou cliente..." />
      </div>
      <div class="flex gap-3 w-full md:w-auto">
        <select class="block w-full pl-4 pr-10 py-2.5 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-slate-700 cursor-pointer font-medium hover:bg-slate-100 transition-colors">
          <option value="">Status: Todos</option>
          <option value="open">Aguardando Análise</option>
          <option value="in_progress">Em Progresso</option>
          <option value="resolved">Finalizado</option>
        </select>
      </div>
    </div>

    <!-- Tabela Premium -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden ring-1 ring-slate-900/5">
      <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-100">
        <thead class="bg-slate-50/80">
          <tr>
            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Protocolo</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Assunto do Chamado</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Urgência</th>
            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Acões</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-50">
          
          <!-- Item Mockado 1 -->
          <tr class="hover:bg-slate-50/80 transition-colors group cursor-pointer">
            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-blue-600">
              #TKT-1024
            </td>
            <td class="px-6 py-5 min-w-[300px]">
              <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Erro ao tentar anexar documentação do RH</span>
                <span class="text-sm text-slate-500 truncate max-w-sm mt-0.5">O sistema reporta timeout ao tentar enviar arquivos com mais de 2MB pelo portal...</span>
              </div>
            </td>
            <td class="px-6 py-5 whitespace-nowrap">
              <span class="px-3 py-1 inline-flex text-xs font-bold rounded-lg bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200/50 items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Finalizado
              </span>
            </td>
            <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">
              Baixa
            </td>
            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
              <button class="text-slate-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-colors shadow-sm bg-white border border-slate-100" title="Ver Detalhes">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
            </td>
          </tr>

          <!-- Item Mockado 2 -->
          <tr class="hover:bg-slate-50/80 transition-colors group cursor-pointer">
            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-blue-600">
              #TKT-1025
            </td>
            <td class="px-6 py-5 min-w-[300px]">
              <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Servidor de Licenças Fora do Ar</span>
                <span class="text-sm text-slate-500 truncate max-w-sm mt-0.5">Nenhum computador da bancada 3 consegue obter IP pelo roteador principal...</span>
              </div>
            </td>
            <td class="px-6 py-5 whitespace-nowrap">
              <span class="px-3 py-1 inline-flex text-xs font-bold rounded-lg bg-orange-100 text-orange-800 ring-1 ring-orange-200/50 items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-600 animate-pulse"></span>
                Em Aberto
              </span>
            </td>
            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-red-600 flex items-center gap-1">
              <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
              Crítica
            </td>
            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
              <button class="text-slate-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-colors shadow-sm bg-white border border-slate-100" title="Ver Detalhes">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
            </td>
          </tr>

        </tbody>
      </table>
      </div>
    </div>
  </div>
</template>
