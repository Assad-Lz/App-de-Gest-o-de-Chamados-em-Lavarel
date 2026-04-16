<template>
  <div class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto pt-8">
      <span class="inline-block px-4 py-1.5 bg-cellar-navyMid/10 text-cellar-navyMid rounded-pill text-[10px] font-black uppercase tracking-[0.2em] mb-4">Institucional</span>
      <h1 class="text-5xl font-black text-cellar-navy tracking-tight mb-6">Política de Privacidade</h1>
      <p class="text-cellar-gray text-lg font-medium leading-relaxed">
        Sua privacidade é nossa prioridade. Esta página detalha como lidamos com as informações coletadas durante o uso desta plataforma de estudos.
      </p>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-round shadow-sm border border-slate-100 p-10 max-w-4xl mx-auto relative group overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 bg-cellar-navy/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
      
      <div class="relative z-10 space-y-8 text-cellar-dark">
        <section>
          <h2 class="text-xl font-black text-cellar-navy uppercase tracking-widest mb-4 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-cellar-navyMid/10 text-cellar-navyMid flex items-center justify-center text-xs">01</span>
            Coleta de Dados
          </h2>
          <p class="text-cellar-gray leading-relaxed font-medium">
            Esta é uma plataforma de estudos e testes técnicos. Não coletamos nem transmitimos dados para servidores externos fora do ambiente de banco de dados configurado pelo próprio usuário.
          </p>
        </section>

        <section>
          <h2 class="text-xl font-black text-cellar-navy uppercase tracking-widest mb-4 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-cellar-navyMid/10 text-cellar-navyMid flex items-center justify-center text-xs">02</span>
            Armazenamento Local
          </h2>
          <p class="text-cellar-gray leading-relaxed font-medium">
            Todas as informações inseridas (títulos de chamados, descrições, e-mails de contato) são processadas em seu banco de dados privado. Recomendamos o uso de dados fictícios para fins de teste.
          </p>
        </section>

        <section>
          <h2 class="text-xl font-black text-cellar-navy uppercase tracking-widest mb-4 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-cellar-navyMid/10 text-cellar-navyMid flex items-center justify-center text-xs">03</span>
            Cookies e Sessão
          </h2>
          <p class="text-cellar-gray leading-relaxed font-medium">
            Utilizamos apenas o armazenamento local do navegador (LocalStorage) para manter sua sessão ativa enquanto você navega pelo painel. Nenhum rastreio persistente é realizado.
          </p>
        </section>
      </div>
    </div>

    <!-- Footer Banner (Requested) -->
    <div class="relative overflow-hidden rounded-round shadow-2xl min-h-[300px] flex items-center group max-w-5xl mx-auto">
      <div class="absolute inset-0 z-0">
        <img src="/assets/images/cellar_vinhos_cover.jpeg" alt="Cellar Vinhos Background" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-r from-cellar-wine/90 via-cellar-wine/60 to-transparent"></div>
      </div>

      <div class="relative z-10 p-12 text-white">
        <h3 class="text-3xl font-black mb-2">Transparência e Respeito</h3>
        <p class="text-white/80 font-medium max-w-md">
          A Cellar Vinhos acredita na clareza de processos, tanto no suporte técnico quanto no tratamento de dados.
        </p>
        <div class="mt-6">
          <router-link to="/" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white border border-white/30 px-6 py-3 rounded-pill text-xs font-black uppercase tracking-widest transition-all">
            Voltar ao Dashboard
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Sem lógica necessária por enquanto
</script>
