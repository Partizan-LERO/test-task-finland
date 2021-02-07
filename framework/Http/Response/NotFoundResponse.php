<?php

namespace Framework\Http\Response;

class NotFoundResponse
{

    public static function send(): void
    {
        header_remove();
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

}
