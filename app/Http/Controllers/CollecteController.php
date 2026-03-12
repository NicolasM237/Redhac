<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use Illuminate\Http\Request;
use App\Models\Nature;

class CollecteController extends Controller
{
    // Affiche toutes les collectes et les natures pour le select
    public function viewcollectes()
    {
        // Corrigé : relation 'nature' et non 'natures'
        $collectes = Collecte::with('nature')->get();
        $natures = Nature::all(); // Récupère toutes les natures pour le select

        return view('collectes', compact('collectes', 'natures'));
    }

    // Enregistrer une nouvelle collecte
    public function store(Request $request)
    {
        $request->validate([
            'nature_id' => 'required|exists:natures,id',
            'nom' => 'required|string|unique:collectes,nom',
            'quantite' => 'required|integer|min:0',
            'date_collecte' => 'required|date',
        ]);

        Collecte::create([
            'nature_id' => $request->nature_id,
            'nom' => $request->nom,
            'quantite' => $request->quantite,
            'date_collecte' => $request->date_collecte,
        ]);

        return redirect()->back()->with('success', 'Collecte enregistrée avec succès');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:collectes,id',
            'nature_id' => 'required|exists:natures,id',
            'nom' => 'required|string|unique:collectes,nom,' . $request->id,
            'quantite' => 'required|integer|min:0',
            'date_collecte' => 'required|date',
        ]);

        $collecte = Collecte::findOrFail($request->id);
        $collecte->nature_id = $request->nature_id;
        $collecte->nom = $request->nom;
        $collecte->quantite = $request->quantite;
        $collecte->date_collecte = $request->date_collecte;
        $collecte->save();

        return redirect()->back()->with('success', 'Collecte modifiée avec succès');
    }

    // Supprimer une collecte
    public function deleteCollecte($id)
    {
        $collecte = Collecte::findOrFail($id);
        $collecte->delete();

        return redirect()->back()->with('success', 'Collecte supprimée avec succès');
    }
}
