<?php

namespace App\Models\System;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;

class Config extends BaseModel
{
    public static function cache(string $key): mixed
    {
        return Cache::remember('config_' . $key, now()->addDay(), function () use ($key) {
            return self::where('key', $key)->first()->value;
        });
    }

    public static function cacheFlush(): void
    {
        Cache::tags('config')->flush();
    }

    public static function cacheForget(string $key): void
    {
        Cache::forget('config_' . $key);
    }
}
