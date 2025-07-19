<?php

if (!function_exists('httpStatusCodeError')) {
    function httpStatusCodeError(int $statusCode = 0)
    {
        $statusCodeErrors = [
            400, 401, 403, 404, 405, 406, 407, 408, 409,
            410, 415, 422, 424, 429,
            500, 501, 502, 503, 504, 505
        ];

        return in_array($statusCode, $statusCodeErrors) ? $statusCode : 500;
    }
}
