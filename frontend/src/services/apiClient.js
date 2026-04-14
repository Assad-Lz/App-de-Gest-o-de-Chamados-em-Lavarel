/**
 * Camada de serviço para comunicação com a API do backend.
 *
 * REGRA ARQUITETURAL: Nenhuma requisição HTTP deve ser feita
 * diretamente nos componentes Vue.js. Toda comunicação com
 * a API passa por esta camada de serviço.
 *
 * Isso garante:
 *   - Centralização de configurações (headers, baseURL)
 *   - Facilidade de mock para testes
 *   - Interceptors de segurança e tratamento de erros
 *   - Middleware de ações (logging, auth token, etc.)
 */
import axios from 'axios'

/**
 * Instância configurada do Axios com configurações globais.
 *
 * Todas as requisições passam por aqui antes de sair para o backend.
 */
const apiClient = axios.create({
  baseURL: '/api', // Proxy do Vite redireciona para http://localhost:8000/api
  timeout: 15000,  // 15 segundos de timeout
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    // Header de identificação para middlewares do backend
    'X-Requested-With': 'XMLHttpRequest',
  },
})

/**
 * Interceptor de requisição – processa toda requisição antes de enviar.
 *
 * Funções middleware do frontend:
 *   1. Adiciona headers de segurança
 *   2. Sanitiza dados antes de enviar (defesa em profundidade)
 *   3. Pode adicionar tokens de autenticação futuramente
 */
apiClient.interceptors.request.use(
  (config) => {
    // Sanitiza os dados da requisição contra XSS antes de enviar
    // (defesa em profundidade – o backend também sanitiza)
    if (config.data && typeof config.data === 'object') {
      config.data = sanitizarObjeto(config.data)
    }

    return config
  },
  (error) => Promise.reject(error)
)

/**
 * Interceptor de resposta – processa todas as respostas recebidas.
 *
 * Trata erros de forma centralizada:
 *   - 422: Erros de validação do backend
 *   - 404: Recurso não encontrado
 *   - 500: Erro interno do servidor
 */
apiClient.interceptors.response.use(
  (response) => response, // Resposta bem-sucedida: passa direto
  (error) => {
    const mensagem = error.response?.data?.message ?? 'Erro inesperado na comunicação com o servidor.'

    // Propaga o erro com mensagem tratada para os componentes
    return Promise.reject(new Error(mensagem))
  }
)

/**
 * Remove tags HTML de strings para prevenir XSS no frontend.
 * Defesa em profundidade (o backend já sanitiza também).
 *
 * @param {Object} obj - Objeto com dados a serem sanitizados
 * @returns {Object} Objeto com strings sanitizadas
 */
function sanitizarObjeto(obj) {
  if (typeof obj !== 'object' || obj === null) return obj

  return Object.fromEntries(
    Object.entries(obj).map(([chave, valor]) => [
      chave,
      typeof valor === 'string'
        ? valor.replace(/<[^>]*>/g, '') // Remove tags HTML
        : valor,
    ])
  )
}

export default apiClient
