<?php
// app/Exceptions/ApiException.php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $data;
    protected $statusCode;

    public function __construct(string $message = '', int $statusCode = 400, $data = null)
    {
        parent::__construct($message);
        
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'data' => $this->data,
            'trace' => config('app.debug') ? $this->getTrace() : null,
        ], $this->statusCode);
    }
}