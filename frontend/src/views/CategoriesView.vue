<template>
  <div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-cellar-navy tracking-tight uppercase">Categorias</h1>
        <p class="text-cellar-gray mt-1 font-medium">Sistemas de classificação para as filas de atendimento.</p>
      </div>
      <button 
        @click="showCreateModal = true"
        class="bg-cellar-navy hover:bg-cellar-navyMid text-white px-6 py-3 rounded-pill font-bold shadow-md transition-all hover:scale-105 cellar-btn uppercase tracking-widest text-sm"
      >
        Nova Categoria
      </button>
    </div>

    <div v-if="loading" class="p-12 text-center text-cellar-gray font-bold">
      <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-cellar-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
      Sincronizando divisões...
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <div 
        v-for="cat in categories" 
        :key="cat.id"
        class="bg-white rounded-round shadow-sm border border-slate-100 p-8 hover:shadow-lg transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1"
      >
        <div>
          <div class="flex justify-between items-start mb-6">
            <span class="inline-flex p-4 rounded-xl bg-cellar-navy/10 text-cellar-navy group-hover:scale-110 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </span>
            <button 
              v-if="cat.tickets_count === 0"
              @click="deleteCategory(cat.id)"
              class="text-slate-300 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100 bg-red-50 p-2 rounded-lg"
              title="Excluir categoria vazia"
            >
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </button>
            <span v-else class="text-[10px] font-black text-slate-300 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded" title="Não é possível excluir categorias com chamados">Bloqueada</span>
          </div>
          <h3 class="text-xl font-bold text-cellar-navy group-hover:text-cellar-orange transition-colors">{{ cat.name }}</h3>
          <p class="text-sm text-cellar-gray mt-3 leading-relaxed font-medium">Responsável pelo gerenciamento de fluxos relacionados a {{ cat.name.toLowerCase() }}.</p>
        </div>
        <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-between">
          <span class="text-xs font-bold text-cellar-gray uppercase tracking-wider">Chamados vinculados: {{ cat.tickets_count }}</span>
          <span class="px-3 py-1 rounded-pill text-xs font-bold bg-emerald-50 text-emerald-600 uppercase tracking-widest border border-emerald-100">Ativa</span>
        </div>
      </div>

    </div>

    <!-- Modal Criar -->
    <Transition name="modal">
      <div v-if="showCreateModal" class="fixed inset-0 bg-cellar-navy/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-round w-full max-w-md shadow-2xl p-8 relative">
           <button @click="showCreateModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-cellar-dark bg-slate-100 hover:bg-slate-200 p-2 rounded-full transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>

          <h3 class="text-2xl font-black text-cellar-navy mb-6">Nova Categoria</h3>
          <form @submit.prevent="createCategory" class="space-y-4">
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-2">Nome da Categoria</label>
              <input 
                v-model="newName" 
                required 
                type="text" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-cellar-orange/10 focus:border-cellar-orange outline-none transition-all font-medium"
                placeholder="Ex: Redes, Telefonia, E-mail..."
              >
            </div>
            <button 
              type="submit" 
              :disabled="submitting"
              class="w-full py-4 bg-cellar-orange text-white rounded-xl font-black shadow-lg hover:shadow-xl transition-all disabled:opacity-50"
            >
              {{ submitting ? 'Criando...' : 'Salvar Categoria' }}
            </button>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const toast = useToast()
const userRole = ref(localStorage.getItem('userRole'))
const categories = ref([])
const loading = ref(true)
const showCreateModal = ref(false)
const submitting = ref(false)
const newName = ref('')

const API_URL = 'http://localhost:8000/api/v1/categories'

const fetchCategories = async () => {
  loading.value = true
  try {
    const { data } = await axios.get(API_URL)
    categories.value = data.data
  } catch {
    toast.error('Erro ao carregar categorias.')
  } finally {
    loading.value = false
  }
}

const createCategory = async () => {
  submitting.value = true
  try {
    await axios.post(API_URL, { name: newName.value })
    toast.success('Categoria criada com sucesso!')
    newName.value = ''
    showCreateModal.value = false
    await fetchCategories()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Erro ao criar categoria.')
  } finally {
    submitting.value = false
  }
}

const deleteCategory = async (id) => {
  if (!confirm('Tem certeza que deseja remover esta categoria?')) return
  
  try {
    await axios.delete(`${API_URL}/${id}`)
    toast.success('Categoria removida!')
    await fetchCategories()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Não foi possível remover.')
  }
}

onMounted(fetchCategories)
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.9); }
</style>
