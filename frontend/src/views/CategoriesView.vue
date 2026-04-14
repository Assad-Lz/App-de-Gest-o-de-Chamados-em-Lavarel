<template>
  <!-- CategoriesView.vue – CRUD de Categorias -->
  <div>
    <div class="row">
      <div class="col-12">
        <div class="page-title-box">
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
              <li class="breadcrumb-item active">Categorias</li>
            </ol>
          </div>
          <h4 class="page-title">
            <i class="uil-tag-alt me-2"></i>Gestão de Categorias
          </h4>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Formulário de criação/edição -->
      <div class="col-lg-4">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i :class="modoEdicao ? 'uil-edit' : 'uil-plus-circle'" class="me-2"></i>
              {{ modoEdicao ? 'Editar Categoria' : 'Nova Categoria' }}
            </h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="salvar">
              <!-- Campo honeypot oculto -->
              <input type="text" name="telefone_extra"
                     style="display:none !important" tabindex="-1"
                     autocomplete="off" v-model="form.telefone_extra" />

              <div class="mb-3">
                <label for="cat-nome" class="form-label fw-semibold">
                  Nome da Categoria <span class="text-danger">*</span>
                </label>
                <input id="cat-nome"
                       v-model="form.name"
                       type="text"
                       class="form-control"
                       placeholder="Ex: Suporte Técnico"
                       maxlength="255"
                       required />
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1" :disabled="salvando">
                  <span v-if="salvando" class="spinner-border spinner-border-sm me-1"></span>
                  <i v-else :class="modoEdicao ? 'uil-save' : 'uil-plus'" class="me-1"></i>
                  {{ modoEdicao ? 'Salvar' : 'Criar' }}
                </button>
                <button v-if="modoEdicao" type="button"
                        class="btn btn-light" @click="cancelarEdicao">
                  Cancelar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Lista de categorias -->
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Categorias Cadastradas</h5>
            <span class="badge bg-primary rounded-pill">{{ categorias.length }}</span>
          </div>
          <div class="card-body p-0">
            <div v-if="carregando" class="text-center py-5">
              <div class="spinner-border text-primary"></div>
            </div>
            <div v-else-if="categorias.length === 0" class="text-center py-5 text-muted">
              <i class="uil-folder-open fs-1 d-block mb-2"></i>
              Nenhuma categoria cadastrada ainda.
            </div>
            <ul v-else class="list-group list-group-flush">
              <li v-for="cat in categorias" :key="cat.id"
                  class="list-group-item d-flex align-items-center justify-content-between py-3">
                <div>
                  <span class="fw-semibold">{{ cat.name }}</span>
                  <small class="d-block text-muted">ID: {{ cat.id }}</small>
                </div>
                <div>
                  <button @click="iniciarEdicao(cat)"
                          class="btn btn-sm btn-soft-primary me-1">
                    <i class="uil-edit"></i>
                  </button>
                  <button @click="deletar(cat)"
                          class="btn btn-sm btn-soft-danger">
                    <i class="uil-trash-alt"></i>
                  </button>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, inject } from 'vue'
import categoryService from '@/services/categoryService'

const mostrarToast = inject('mostrarToast')

const categorias = ref([])
const carregando = ref(false)
const salvando = ref(false)
const modoEdicao = ref(false)
let categoriaEmEdicao = null

const form = reactive({ name: '', telefone_extra: '' })

async function carregar() {
  carregando.value = true
  try {
    categorias.value = await categoryService.listar()
  } catch (e) {
    mostrarToast('Erro ao carregar categorias.', 'danger')
  } finally {
    carregando.value = false
  }
}

function iniciarEdicao(cat) {
  categoriaEmEdicao = cat
  form.name = cat.name
  modoEdicao.value = true
}

function cancelarEdicao() {
  form.name = ''
  form.telefone_extra = ''
  modoEdicao.value = false
  categoriaEmEdicao = null
}

async function salvar() {
  salvando.value = true
  try {
    if (modoEdicao.value) {
      await categoryService.atualizar(categoriaEmEdicao.id, { name: form.name })
      mostrarToast('Categoria atualizada com sucesso!', 'success')
    } else {
      await categoryService.criar({ name: form.name, telefone_extra: form.telefone_extra })
      mostrarToast('Categoria criada com sucesso!', 'success')
    }
    cancelarEdicao()
    await carregar()
  } catch (e) {
    mostrarToast(e.message, 'danger')
  } finally {
    salvando.value = false
  }
}

async function deletar(cat) {
  if (!confirm(`Excluir a categoria "${cat.name}"?\n\nIsso não será possível se houver chamados vinculados.`)) return
  try {
    await categoryService.deletar(cat.id)
    mostrarToast('Categoria excluída com sucesso!', 'success')
    await carregar()
  } catch (e) {
    mostrarToast(e.message, 'danger')
  }
}

onMounted(carregar)
</script>
