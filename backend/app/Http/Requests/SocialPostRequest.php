<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialPostRequest extends FormRequest
{
    /**
     * Solo accesible desde el panel (autenticado).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación de una publicación de redes sociales.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'in:instagram,facebook,tiktok'],
            'url' => ['required', 'url', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }
}
