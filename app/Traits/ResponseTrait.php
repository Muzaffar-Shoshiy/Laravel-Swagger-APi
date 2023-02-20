<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait {
    /**
     * Summary of responseSuccess
     * @param mixed $data
     * @param mixed $message
     * @return JsonResponse
     */
    public function responseSuccess($data, $message = "Products fetched successfully."): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Summary of responseError
     * @param mixed $error
     * @param int $responseCode
     * @return JsonResponse
     */
    public function responseError($error, $message = "Data is invalid", int $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => $error
        ],$responseCode);
    }
}
