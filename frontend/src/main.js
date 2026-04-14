/**
 * Ponto de entrada da aplicação Vue.js.
 *
 * Configura e monta o aplicativo com:
 *   - Vue Router para navegação SPA
 *   - Pinia para gerenciamento de estado
 */
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

// Cria a instância da aplicação Vue.js
const app = createApp(App)

// Registra o gerenciador de estado Pinia
app.use(createPinia())

// Registra o roteador para navegação SPA
app.use(router)

// Monta a aplicação no elemento #app do HTML
app.mount('#app')
