<?php

namespace App\Providers;

use Illuminate\Validation\Factory;
use Mews\Captcha\CaptchaServiceProvider as ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish configuration files
        $this->publishes([
            __DIR__ . '/../config/captcha.php' => config_path('captcha.php')
        ], 'config');

        /* @var Factory $validator */
        $validator = $this->app['validator'];

        // Validator extensions
        $validator->extend('captcha', function ($attribute, $value, $parameters) {
            return config('captcha.disable') || ($value && captcha_check($value));
        });

        // Validator extensions
        $validator->extend('captcha_api', function ($attribute, $value, $parameters) {
            return config('captcha.disable') || ($value && captcha_api_check(
                $value,
                $parameters[0],
                $parameters[1] ?? 'default'
            ));
        });
    }
}
