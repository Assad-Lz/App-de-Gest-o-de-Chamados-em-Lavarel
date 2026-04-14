/**
 * Serviço de Tickets – camada de abstração da API.
 *
 * Encapsula toda comunicação com os endpoints /api/tickets.
 * Suporta filtros por status e categoria para a listagem.
 */
import apiClient from './apiClient'

/**
 * Nomes legíveis para os status dos tickets.
 * Usado para exibição na interface.
 */
export const STATUS_LABELS = {
  aberto: 'Aberto',
  em_progresso: 'Em Progresso',
  resolvido: 'Resolvido',
}

/**
 * Classes CSS Bootstrap para os badges de status.
 */
export const STATUS_BADGES = {
  aberto: 'badge bg-warning text-dark',
  em_progresso: 'badge bg-primary',
  resolvido: 'badge bg-success',
}

/**
 * Serviço para operações CRUD de Tickets (Chamados).
 */
const ticketService = {
  /**
   * Busca todos os tickets com filtros opcionais.
   *
   * @param {Object} filtros - { status?: string, category_id?: number }
   * @returns {Promise<Ticket[]>} Lista de tickets filtrados
   */
  async listar(filtros = {}) {
    // Remove filtros vazios antes de enviar
    const params = Object.fromEntries(
      Object.entries(filtros).filter(([, valor]) => valor !== '' && valor !== null)
    )

    const response = await apiClient.get('/tickets', { params })
    return response.data.data
  },

  /**
   * Cria um novo chamado.
   *
   * @param {Object} dados - { title, description, category_id }
   * @returns {Promise<Ticket>} Ticket criado (status sempre "aberto")
   */
  async criar(dados) {
    const response = await apiClient.post('/tickets', dados)
    return response.data.data
  },

  /**
   * Atualiza um chamado existente.
   *
   * @param {number} id   - ID do chamado
   * @param {Object} dados - { title, description, status, category_id }
   * @returns {Promise<Ticket>} Ticket atualizado
   */
  async atualizar(id, dados) {
    const response = await apiClient.put(`/tickets/${id}`, dados)
    return response.data.data
  },

  /**
   * Remove um chamado pelo ID.
   *
   * @param {number} id - ID do chamado
   * @returns {Promise<void>}
   */
  async deletar(id) {
    await apiClient.delete(`/tickets/${id}`)
  },
}

export default ticketService
