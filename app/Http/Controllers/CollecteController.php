<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\Nature;
use App\Models\Activite; // Import du modèle Activite
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollecteController extends Controller
{
    /**
     * Méthode interne pour centraliser l'enregistrement des activités
     */
    private function logAction($type, $id, $description)
    {
        Activite::create([
            'user_id' => Auth::id(),
            'action_type' => $type,
            'table_name' => 'collectes',
            'entity_id' => $id,
            'description' => $description,
        ]);
    }

    // Affiche toutes les collectes et les natures pour le select
    public function viewcollectes(Request $request)
{
    $search = $request->input('search');
    $natures = Nature::all(); 

    $collectes = Collecte::with('nature')
        ->when($search, function ($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhereHas('nature', function($n) use ($search) {
                      $n->where('nom', 'like', "%$search%");
                  });
            });
        })
        ->latest() // Trie par date de création décroissante
        ->paginate(5); // Réduit à 15 pour une meilleure performance

    // On préserve la recherche dans les liens
    $collectes->appends(['search' => $search]);

    return view('collectes', compact('collectes', 'natures', 'search'));
}

    // Enregistrer une nouvelle collecte
    public function store(Request $request)
    {
        $request->validate([
            'nature_id' => 'required|exists:natures,id',
            'nom' => 'required|string|unique:collectes,nom',
            //'quantite' => 'required|integer|min:0',
            'date_collecte' => 'required|date',
        ]);

        $collecte = Collecte::create([
            'nature_id' => $request->nature_id,
            'nom' => $request->nom,
            // 'quantite' => $request->quantite,
            'date_collecte' => $request->date_collecte,
        ]);

        // LOG DE L'ACTION
        $this->logAction('création', $collecte->id, "A créé la collecte : {$collecte->nom}");

        return redirect()->back()->with('success', 'Mode de collecte enregistré avec succès');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:collectes,id',
            'nature_id' => 'required|exists:natures,id',
            'nom' => 'required|string|unique:collectes,nom,' . $request->id,
            //'quantite' => 'required|integer|min:0',
            'date_collecte' => 'required|date',
        ]);

        $collecte = Collecte::findOrFail($request->id);
        $collecte->nature_id = $request->nature_id;
        $collecte->nom = $request->nom;
        //$collecte->quantite = $request->quantite;
        $collecte->date_collecte = $request->date_collecte;
        $collecte->save();

        // LOG DE L'ACTION
        $this->logAction('modification', $collecte->id, "A modifié la collecte : {$collecte->nom}");

        return redirect()->back()->with('success', 'Mode de collecte modifié avec succès');
    }

    // Supprimer une collecte
    public function deleteCollecte($id)
    {
        $collecte = Collecte::findOrFail($id);
        $nom = $collecte->nom; // On garde le nom avant la suppression
        $collecte->delete();

        // LOG DE L'ACTION
        $this->logAction('suppression', $id, "A supprimé la collecte : {$nom}");

        return redirect()->back()->with('success', 'Mode de collecte supprimé avec succès');
    }

    public function listApi(Request $request){
        $user = Auth::user();
        $data = Collecte::all();
        return response()->json($data); 
    }
}