<?php

/*
 * -------------------------------------------------------
 * Presentation :: Form Request – Criar Categoria
 * -------------------------------------------------------
 * Responsável por validar os dados da requisição de criação
 * de categoria ANTES de chegar ao Controller.
 *
 * Form Requests são a camada de validação do Laravel, seguindo
 * o princípio SRP: validação separada do controller.
 */

declare(strict_types=1);

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para criação de uma nova Categoria.
 *
 * Valida e sanitiza os dados antes de chegarem ao Controller.
 * O Laravel automaticamente rejeita a requisição com 422
 * se a validação falhar.
 */
class StoreCategoryRequest extends FormRequest
{
    /**
     * Define se o usuário está autorizado a fazer esta requisição.
     * Em uma API pública sem autenticação, sempre retorna true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação para os campos da requisição.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Nome: obrigatório, string, máximo 255 caracteres, único na tabela
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],

            // Campos honeypot: devem ser VAZIOS (preenchidos = bot detectado)
            'website'       => ['prohibited'],
            'telefone_extra' => ['prohibited'],
            'endereco_bot'  => ['prohibited'],
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
            'name.required' => 'O campo nome é obrigatório.',
            'name.max'      => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique'   => 'Já existe uma categoria com este nome.',
        ];
    }
}
