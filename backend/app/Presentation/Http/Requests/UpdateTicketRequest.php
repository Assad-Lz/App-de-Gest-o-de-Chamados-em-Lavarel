<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests;

use App\Domain\Ticket\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request para atualização de um Chamado existente.
 *
 * Permite alterar título, descrição, status e categoria.
 * O status deve ser um dos valores definidos na entidade de domínio.
 */
class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para atualização de chamado.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],

            // Status validado contra os valores permitidos no domínio
            'status' => [
                'required',
                'string',
                Rule::in(Ticket::getStatusValidos()),
            ],

            // category_id deve existir no banco (integridade referencial)
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    /**
     * Mensagens de erro personalizadas em português.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $statusValidos = implode(', ', Ticket::getStatusValidos());

        return [
            'title.required'       => 'O título do chamado é obrigatório.',
            'description.required' => 'A descrição do chamado é obrigatória.',
            'status.required'      => 'O status do chamado é obrigatório.',
            'status.in'            => "O status deve ser um dos seguintes: {$statusValidos}.",
            'category_id.required' => 'A categoria do chamado é obrigatória.',
            'category_id.exists'   => 'A categoria selecionada não existe.',
        ];
    }
}
