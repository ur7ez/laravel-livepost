<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralJsonException extends Exception
{
    protected $code = 422;
    /**
     * Report the exception
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception as an HTTP response
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request)
    {
        return new JsonResponse([
            'error' => [
                'message' => $this->getMessage()
            ],
        ], $this->code);
    }
}
