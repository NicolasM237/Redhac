<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        // 1️⃣ Récupère la langue de la session (ou français par défaut)
        $locale = session('locale', 'fr');

        // 2️⃣ Définit la langue de l'application
        App::setLocale($locale);

        // 3️⃣ Continue la requête
        return $next($request);
    }
}