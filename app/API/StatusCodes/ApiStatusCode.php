<?php

namespace App\API\StatusCodes;

use Illuminate\Http\Response;

/**
 * Class ApiStatusCode
 * @package App\API\StatusCodes
 */
class ApiStatusCode extends StatusCode {

    /**
     * @param string $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondCreated($message = 'Object Created.') {
        return StatusCode::respondWithSuccess($message, Response::HTTP_CREATED);
    }

}