<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Recibe un mensaje del formulario de contacto y lo envía por correo.
     */
    public function store(ContactRequest $request): JsonResponse
    {
        $data = $request->validated();

        $to = Setting::value('email', config('mail.from.address'));
        $siteName = Setting::value('site_name', config('app.name'));

        $body = "Nombre: {$data['name']}\n"
            ."Email: {$data['email']}\n"
            .(isset($data['phone']) && $data['phone'] !== '' ? "Teléfono: {$data['phone']}\n" : '')
            ."\nMensaje:\n{$data['message']}";

        Mail::raw($body, function ($message) use ($to, $siteName, $data) {
            $message->to($to)
                ->subject("$siteName — Nuevo mensaje de contacto")
                ->replyTo($data['email'], $data['name']);
        });

        return response()->json([
            'message' => 'Mensaje enviado correctamente.',
        ]);
    }
}
