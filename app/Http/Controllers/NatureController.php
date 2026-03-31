<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use Faker\Guesser\Name;
use App\Models\Activite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NatureController extends Controller
{
   public function viewNatures(Request $request)
{
    $search = $request->input('search');

    $natures = Nature::when($search, function ($query, $search) {
            return $query->where('nom', 'LIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(10)
        ->appends(['search' => $search]); // Important pour garder le filtre de recherche

    return view('natures', compact('natures', 'search'));
}


    public function createNatures(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255']);

        $nature = Nature::create(['nom' => $request->nom]);

        // Enregistrement de l'activité
        $this->logActivity('création', 'natures', $nature->id, "A créé la nature : {$nature->nom}");

        return redirect()->back()->with('success', 'Nature de cas créé avec succès');
    }

    public function updateNature(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:natures,id',
            'nom' => 'required|string|max:255',
        ]);

        $nature = Nature::findOrFail($request->id);
        $nature->nom = $request->nom;
        $nature->save();

        $this->logActivity('modification', 'natures', $nature->id, "A modifié la nature en : {$nature->nom}");

        return redirect()->back()->with('success', 'Nature de cas modifiée avec succès');
    }

    public function deleteNature($id)
    {
        $nature = Nature::findOrFail($id);
        $nomNature = $nature->nom; // On garde le nom avant suppression pour le log
        $nature->delete();

        // Note : l'entity_id est conservé dans le log même si la ligne est supprimée
        $this->logActivity('suppression', 'natures', $id, "A supprimé la nature : {$nomNature}");

        return redirect()->back()->with('success', 'Nature de cas supprimé avec succès');
    }

    /**
     * Méthode centralisée pour loguer les actions
     */
    private function logActivity($type, $table, $id, $description)
    {
        Activite::create([
            'user_id'     => Auth::id(),
            'action_type' => $type,
            'table_name'  => $table,
            'entity_id'   => $id,
            'description' => $description,
        ]);
    }

    public function listApi(Request $request)
    {
        $user = Auth::user();
        $data = Nature::all();
        return response()->json($data);
    }
}
