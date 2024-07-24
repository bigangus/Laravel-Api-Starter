<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Mews\Captcha\Captcha;

class GeneralController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return (new static)->getMiddleware('general', ['captcha'], ['captcha']);
    }

    /**
     * @throws Exception
     */
    public function captcha(Captcha $captcha, string $config = 'default'): JsonResponse
    {
        return Response::success('Captcha retrieved successfully', [
            'captcha' => $captcha->create($config, true)
        ]);
    }
}
