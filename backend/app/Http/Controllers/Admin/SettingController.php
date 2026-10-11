<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Claves de configuración editables desde el panel.
     */
    private const EDITABLE_KEYS = [
        'site_name',
        'lawyer_name',
        'lawyer_title',
        'tagline',
        'about',
        'phone',
        'whatsapp',
        'email',
        'address',
        'schedule',
        'instagram',
        'facebook',
        'tiktok',
        'photo_url',
        'hero_image_url',
    ];

    /**
     * Devuelve toda la configuración (para el formulario del panel).
     */
    public function index(): JsonResponse
    {
        return response()->json(['data' => Setting::allAsArray()]);
    }

    /**
     * Actualiza las claves de configuración recibidas.
     */
    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        foreach (self::EDITABLE_KEYS as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }

        return response()->json(['data' => Setting::allAsArray()]);
    }
}
