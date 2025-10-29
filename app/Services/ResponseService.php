<?php

namespace App\Services;

class ResponseService
{
    public function success(int $code, string $message, $data = [], $extra = [])
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'error'   => null,
            'errors'  => null,
            'extra'   => $extra
        ], $code);
    }

    public function error(int $code, string $message, $errors = [], $extra = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'error'   => $message,
            'errors'  => $errors,
            'extra'   => $extra
        ], $code);
    }
}
