<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    
    protected function success($data = [], string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function error(string $message = 'Error', array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    protected function created($data = [], string $message = 'Created'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, [], 401);
    }

    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, [], 403);
    }

    protected function notFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->error($message, [], 404);
    }

    protected function unprocessable(array $errors = [], string $message = 'Validation Failed'): JsonResponse
    {
        return $this->error($message, $errors, 422);
    }

    protected function serverError(string $message = 'Server Error'): JsonResponse
    {
        return $this->error($message, [], 500);
    }
}
