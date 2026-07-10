<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $page): View
    {
        $pages = [
            'sobre-nosotros' => 'Sobre nosotros',
            'contacto' => 'Contacto',
            'politicas-de-envio' => 'Políticas de envío',
            'politicas-de-devolucion' => 'Políticas de devolución',
            'terminos-y-condiciones' => 'Términos y condiciones',
            'politica-de-privacidad' => 'Política de privacidad',
        ];

        abort_unless(isset($pages[$page]), 404);

        return view('store.pages.basic', [
            'title' => $pages[$page],
            'slug' => $page,
            'seo' => [
                'title' => $pages[$page],
                'description' => $pages[$page].' de Qué Económico.',
                'canonical' => url($page),
            ],
        ]);
    }
}
