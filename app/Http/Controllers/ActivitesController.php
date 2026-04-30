<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivitesController extends Controller
{
    /**
     * Affiche uniquement les activités de l'utilisateur connecté
     */
    
    public function viewactivites(Request $request)
{
    $search = $request->input('search');

    $activites = Activite::where('user_id', Auth::id())
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('action_type', 'like', "%$search%")
                  ->orWhere('table_name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        })
        ->latest()
        ->paginate(15)
        ->withQueryString(); // Remplace appends pour plus de simplicité

    return view('activites', compact('activites', 'search'));
}

    /**
     * Supprime une activité spécifique (avec vérification de sécurité)
     */
    public function destroy($id)
    {
        // On s'assure que l'activité appartient bien à l'utilisateur connecté avant de supprimer
        $activite = Activite::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

        $activite->delete();

        return redirect()->back()->with('success', 'Activité supprimée avec succès.');
    }

    /**
     * Vide tout l'historique d'activité de l'utilisateur connecté
     */
    public function clearMyHistory()
    {
        Activite::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Votre historique d\'activités a été entièrement vidé.');
    }
}