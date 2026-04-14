<template>
  <div class="space-y-8 animate-in fade-in duration-500">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>
        <p class="text-slate-500 mt-1">Resumo gerencial em tempo real.</p>
      </div>
      <div class="h-10 w-10 bg-white rounded-full shadow flex items-center justify-center text-blue-600 ring-1 ring-slate-100 cursor-pointer hover:bg-blue-50 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
      </div>
    </div>

    <!-- Cards Premium -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Card Total -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100/60 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
        <p class="text-sm font-semibold text-slate-500 mb-1 z-10 flex items-center gap-2">
           <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
           Total de Chamados
        </p>
        <h3 class="text-5xl font-extrabold text-slate-800 z-10 mt-2">24</h3>
      </div>

      <!-- Card Aberto -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100/60 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
         <p class="text-sm font-semibold text-slate-500 mb-1 z-10 flex items-center gap-2">
           <svg class="w-4 h-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
           Aguardando Análise
        </p>
        <h3 class="text-5xl font-extrabold text-yellow-600 z-10 mt-2">8</h3>
      </div>

      <!-- Card Em Progresso -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100/60 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300">
         <div class="absolute -right-6 -top-6 w-32 h-32 bg-gradient-to-br from-purple-50 to-fuchsia-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
        <p class="text-sm font-semibold text-slate-500 mb-1 z-10 flex items-center gap-2">
           <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
           Em Progresso
        </p>
        <h3 class="text-5xl font-extrabold text-purple-600 z-10 mt-2">4</h3>
      </div>

      <!-- Card Resolvido -->
      <div class="bg-gradient-to-br from-emerald-500 text-white to-teal-600 rounded-2xl shadow-md p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 duration-300">
         <div class="absolute -right-6 -top-6 w-32 h-32 bg-white rounded-full opacity-10 group-hover:scale-125 transition-transform duration-500"></div>
        <p class="text-sm font-semibold opacity-90 mb-1 z-10 flex items-center gap-2">
           <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
           Finalizados
        </p>
        <h3 class="text-5xl font-extrabold z-10 mt-2">12</h3>
      </div>

    </div>
  </div>
</template>
