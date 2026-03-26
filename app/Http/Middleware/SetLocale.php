<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ✅ Liste des locales disponibles
        $availableLocales = ['fr', 'en'];

        // 1️⃣ Récupère la langue depuis la session, sinon celle du navigateur, sinon 'fr'
        $locale = Session::get('locale') 
                  ?? substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2) 
                  ?? 'fr';

        // 2️⃣ Vérifie si la langue est autorisée, sinon 'fr'
        if (!in_array($locale, $availableLocales)) {
            $locale = 'fr';
        }

        // 3️⃣ Définit la langue de l'application
        App::setLocale($locale);

        // 4️⃣ Continue la requête
        return $next($request);
    }
}