<?php

namespace App\Models\System;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;

class Dictionary extends BaseModel
{
    public static function cache(string $key): mixed
    {
        return Cache::remember('dict_' . $key, now()->addDay(), function () use ($key) {
            return self::where('key', $key)->first()->value;
        });
    }

    public static function cacheForget(string $key): void
    {
        Cache::forget('dict_' . $key);
    }

    public static function cacheFlush(): void
    {
        Cache::tags('dict')->flush();
    }
}
