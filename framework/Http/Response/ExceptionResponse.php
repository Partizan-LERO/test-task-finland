<?php

namespace Framework\Http\Response;

class ExceptionResponse
{
    public static function send(string $message): void
    {
        header_remove();
        http_response_code(500);
        header('Content-type: application/json');
        echo json_encode(['error' => $message]);
    }

}
