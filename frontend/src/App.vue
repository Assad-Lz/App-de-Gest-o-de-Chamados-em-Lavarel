<template>
  <!--
    App.vue – Componente raiz da aplicação.

    Estrutura do layout:
      - Sidebar de navegação lateral (fixo)
      - Área de conteúdo principal (muda conforme a rota)
      - Notificações toast globais
  -->
  <div id="wrapper">

    <!-- ========== Sidebar de Navegação ========== -->
    <div class="leftside-menu">

      <!-- Logo do sistema -->
      <a href="/" class="logo text-center logo-light">
        <span class="logo-lg">
          <img src="/assets/images/logo.png" alt="Logo" height="16"
               onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
          <span class="fw-bold text-white" style="font-size:18px; display:none">🎫 GestChamados</span>
        </span>
        <span class="logo-sm">
          <span class="fw-bold text-white fs-4">GC</span>
        </span>
      </a>

      <!-- Menu de navegação lateral -->
      <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">

          <li class="side-nav-title">Menu Principal</li>

          <li class="side-nav-item">
            <router-link to="/" class="side-nav-link">
              <i class="uil-home-alt"></i>
              <span>Dashboard</span>
            </router-link>
          </li>

          <li class="side-nav-item">
            <router-link to="/chamados" class="side-nav-link">
              <i class="uil-ticket"></i>
              <span>Chamados</span>
            </router-link>
          </li>

          <li class="side-nav-item">
            <router-link to="/categorias" class="side-nav-link">
              <i class="uil-tag-alt"></i>
              <span>Categorias</span>
            </router-link>
          </li>

        </ul>

        <!-- Botão de criar chamado rápido no rodapé da sidebar -->
        <div class="clearfix"></div>
      </div>
    </div>
    <!-- /Sidebar -->

    <!-- ========== Área de Conteúdo Principal ========== -->
    <div class="content-page">
      <div class="content">

        <!-- Topbar -->
        <div class="navbar-custom">
          <ul class="list-unstyled topbar-menu float-end mb-0">
            <li class="dropdown notification-list">
              <span class="badge bg-soft-primary text-primary rounded-pill me-3">
                Sistema de Gestão de Chamados
              </span>
            </li>
          </ul>
          <button class="button-menu-mobile open-left" id="sidebar-toggle">
            <i class="mdi mdi-menu"></i>
          </button>
          <div class="app-search dropdown d-none d-lg-block"></div>
        </div>

        <!-- Conteúdo dinâmico da rota atual -->
        <div class="container-fluid">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </div>

      </div>

      <!-- Footer -->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 text-center">
              <span class="text-muted">
                Gestão de Chamados &copy; {{ anoAtual }} – Desenvolvido com Laravel + Vue.js
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <!-- /Conteúdo -->

    <!-- Toast de notificação global -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99999">
      <div v-if="toast.visible"
           :class="['toast show align-items-center text-white border-0', `bg-${toast.tipo}`]"
           role="alert"
           aria-live="assertive">
        <div class="d-flex">
          <div class="toast-body">
            <i :class="toast.tipo === 'success' ? 'uil-check-circle' : 'uil-exclamation-triangle'"></i>
            {{ toast.mensagem }}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto"
                  @click="fecharToast"></button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
/**
 * App.vue – Lógica do componente raiz.
 *
 * Fornece o estado global de notificações toast via provide/inject,
 * permitindo que qualquer componente filho exiba notificações.
 */
import { reactive, computed, provide } from 'vue'

// Estado reativo para o sistema de notificações
const toast = reactive({
  visible: false,
  mensagem: '',
  tipo: 'success', // 'success' | 'danger' | 'warning'
})

let toastTimer = null

/**
 * Exibe uma notificação toast por 4 segundos.
 *
 * @param {string} mensagem - Texto da notificação
 * @param {string} tipo     - Tipo: 'success', 'danger', 'warning'
 */
function mostrarToast(mensagem, tipo = 'success') {
  // Cancela timer anterior se houver
  if (toastTimer) clearTimeout(toastTimer)

  toast.visible = true
  toast.mensagem = mensagem
  toast.tipo = tipo

  // Auto-fecha após 4 segundos
  toastTimer = setTimeout(() => {
    toast.visible = false
  }, 4000)
}

function fecharToast() {
  toast.visible = false
}

// Disponibiliza o notificador para todos os componentes filhos
provide('mostrarToast', mostrarToast)

const anoAtual = computed(() => new Date().getFullYear())
</script>

<style>
/* Transição suave ao trocar de página */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Estilo base da fonte para toda a aplicação */
body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}
</style>
