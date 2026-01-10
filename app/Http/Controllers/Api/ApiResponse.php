<?php
// app/Http/Controllers/Api/ApiResponse.php

namespace App\Http\Controllers\Api;

trait ApiResponse
{
    /**
     * Retorno de sucesso padrão
     */
    protected function success($data = null, string $message = null, $meta = null, int $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Retorno de erro padrão
     */
    protected function error(string $message = "", int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Retorno de recurso criado
     */
    protected function created($data = null, string $message = 'Resource created successfully')
    {
        return $this->success($data, $message, null, 201);
    }

    /**
     * Retorno de recurso não encontrado
     */
    protected function notFound(string $message = 'Resource not found')
    {
        return $this->error($message, 404);
    }

    /**
     * Retorno de acesso não autorizado
     */
    protected function unauthorized(string $message = 'Unauthorized access')
    {
        return $this->error($message, 401);
    }
}