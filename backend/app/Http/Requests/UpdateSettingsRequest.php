<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Solo accesible desde el panel (autenticado).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación de la configuración del sitio.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'site_name' => ['nullable', 'string', 'max:255'],
            'lawyer_name' => ['nullable', 'string', 'max:255'],
            'lawyer_title' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'photo_url' => ['nullable', 'url', 'max:1000'],
            'hero_image_url' => ['nullable', 'url', 'max:1000'],
        ];
    }
}
