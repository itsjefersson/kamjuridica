<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SocialPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kamjuridica.com'],
            [
                'name' => 'Administradora',
                'password' => Hash::make('password'),
            ],
        );

        $settings = [
            'site_name' => 'KAM Jurídica',
            'lawyer_name' => 'KAM Jurídica',
            'lawyer_title' => 'Abogada',
            'photo_url' => 'https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt='.rawurlencode('Professional portrait of a confident female lawyer in an elegant navy blue suit, standing in a modern law office with bookshelves, warm professional lighting, photorealistic').'&image_size=portrait_4_3',
            'hero_image_url' => 'https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt='.rawurlencode('Wide cinematic view of an elegant modern law office, large wooden desk with brass scales of justice, floor-to-ceiling shelves of leather-bound law books, warm golden lighting, deep navy blue and gold tones, photorealistic').'&image_size=landscape_16_9',
            'tagline' => 'Asesoría jurídica confiable, cercana y a tu medida',
            'about' => 'Abogada con experiencia en asesoría y representación jurídica, comprometida con brindar soluciones claras, oportunas y efectivas a cada cliente.',
            'phone' => '+57 300 000 0000',
            'whatsapp' => '573000000000',
            'email' => 'contacto@kamjuridica.com',
            'address' => 'Bogotá, Colombia',
            'schedule' => 'Lunes a viernes · 8:00 a. m. – 6:00 p. m.',
            'instagram' => 'https://instagram.com/kamjuridica',
            'facebook' => 'https://facebook.com/kamjuridica',
            'tiktok' => 'https://tiktok.com/@kamjuridica',
            'services' => json_encode([
                [
                    'title' => 'Derecho Laboral',
                    'description' => 'Despidos, liquidaciones, acoso laboral y reclamaciones ante el empleador.',
                    'icon' => 'briefcase',
                ],
                [
                    'title' => 'Derecho de Familia',
                    'description' => 'Divorcios, custodias, alimentos y sucesiones con acompañamiento cercano.',
                    'icon' => 'family',
                ],
                [
                    'title' => 'Derecho Civil y Contratos',
                    'description' => 'Redacción, revisión y ejecución de contratos y obligaciones civiles.',
                    'icon' => 'contract',
                ],
                [
                    'title' => 'Derecho Penal',
                    'description' => 'Defensa y representación en procesos penales con total confidencialidad.',
                    'icon' => 'shield',
                ],
                [
                    'title' => 'Derecho Comercial',
                    'description' => 'Constitución de empresas y asesoría a negocios y emprendimientos.',
                    'icon' => 'chart',
                ],
                [
                    'title' => 'Seguridad Social y Pensiones',
                    'description' => 'Trámites de pensiones, salud y riesgos laborales.',
                    'icon' => 'health',
                ],
            ]),
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $socialPosts = [
            [
                'platform' => 'instagram',
                'url' => 'https://instagram.com/kamjuridica',
                'title' => 'Sígueme en Instagram',
                'active' => true,
            ],
            [
                'platform' => 'facebook',
                'url' => 'https://facebook.com/kamjuridica',
                'title' => 'Sígueme en Facebook',
                'active' => true,
            ],
            [
                'platform' => 'tiktok',
                'url' => 'https://tiktok.com/@kamjuridica',
                'title' => 'Mírame en TikTok',
                'active' => true,
            ],
        ];

        foreach ($socialPosts as $post) {
            SocialPost::updateOrCreate(
                ['platform' => $post['platform'], 'url' => $post['url']],
                $post,
            );
        }
    }
}
