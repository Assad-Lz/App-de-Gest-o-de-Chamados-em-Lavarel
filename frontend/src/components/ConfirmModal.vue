<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 bg-cellar-navy/60 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
      <div class="bg-white rounded-round w-full max-w-md shadow-2xl p-8 relative overflow-hidden">
        <!-- Decorador -->
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-red-50 rounded-full opacity-50"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center">
          <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          
          <h3 class="text-xl font-black text-cellar-navy uppercase tracking-wider mb-2">{{ title }}</h3>
          <p class="text-slate-500 font-medium leading-relaxed mb-8">{{ message }}</p>
          
          <div class="flex gap-3 w-full">
            <button 
              @click="$emit('cancel')" 
              class="flex-1 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all uppercase tracking-widest text-xs"
            >
              Cancelar
            </button>
            <button 
              @click="$emit('confirm')" 
              class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition-all uppercase tracking-widest text-xs"
            >
              Sim, Excluir
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  show: Boolean,
  title: { type: String, default: 'Confirmar Exclusão' },
  message: { type: String, default: 'Tem certeza que deseja remover este item? Esta ação não pode ser desfeita.' }
})
defineEmits(['confirm', 'cancel'])
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.95); }
</style>
