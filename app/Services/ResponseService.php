<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function successPaginated(LengthAwarePaginator $paginator, string $message = 'Retrieved successfully', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'error' => null,
            'errors' => null,
            'extra' => [
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ]
            ]
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
