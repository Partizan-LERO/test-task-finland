<?php

namespace Framework\Http\Response;

class JsonResponse
{
    /**
     * @param  array  $data
     * @return string
     */
    public static function send(array $data): string
    {
        header_remove();
        header('Content-type: application/json');
        return json_encode($data);
    }

}
