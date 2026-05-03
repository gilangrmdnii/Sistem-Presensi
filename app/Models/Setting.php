<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:$key", function () use ($key, $default) {
            return self::find($key)?->value ?? $default;
        });
    }

    public static function put(string $key, mixed $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:$key");
    }

    public static function flush(): void
    {
        foreach (self::all() as $row) {
            Cache::forget("setting:{$row->key}");
        }
    }
}
