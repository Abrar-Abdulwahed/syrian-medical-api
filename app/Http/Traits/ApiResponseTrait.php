<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{

    public function returnAllDataJSON(
        $data      = [],
        $meta      = [],
        $links     = [],
        $message   = '',
        $status    = 'success',
        $code      = '200',
    ) {
        return response()->json([
            'data'       => $data,
            'meta'       => $meta,
            'links'      => $links,
            'code'       => $code,
            'status'     => $status,
            'message'    => $message,
        ], $code);
    }

    public function returnJSON(
        $data    = [],
        $message = '',
        $status  = 'success',
        $code    = '200',
    ) {
        return response()->json([
            'code'    => $code,
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public function returnSuccess(
        $message = 'Your request done successfully'
    ) {
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => $message,
        ], 200);
    }

    public function returnWrong(
        $message = 'Your Request Is Invalid',
        $code    = JsonResponse::HTTP_BAD_REQUEST,
        $errors  = [],
    ) {
        if ($errors === []) {
            return response()->json([
                'code'    => (string) $code,
                'status'  => 'failed',
                'message' => $message,
            ], $code);
        } else {

            return response()->json([
                'code'    => (string) $code,
                'status'  => 'failed',
                'message' => $message,
                'errors'  => $errors,
            ], $code);
        }
    }
}