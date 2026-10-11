<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    /**
     * Devuelve el valor de una configuración por su clave.
     */
    public static function value(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Devuelve todas las configuraciones como clave => valor.
     * Las claves que se almacenan como JSON se decodifican a su tipo original.
     */
    public static function allAsArray(): array
    {
        $settings = static::query()->pluck('value', 'key')->toArray();

        // Los servicios se guardan como JSON y se exponen como array.
        if (isset($settings['services'])) {
            $settings['services'] = json_decode($settings['services'], true) ?: [];
        }

        return $settings;
    }
}
