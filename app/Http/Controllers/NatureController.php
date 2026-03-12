<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use Faker\Guesser\Name;
use Illuminate\Http\Request;

class NatureController extends Controller
{
    public function viewNatures(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            // Recherche par nom partiel
            $natures = Nature::where('nom', 'LIKE', "%{$search}%")->get();
        } else {
            $natures = Nature::all();
        }

        return view('natures', compact('natures', 'search'));
    }

    public function createNatures(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        Nature::create([
            'nom' => $request->nom,
        ]);

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

        return redirect()->back()->with('success', 'Nature de cas modifiée avec succès');
    }

    public function deleteNature($id)
    {
        $natures = Nature::findOrFail($id);

        $natures->delete();
        return redirect()->back()->with('success', 'Nature de cas supprimé avec succès');
    }
}
