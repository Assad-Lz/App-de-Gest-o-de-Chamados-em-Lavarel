<template>
  <!--
    TicketsView.vue – Página principal de Gestão de Chamados.

    Funcionalidades:
      - Listagem de chamados com filtros reativos por status e categoria
      - Botão para criar novo chamado (abre modal)
      - Ações de edição e exclusão por linha
      - Modal de criação e edição compartilhado
  -->
  <div>

    <!-- ========== Breadcrumb / Cabeçalho da Página ========== -->
    <div class="row">
      <div class="col-12">
        <div class="page-title-box">
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
              <li class="breadcrumb-item active">Chamados</li>
            </ol>
          </div>
          <h4 class="page-title">
            <i class="uil-ticket me-2"></i>Gestão de Chamados
          </h4>
        </div>
      </div>
    </div>

    <!-- ========== Cards de Estatísticas ========== -->
    <div class="row">
      <div class="col-md-4">
        <div class="card widget-flat text-bg-primary">
          <div class="card-body">
            <div class="float-end"><i class="uil-ticket fs-2 opacity-50"></i></div>
            <h6 class="text-uppercase mt-0 mb-2 fw-semibold opacity-75">Total de Chamados</h6>
            <h2 class="mb-0">{{ tickets.length }}</h2>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card widget-flat text-bg-warning">
          <div class="card-body">
            <div class="float-end"><i class="uil-clock fs-2 opacity-50"></i></div>
            <h6 class="text-uppercase mt-0 mb-2 fw-semibold opacity-75">Em Aberto</h6>
            <h2 class="mb-0">{{ contagemStatus('aberto') }}</h2>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card widget-flat text-bg-success">
          <div class="card-body">
            <div class="float-end"><i class="uil-check-circle fs-2 opacity-50"></i></div>
            <h6 class="text-uppercase mt-0 mb-2 fw-semibold opacity-75">Resolvidos</h6>
            <h2 class="mb-0">{{ contagemStatus('resolvido') }}</h2>
          </div>
        </div>
      </div>
    </div>

    <!-- ========== Filtros + Botão Criar ========== -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <div class="row mb-3 align-items-end">
              <!-- Filtro por Status -->
              <div class="col-md-4">
                <label class="form-label fw-semibold">Filtrar por Status</label>
                <select v-model="filtros.status" @change="carregarTickets" class="form-select">
                  <option value="">Todos os status</option>
                  <option value="aberto">Aberto</option>
                  <option value="em_progresso">Em Progresso</option>
                  <option value="resolvido">Resolvido</option>
                </select>
              </div>

              <!-- Filtro por Categoria -->
              <div class="col-md-4">
                <label class="form-label fw-semibold">Filtrar por Categoria</label>
                <select v-model="filtros.category_id" @change="carregarTickets" class="form-select">
                  <option value="">Todas as categorias</option>
                  <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>

              <!-- Botão de criar chamado -->
              <div class="col-md-4 d-flex justify-content-end">
                <button @click="abrirModalCriar" class="btn btn-primary">
                  <i class="uil-plus me-1"></i>Novo Chamado
                </button>
              </div>
            </div>

            <!-- ========== Tabela de Chamados ========== -->
            <div class="table-responsive">
              <table class="table table-centered table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th class="text-center">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Estado de carregamento -->
                  <tr v-if="carregando">
                    <td colspan="6" class="text-center py-4">
                      <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                      Carregando chamados...
                    </td>
                  </tr>

                  <!-- Estado vazio -->
                  <tr v-else-if="tickets.length === 0">
                    <td colspan="6" class="text-center py-5 text-muted">
                      <i class="uil-inbox fs-1 d-block mb-2"></i>
                      Nenhum chamado encontrado para os filtros selecionados.
                    </td>
                  </tr>

                  <!-- Linhas dos chamados -->
                  <tr v-else v-for="ticket in tickets" :key="ticket.id">
                    <td class="text-muted fw-semibold">#{{ ticket.id }}</td>
                    <td>
                      <span class="fw-semibold">{{ ticket.title }}</span>
                      <p class="text-muted mb-0 small text-truncate" style="max-width:280px">
                        {{ ticket.description }}
                      </p>
                    </td>
                    <td>
                      <span class="badge bg-light text-dark">
                        {{ nomeDaCategoria(ticket.category_id) }}
                      </span>
                    </td>
                    <td>
                      <span :class="badgeDoStatus(ticket.status)">
                        {{ labelDoStatus(ticket.status) }}
                      </span>
                    </td>
                    <td class="text-muted small">
                      {{ formatarData(ticket.created_at) }}
                    </td>
                    <td class="text-center">
                      <button @click="abrirModalEditar(ticket)"
                              class="btn btn-sm btn-soft-primary me-1"
                              title="Editar chamado">
                        <i class="uil-edit"></i>
                      </button>
                      <button @click="confirmarDelecao(ticket)"
                              class="btn btn-sm btn-soft-danger"
                              title="Excluir chamado">
                        <i class="uil-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /Tabela -->

          </div>
        </div>
      </div>
    </div>

    <!-- ========== Modal: Criar / Editar Chamado ========== -->
    <div v-if="modal.aberto"
         class="modal fade show d-block"
         tabindex="-1"
         style="background: rgba(0,0,0,0.5)"
         @click.self="fecharModal">

      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">
              <i :class="modal.modo === 'criar' ? 'uil-plus-circle' : 'uil-edit'" class="me-2"></i>
              {{ modal.modo === 'criar' ? 'Novo Chamado' : 'Editar Chamado' }}
            </h5>
            <button type="button" class="btn-close" @click="fecharModal"></button>
          </div>

          <form @submit.prevent="salvar">
            <div class="modal-body">

              <!-- Honeypot: campo oculto que humanos NUNCA preenchem -->
              <!-- Se preenchido, o backend detecta como bot -->
              <input type="text" name="website" style="display:none !important"
                     tabindex="-1" autocomplete="off" v-model="form.website" />

              <!-- Título -->
              <div class="mb-3">
                <label for="ticket-title" class="form-label fw-semibold">
                  Título <span class="text-danger">*</span>
                </label>
                <input id="ticket-title"
                       v-model="form.title"
                       type="text"
                       class="form-control"
                       :class="{ 'is-invalid': erros.title }"
                       placeholder="Descreva brevemente o problema..."
                       maxlength="255"
                       required />
                <div v-if="erros.title" class="invalid-feedback">{{ erros.title }}</div>
              </div>

              <!-- Descrição -->
              <div class="mb-3">
                <label for="ticket-desc" class="form-label fw-semibold">
                  Descrição <span class="text-danger">*</span>
                </label>
                <textarea id="ticket-desc"
                          v-model="form.description"
                          class="form-control"
                          :class="{ 'is-invalid': erros.description }"
                          rows="4"
                          placeholder="Descreva o problema em detalhes..."
                          required></textarea>
                <div v-if="erros.description" class="invalid-feedback">{{ erros.description }}</div>
              </div>

              <div class="row">
                <!-- Categoria -->
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="ticket-cat" class="form-label fw-semibold">
                      Categoria <span class="text-danger">*</span>
                    </label>
                    <select id="ticket-cat"
                            v-model="form.category_id"
                            class="form-select"
                            :class="{ 'is-invalid': erros.category_id }"
                            required>
                      <option value="">Selecione uma categoria...</option>
                      <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                        {{ cat.name }}
                      </option>
                    </select>
                    <div v-if="erros.category_id" class="invalid-feedback">{{ erros.category_id }}</div>
                  </div>
                </div>

                <!-- Status (apenas na edição) -->
                <div class="col-md-6" v-if="modal.modo === 'editar'">
                  <div class="mb-3">
                    <label for="ticket-status" class="form-label fw-semibold">Status</label>
                    <select id="ticket-status" v-model="form.status" class="form-select">
                      <option value="aberto">Aberto</option>
                      <option value="em_progresso">Em Progresso</option>
                      <option value="resolvido">Resolvido</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-light" @click="fecharModal">
                <i class="uil-times me-1"></i>Cancelar
              </button>
              <button type="submit" class="btn btn-primary" :disabled="salvando">
                <span v-if="salvando" class="spinner-border spinner-border-sm me-2"></span>
                <i v-else class="uil-save me-1"></i>
                {{ salvando ? 'Salvando...' : 'Salvar' }}
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
/**
 * TicketsView.vue – Lógica da página de Chamados.
 *
 * Toda comunicação com a API é feita EXCLUSIVAMENTE
 * através dos serviços (ticketService, categoryService).
 * Nenhuma chamada direta ao axios é feita aqui.
 */
