<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\Middleware;

abstract class Controller
{
    abstract public static function middleware();

    /**
     * @param string $aclName
     * @param array $authExceptRoutes
     * @param array $aclExceptRoutes
     * @return Middleware[]
     */
    protected function getMiddleware(string $aclName, array $authExceptRoutes = [], array $aclExceptRoutes = [])
    {
        $methods = get_class_methods(static::class);

        $aclRoutes = array_values(array_diff($methods, array_merge(['__construct', 'middleware'], $aclExceptRoutes)));

        $middlewares = [
            new Middleware('auth:sanctum', except: $authExceptRoutes)
        ];

        foreach ($aclRoutes as $route) {
            $middlewares[] = new Middleware('acl:' . $aclName . '.' . $route, only: [$route]);
        }

        return $middlewares;
    }
}
