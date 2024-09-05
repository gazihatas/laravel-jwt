<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function successResponse($data, $message = null, $code = 200) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse($message, $errors = [], $code = 400) : JsonResponse
    {

        if (!is_array($errors)) {
            $errors = [];
        }

        $errorStructure = !empty($errors) ? $errors : ['message' => [$message]];

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errorStructure,
        ], $code);
    }
}