import { ref, reactive, onMounted, inject } from 'vue'
import ticketService, { STATUS_LABELS, STATUS_BADGES } from '@/services/ticketService'
import categoryService from '@/services/categoryService'

// Sistema de notificações injetado do componente raiz (App.vue)
const mostrarToast = inject('mostrarToast')

// Estado da listagem
const tickets = ref([])
const categorias = ref([])
const carregando = ref(false)

// Filtros reativos (atualizam a lista automaticamente)
const filtros = reactive({ status: '', category_id: '' })

// Estado do modal de criação/edição
const modal = reactive({ aberto: false, modo: 'criar' })
const salvando = ref(false)
const erros = reactive({})

// Formulário do modal
const form = reactive({
  title: '',
  description: '',
  category_id: '',
  status: 'aberto',
  website: '',      // Campo honeypot (sempre vazio para humanos)
})

let ticketEmEdicao = null

// ────────────────────────────────────────────────────────
// Carregamento de dados
// ────────────────────────────────────────────────────────

/**
 * Carrega a lista de tickets aplicando os filtros ativos.
 */
async function carregarTickets() {
  carregando.value = true
  try {
    tickets.value = await ticketService.listar(filtros)
  } catch (erro) {
    mostrarToast(`Erro ao carregar chamados: ${erro.message}`, 'danger')
  } finally {
    carregando.value = false
  }
}

