<template>
  <div class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto pt-8">
      <span class="inline-block px-4 py-1.5 bg-cellar-orange/10 text-cellar-orange rounded-pill text-[10px] font-black uppercase tracking-[0.2em] mb-4">Uso da Plataforma</span>
      <h1 class="text-5xl font-black text-cellar-navy tracking-tight mb-6">Termos de Uso</h1>
      <p class="text-cellar-gray text-lg font-medium leading-relaxed">
        Regras fundamentais para garantir a melhor experiência de aprendizado e uso da nossa central de gestão.
      </p>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-round shadow-sm border border-slate-100 p-10 max-w-4xl mx-auto relative group overflow-hidden">
      <div class="absolute top-0 left-0 w-32 h-32 bg-cellar-orange/5 rounded-full -ml-16 -mt-16 transition-transform group-hover:scale-110"></div>
      
      <div class="relative z-10 space-y-8 text-cellar-dark">
        <section>
          <h2 class="text-xl font-black text-cellar-navy uppercase tracking-widest mb-4 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-cellar-orange/10 text-cellar-orange flex items-center justify-center text-xs">01</span>
            Finalidade Educacional
          </h2>
          <p class="text-cellar-gray leading-relaxed font-medium">
            Este software é um projeto de estudo e teste técnico. Seu uso é destinado exclusivamente para fins de demonstração, aprendizado de arquitetura Laravel/Vue e avaliação técnica.
          </p>
        </section>

        <section>
          <h2 class="text-xl font-black text-cellar-navy uppercase tracking-widest mb-4 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-cellar-orange/10 text-cellar-orange flex items-center justify-center text-xs">02</span>
            Uso Não Comercial
          </h2>
          <p class="text-cellar-gray leading-relaxed font-medium">
            É terminantemente proibido o uso deste código ou da plataforma para fins comerciais, revenda ou lucro direto. O projeto segue a licença CC BY-NC-SA 4.0.
          </p>
        </section>

        <section>
          <h2 class="text-xl font-black text-cellar-navy uppercase tracking-widest mb-4 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-cellar-orange/10 text-cellar-orange flex items-center justify-center text-xs">03</span>
            Limitação de Responsabilidade
          </h2>
          <p class="text-cellar-gray leading-relaxed font-medium">
            Embora apliquemos técnicas de segurança severas (Hardening), este sistema não deve ser utilizado para gerenciar infraestruturas críticas ou dados sensíveis reais em produção.
          </p>
        </section>
      </div>
    </div>

    <!-- Footer Banner (Requested) -->
    <div class="relative overflow-hidden rounded-round shadow-2xl min-h-[300px] flex items-center group max-w-5xl mx-auto">
      <div class="absolute inset-0 z-0">
        <img src="/assets/images/cellar_vinhos_cover.jpeg" alt="Cellar Vinhos Background" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-r from-cellar-navy/90 via-cellar-navy/60 to-transparent"></div>
      </div>

      <div class="relative z-10 p-12 text-white">
        <h3 class="text-3xl font-black mb-2">Compromisso com o Código</h3>
        <p class="text-white/80 font-medium max-w-md">
          A qualidade técnica da Cellar Vinhos reflete nosso compromisso com a excelência operacional em cada linha de código.
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
