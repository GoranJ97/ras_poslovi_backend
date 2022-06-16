<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseService
{
    /**
     * @param array|null $data
     * @param null $count
     * @param null $message
     * @param int $status
     * @return JsonResponse
     */
    public static function sendList(?array $data = [], $count = null, $message = null, int $status = 200): JsonResponse
    {
        $responseArray = [];

        $responseArray['data'] = $data;

        if($count) {
            $responseArray['count'] = $count;
        }

        if($message) {
            $responseArray['message'] = $message;
        }

        return new JsonResponse($responseArray, $status);
    }

    /**
     * @param $item
     * @param null $message
     * @param int $status
     * @return JsonResponse
     */
    public static function sendItem($item = [], $message = null, int $status = 200): JsonResponse
    {
        $responseArray = [];

        if($item) {
            $responseArray['item'] = $item;
        }

        if($message) {
            $responseArray['message'] = $message;
        }

        return new JsonResponse($responseArray ? $responseArray : null, $status);
    }
}
