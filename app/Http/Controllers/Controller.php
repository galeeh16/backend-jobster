<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function successResponse(mixed $data = null, string $message='Success', int $status = 200): JsonResponse 
    {
        return response()->json(['message' => $message, 'data' => $data], $status);
    }

    protected function errorResponse(mixed $errors = null, string $message='Whoops, something went wrong', int $status = 500): JsonResponse 
    {
        return response()->json(['message' => $message, 'errors' => $errors], $status);
    }

    protected function formatError(\Exception $e): array 
    {
        return [
            'message' => 'Whoops, something went wrong',
            'desc' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ];
    }
}
