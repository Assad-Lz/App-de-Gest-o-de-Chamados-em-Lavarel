<template>
  <div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Categorias</h1>
        <p class="text-slate-500 mt-1">Sistemas de classificação para as filas de atendimento.</p>
      </div>
      <button class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium shadow-md shadow-slate-200 transition-all hover:-translate-y-0.5">
        Nova Divisão
      </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- Card Categoria 1 -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1 cursor-pointer ring-1 ring-transparent hover:ring-indigo-500/10">
        <div>
          <div class="flex justify-between items-start mb-4">
            <span class="inline-flex p-3.5 rounded-xl bg-gradient-to-br from-indigo-50 to-blue-50 text-indigo-600 ring-1 ring-indigo-100 group-hover:scale-110 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </span>
            <button class="text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 bg-red-50 p-1.5 rounded-lg">
              <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </button>
          </div>
          <h3 class="text-lg font-bold text-slate-800">Hardware & Equipamentos</h3>
          <p class="text-sm text-slate-500 mt-2 leading-relaxed">Danos físicos, troca de peças, formatação de computadores e reposição de periféricos em toda a empresa.</p>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Abertos: 12</span>
          <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-50 text-emerald-600 uppercase tracking-wider">Online</span>
        </div>
      </div>

      <!-- Card Categoria 2 -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1 cursor-pointer ring-1 ring-transparent hover:ring-rose-500/10">
        <div>
          <div class="flex justify-between items-start mb-4">
            <span class="inline-flex p-3.5 rounded-xl bg-gradient-to-br from-rose-50 to-pink-50 text-rose-600 ring-1 ring-rose-100 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
            <button class="text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 bg-red-50 p-1.5 rounded-lg">
              <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </button>
          </div>
          <h3 class="text-lg font-bold text-slate-800">Acessos & Permissões</h3>
          <p class="text-sm text-slate-500 mt-2 leading-relaxed">Liberação de catracas, reset de credenciais AD, e liberação de Firewall e VPN.</p>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Abertos: 3</span>
          <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-50 text-emerald-600 uppercase tracking-wider">Online</span>
        </div>
      </div>

    </div>
  </div>
</template>
