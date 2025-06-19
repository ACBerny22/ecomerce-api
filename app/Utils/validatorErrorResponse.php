<?php

namespace App\Http\Utils;

class Utils
{
    public function validationErrorResponse($validation)
    {
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validation->errors()
            ], 400);
        }
    }
}
