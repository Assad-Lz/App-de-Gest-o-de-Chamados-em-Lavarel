import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

/**
 * Configuração do Vite para o frontend Vue.js.
 * O proxy redireciona chamadas /api para o backend Laravel.
 */
export default defineConfig({
  plugins: [vue()],

  resolve: {
    alias: {
      '@': resolve(__dirname, './src'),
    },
  },

  server: {
    port: 5173,

    // Proxy para evitar CORS em desenvolvimento: /api → Laravel
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: false,
      },
    },
  },
})
