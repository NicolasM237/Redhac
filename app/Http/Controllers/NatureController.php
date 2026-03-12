<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use Faker\Guesser\Name;
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
            ->latest() // Les plus récentes en premier
            ->paginate(10); // Pagination pour éviter de charger trop de données d'un coup

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

    public function listApi(Request $request)
    {
        $user = Auth::user();
        $data = Nature::all();
        return response()->json($data);
    }
}
