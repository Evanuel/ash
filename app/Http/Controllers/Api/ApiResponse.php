<?php
// app/Http/Controllers/Api/ApiResponse.php

namespace App\Http\Controllers\Api;

trait ApiResponse
{
    /**
     * Retorno de sucesso padrão
     */
    protected function success($data = null, string $message = null, int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Retorno de erro padrão
     */
    protected function error(string $message = null, int $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Retorno de recurso criado
     */
    protected function created($data = null, string $message = 'Resource created successfully')
    {
        return $this->success($data, $message, 201);
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

    /**
     * Retorno para validação de dados
     */
    protected function validationError($errors, string $message = 'Validation failed')
    {
        return $this->error($message, 422, $errors);
    }
}