<template>
  <!-- DashboardView.vue – Painel de visão geral do sistema -->
  <div>
    <div class="row">
      <div class="col-12">
        <div class="page-title-box">
          <h4 class="page-title">
            <i class="uil-home-alt me-2"></i>Dashboard
          </h4>
        </div>
      </div>
    </div>

    <!-- Cards de resumo -->
    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
          <div class="card-body">
            <div class="float-end">
              <i class="uil-ticket text-primary" style="font-size:36px"></i>
            </div>
            <h6 class="text-uppercase mt-0 text-muted fw-semibold">Total de Chamados</h6>
            <h2 class="mb-0 text-primary">{{ stats.total }}</h2>
            <p class="mb-0 text-muted mt-2 small">
              <router-link to="/chamados" class="text-primary">Ver todos →</router-link>
            </p>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
          <div class="card-body">
            <div class="float-end">
              <i class="uil-clock text-warning" style="font-size:36px"></i>
            </div>
            <h6 class="text-uppercase mt-0 text-muted fw-semibold">Em Aberto</h6>
            <h2 class="mb-0 text-warning">{{ stats.aberto }}</h2>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
          <div class="card-body">
            <div class="float-end">
              <i class="uil-spinner-alt text-info" style="font-size:36px"></i>
            </div>
            <h6 class="text-uppercase mt-0 text-muted fw-semibold">Em Progresso</h6>
            <h2 class="mb-0 text-info">{{ stats.em_progresso }}</h2>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
          <div class="card-body">
            <div class="float-end">
              <i class="uil-check-circle text-success" style="font-size:36px"></i>
            </div>
            <h6 class="text-uppercase mt-0 text-muted fw-semibold">Resolvidos</h6>
            <h2 class="mb-0 text-success">{{ stats.resolvido }}</h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Últimos chamados -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Chamados Recentes</h5>
            <router-link to="/chamados" class="btn btn-sm btn-primary">Ver todos</router-link>
          </div>
          <div class="card-body p-0">
            <div v-if="carregando" class="text-center py-4">
              <div class="spinner-border text-primary spinner-border-sm"></div>
            </div>
            <div v-else>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>#</th><th>Título</th><th>Status</th><th>Criado em</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="recentes.length === 0">
                      <td colspan="4" class="text-center py-4 text-muted">
                        Nenhum chamado cadastrado ainda.
                      </td>
                    </tr>
                    <tr v-for="t in recentes" :key="t.id">
                      <td class="text-muted">#{{ t.id }}</td>
                      <td class="fw-semibold">{{ t.title }}</td>
                      <td>
                        <span :class="badgeStatus(t.status)">{{ labelStatus(t.status) }}</span>
                      </td>
                      <td class="text-muted small">{{ formatarData(t.created_at) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import ticketService, { STATUS_LABELS, STATUS_BADGES } from '@/services/ticketService'

const carregando = ref(false)
const recentes = ref([])
const stats = reactive({ total: 0, aberto: 0, em_progresso: 0, resolvido: 0 })

function labelStatus(s) { return STATUS_LABELS[s] ?? s }
function badgeStatus(s) { return STATUS_BADGES[s] ?? 'badge bg-secondary' }
function formatarData(d) {
  if (!d) return '–'
  return new Date(d).toLocaleString('pt-BR', { day:'2-digit', month:'2-digit', year:'numeric' })
}

onMounted(async () => {
  carregando.value = true
  try {
    const todos = await ticketService.listar()
    stats.total = todos.length
    stats.aberto = todos.filter(t => t.status === 'aberto').length
    stats.em_progresso = todos.filter(t => t.status === 'em_progresso').length
    stats.resolvido = todos.filter(t => t.status === 'resolvido').length
    recentes.value = todos.slice(0, 5)
  } finally {
    carregando.value = false
  }
})
</script>
