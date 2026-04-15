<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests;

use App\Domain\Ticket\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request para criação de um novo Chamado (Ticket).
 *
 * Valida todos os campos obrigatórios e garante que
 * a categoria_id fornecida existe no banco de dados.
 */
class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação de chamado.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Título: obrigatório, string, máximo 255 caracteres
            'title'       => ['required', 'string', 'max:255'],

            // Descrição: obrigatória, string, sem limite fixo (mas razoável)
            'description' => ['required', 'string', 'max:5000'],

            // category_id: deve existir na tabela categories (integridade referencial)
            'category_id' => ['required', 'integer', 'exists:categories,id'],

            // created_by: string
            'created_by'  => ['nullable', 'string', 'max:255'],

            'user_name'   => ['required', 'string', 'max:255'],
            'user_email'  => ['required', 'email', 'max:255'],
            'department'  => ['required', 'string', 'max:255'],

            // Campos honeypot: devem estar ausentes/vazios
            'website'        => ['prohibited'],
            'telefone_extra' => ['prohibited'],
        ];
    }

    /**
     * Mensagens de erro personalizadas em português.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'       => 'O título do chamado é obrigatório.',
            'title.max'            => 'O título não pode ter mais de 255 caracteres.',
            'description.required' => 'A descrição do chamado é obrigatória.',
            'category_id.required' => 'A categoria do chamado é obrigatória.',
            'category_id.exists'   => 'A categoria selecionada não existe.',
            'category_id.integer'  => 'O ID da categoria deve ser um número inteiro.',
        ];
    }
}
