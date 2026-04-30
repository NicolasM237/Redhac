<?php

namespace App\Http\Controllers;

use App\Models\Historique; // Utilisation de ton modèle spécifique
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{


   public function viewhistorique(Request $request)
    {
        $search = $request->input('search');

        $historiques = Historique::with('user')
            ->when($search, function ($query, $search) {
                // On cherche l'utilisateur associé par nom, prenom ou email
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('nom', 'like', "%$search%")
                        ->orWhere('prenom', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(5)
            ->appends(['search' => $search]); // Important pour garder la recherche lors du changement de page

        return view('historiques', compact('historiques', 'search'));
    }
}
