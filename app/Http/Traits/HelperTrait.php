<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait HelperTrait
{
    public function responseJson($data = null, $message = 'Success', $code = 200): JsonResponse
    {
        $json = ['message' => $message];

        if ($data != null) {
            $json['data'] = $data;
        }

        return response()->json($json, $code);
    }

    public function responseJsonRaw($object, $code = 200): JsonResponse
    {
        return response()->json($object, $code);
    }
}
