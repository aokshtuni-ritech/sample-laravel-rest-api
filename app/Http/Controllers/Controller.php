<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Controller
{
    //
    public function respond($data): JsonResponse
    {
        return response()->json($data);
    }

    public function respondErrorMessage(string $message)
    {
        return response()->json([
            'error' => true,
            'success' => false,
            'message' => $message
        ],Response::HTTP_BAD_REQUEST);
    }

    public function respondNotFound(string $message)
    {
        return response()->json([
            'error' => true,
            'success' => false,
            'message' => $message
        ],Response::HTTP_NOT_FOUND);
    }
}
