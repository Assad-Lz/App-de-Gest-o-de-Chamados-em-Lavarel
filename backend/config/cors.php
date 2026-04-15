<?php

/*
 * -------------------------------------------------------
 * Config :: CORS – Cross-Origin Resource Sharing
 * -------------------------------------------------------
 * Configuração de CORS para permitir que o frontend Vue.js
 * (rodando em localhost:5173) acesse a API Laravel (localhost:8000).
 *
 * Em produção, substituir os valores para os domínios reais.
 */

return [
    /*
     * Rotas que permitem requisições cross-origin.
     * Aplica CORS a todas as rotas da API.
     */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
     * Métodos HTTP permitidos nas requisições cross-origin.
     */
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    /*
     * Origens permitidas. Em desenvolvimento: frontend Vue.js.
     * Em produção: substituir pelo domínio real do frontend.
     */
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
        'http://localhost:5174',
        'http://localhost:5175',
        'http://localhost:5176',
        'http://127.0.0.1:5173',
        'http://127.0.0.1:5174',
        'http://127.0.0.1:5175',
        'http://127.0.0.1:5176',
    ],

    'allowed_origins_patterns' => [],

    /*
     * Headers permitidos nas requisições.
     */
    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
        'Origin',
    ],

    /*
     * Headers expostos na resposta (acessíveis pelo JavaScript do cliente).
     */
    'exposed_headers' => [],

    /*
     * Tempo de cache do preflight OPTIONS em segundos.
     * 86400 = 24 horas.
     */
    'max_age' => 86400,

    /*
     * Permite envio de cookies/credentials nas requisições cross-origin.
     * Necessário para sessões e autenticação via Sanctum.
     */
    'supports_credentials' => true,
];
