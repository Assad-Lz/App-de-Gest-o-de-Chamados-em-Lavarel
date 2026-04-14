/**
 * Serviço de Categorias – camada de abstração da API.
 *
 * Encapsula toda comunicação com os endpoints /api/categories.
 * Os componentes Vue.js usam este serviço em vez de chamar
 * o axios/apiClient diretamente (Clean Architecture no frontend).
 */
import apiClient from './apiClient'

/**
 * Serviço para operações CRUD de Categorias.
 *
 * Segue o princípio SRP: responsável apenas por comunicar
 * com o endpoint de categorias do backend.
 */
const categoryService = {
  /**
   * Busca todas as categorias cadastradas.
   *
   * @returns {Promise<Category[]>} Lista de categorias
   */
  async listar() {
    const response = await apiClient.get('/categories')
    return response.data.data
  },

  /**
   * Cria uma nova categoria.
   *
   * @param {Object} dados - { name: string }
   * @returns {Promise<Category>} Categoria criada
   */
  async criar(dados) {
    const response = await apiClient.post('/categories', dados)
    return response.data.data
  },

  /**
   * Atualiza uma categoria existente.
   *
   * @param {number} id   - ID da categoria
   * @param {Object} dados - { name: string }
   * @returns {Promise<Category>} Categoria atualizada
   */
  async atualizar(id, dados) {
    const response = await apiClient.put(`/categories/${id}`, dados)
    return response.data.data
  },

  /**
   * Remove uma categoria pelo ID.
   * Pode falhar se houver tickets associados (regra de negócio).
   *
   * @param {number} id - ID da categoria
   * @returns {Promise<void>}
   */
  async deletar(id) {
    await apiClient.delete(`/categories/${id}`)
  },
}

export default categoryService
