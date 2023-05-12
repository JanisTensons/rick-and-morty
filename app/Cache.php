<?php declare(strict_types=1);

namespace App;

use Carbon\Carbon;

class Cache
{
    public static function remember(string $key, string $content, int $ttl = 60): void
    {
        $cacheFile = 'cache/' . $key;

        file_put_contents($cacheFile, json_encode([
            'expires_at' => Carbon::now()->addSeconds($ttl),
            'content' => $content
        ]));
    }

    public static function forget(string $key): void
    {
        unlink('cache/' . $key);
    }

    public static function get(string $key): ?string
    {
        if (!self::has($key)) {
            return null;
        }
        $content = json_decode(file_get_contents('cache/' . $key));

        return $content->content;
    }

    public static function has(string $key): bool
    {
        if (!file_exists('cache/' . $key)) {
            return false;
        }
        $content = json_decode(file_get_contents('cache/' . $key));

        if (Carbon::now()->gt($content->expires_at)) {
            return false;
        }
        return true;
    }
}