/**
 * Carrega as categorias para o select do formulário.
 */
async function carregarCategorias() {
  try {
    categorias.value = await categoryService.listar()
  } catch {
    mostrarToast('Erro ao carregar categorias.', 'danger')
  }
}

// ────────────────────────────────────────────────────────
// Ações do modal
// ────────────────────────────────────────────────────────

function abrirModalCriar() {
  Object.assign(form, { title: '', description: '', category_id: '', status: 'aberto', website: '' })
  Object.assign(erros, {})
  ticketEmEdicao = null
  modal.modo = 'criar'
  modal.aberto = true
}

function abrirModalEditar(ticket) {
  Object.assign(form, {
    title: ticket.title,
    description: ticket.description,
    category_id: ticket.category_id,
    status: ticket.status,
    website: '',
  })
  Object.assign(erros, {})
  ticketEmEdicao = ticket
  modal.modo = 'editar'
  modal.aberto = true
}

function fecharModal() {
  modal.aberto = false
}

/**
 * Salva o chamado (criar ou editar) via serviço.
 */
async function salvar() {
  salvando.value = true
  Object.assign(erros, {})

  try {
    const dados = {
      title: form.title,
      description: form.description,
      category_id: form.category_id,
      website: form.website, // Enviado para o backend detectar bots
    }

    if (modal.modo === 'criar') {
      await ticketService.criar(dados)
      mostrarToast('Chamado criado com sucesso!', 'success')
    } else {
      await ticketService.atualizar(ticketEmEdicao.id, { ...dados, status: form.status })
      mostrarToast('Chamado atualizado com sucesso!', 'success')
    }

    fecharModal()
    await carregarTickets()
  } catch (erro) {
    mostrarToast(erro.message, 'danger')
  } finally {
    salvando.value = false
  }
}

/**
 * Confirma e executa a exclusão de um chamado.
 */
async function confirmarDelecao(ticket) {
  if (!confirm(`Deseja excluir o chamado "#${ticket.id} – ${ticket.title}"?`)) return

  try {
    await ticketService.deletar(ticket.id)
    mostrarToast('Chamado excluído com sucesso!', 'success')
    await carregarTickets()
  } catch (erro) {
    mostrarToast(erro.message, 'danger')
  }
}

// ────────────────────────────────────────────────────────
// Helpers de apresentação
// ────────────────────────────────────────────────────────

function contagemStatus(status) {
  return tickets.value.filter(t => t.status === status).length
}

function nomeDaCategoria(id) {
  return categorias.value.find(c => c.id === id)?.name ?? '–'
}

function labelDoStatus(status) {
  return STATUS_LABELS[status] ?? status
}

function badgeDoStatus(status) {
  return STATUS_BADGES[status] ?? 'badge bg-secondary'
}

function formatarData(dataISO) {
  if (!dataISO) return '–'
  return new Date(dataISO).toLocaleString('pt-BR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}

// ────────────────────────────────────────────────────────
// Inicialização
// ────────────────────────────────────────────────────────
onMounted(async () => {
  await carregarCategorias()
  await carregarTickets()
})
</script>
