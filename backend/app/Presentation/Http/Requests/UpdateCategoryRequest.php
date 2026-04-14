<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para atualização de uma Categoria existente.
 */
class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para atualização.
     * O unique ignora o registro atual (pelo ID da rota).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Obtém o ID da rota para ignorar o unique do próprio registro
        $id = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255', "unique:categories,name,{$id}"],
        ];
    }

    /**
     * Mensagens de erro em português.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max'      => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique'   => 'Já existe uma categoria com este nome.',
        ];
    }
}
