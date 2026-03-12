<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nature;
use App\Models\Collecte;
use App\Models\Violences;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ViolencesController extends Controller
{
    public function viewviolences(Request $request)
    {
        $nationalites = [
            'Camerounaise',
            'Gabonaise',
            'Tchadienne',
            'Centrafricaine',
            'Congolaise',
            'Autre'
        ];

        $user = auth()->user();

        $query = Violences::with(['nature', 'collecte', 'user']);

        // Si ce n'est pas un administrateur, on ne montre que ses propres cas
        if ($user->profil !== 'Administrateur') {
            $query->where('user_id', $user->id);
        }

        // Filtrage par nationalité
        if ($request->filled('nationalite')) {
            $query->where('nationalite', $request->nationalite);
        }

        // Filtrage par code si fourni
        if ($request->filled('searchTerm')) {
            $query->where('code', 'like', '%' . $request->searchTerm . '%');
        }

        $violences = $query->get();

        return view('violences', [
            'violences' => $violences,
            'nationalites' => $nationalites
        ]);
    }

    public function addViolences()
    {
        $natures = Nature::all();
        $collectes = Collecte::all();

        $nationalites = [
            'Camerounaise',
            'Gabonaise',
            'Tchadienne',
            'Centrafricaine',
            'Congolaise',
            'Autre'
        ];

        return view('addviolences', [
            'natures' => $natures,
            'collectes' => $collectes,
            'nationalites' => $nationalites
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'contact' => 'required',
            'occupation' => 'required',
            'age' => 'required|integer',
            'sexe' => 'required',
            'nationalite' => 'required',
            'residence' => 'required',
            'datesurvenue' => 'required|date',
            'lieusurvenue' => 'required',
            'situation' => 'required',
            'auteurs' => 'required',
            'nature_id' => 'required',
            'collecte_id' => 'required',
            'description_cas' => 'required',
            'mesure_obc' => 'required',
            'risque_victime' => 'required',
            'attente_victime' => 'required',

            'fichier1' => 'nullable|file|max:10240',
            'fichier2' => 'nullable|file|max:10240',
            'fichier3' => 'nullable|file|max:10240',
        ]);

        $code = 'VIO-' . date('Y') . '-' . strtoupper(Str::random(5));

        $data = $request->all();
        $data['code'] = $code;
        $data['user_id'] = auth()->id();

        if ($request->hasFile('fichier1')) {
            $data['fichier1'] = $request->file('fichier1')->store('violences', 'public');
        }

        if ($request->hasFile('fichier2')) {
            $data['fichier2'] = $request->file('fichier2')->store('violences', 'public');
        }

        if ($request->hasFile('fichier3')) {
            $data['fichier3'] = $request->file('fichier3')->store('violences', 'public');
        }

        Violences::create($data);

        return redirect()->route('view.violences')->with('success', 'Cas enregistré avec succès');
    }

    public function edit($id)
    {
        $violence = Violences::findOrFail($id);
        $natures = Nature::all();
        $collectes = Collecte::all();

        // On définit la liste des statuts
        $statuses = ['Victime', 'Temoin', 'DDH'];

        // On définit les nationalités (pour être sûr que ça marche aussi)
        $nationalites = ['Camerounaise', 'Sénégalaise', 'Ivoirienne', 'Malienne', 'Autre'];

        return view('updateviolence', compact('violence', 'natures', 'collectes', 'statuses', 'nationalites'));
    }

    // Pour sauvegarder les changements
    public function update(Request $request)
    {
        $violence = Violences::findOrFail($request->id);
        $data = $request->all();

        // Gestion de l'upload fichier 1 (répétez pour 2 et 3)
        if ($request->hasFile('fichier1')) {
            $data['fichier1'] = $request->file('fichier1')->store('violences', 'public');
        }

        if ($request->hasFile('fichier2')) {
            $data['fichier2'] = $request->file('fichier2')->store('violences', 'public');
        }

        if ($request->hasFile('fichier3')) {
            $data['fichier3'] = $request->file('fichier3')->store('violences', 'public');
        }

        $violence->update($data);

        return redirect()->route('view.violences')->with('success', 'Violence mise à jour avec succès');
    }


    public function listViolence(Request $request){
        $user = Auth::user();
        $violences = Violence::where('user_id', $user->id)->get();
        return response()->json($violences); 
    }

    public function storeAPI(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:100',
            'contact' => 'required|string|max:150',
            'occupation' => 'nullable|string|max:150',
            'age' => 'required|integer|min:0|max:120',
            'sexe' => 'required|in:M,F,Autre',
            'nationalite' => 'required|string|max:100',

            'residence' => 'required|string|max:255',
            'datesurvenue' => 'required|date',
            'lieusurvenue' => 'required|string|max:255',
            'situation' => 'required|string|max:255',
            'auteurs' => 'nullable|string|max:255',

            'collecte_id' => 'required|exists:collectes,id',

            'description_cas' => 'nullable|string',
            'mesure_obc' => 'nullable|string',
            'risque_victime' => 'nullable|string',
            'attente_victime' => 'nullable|string',

            'fichie1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fichie2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fichie3' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $validated['code'] = Str::uuid();

        $validated['user_id'] = Auth::id();

        // handle files
        if ($request->hasFile('fichie1')) {
            $validated['fichie1'] = $request->file('fichie1')->store('violence_files');
        }

        if ($request->hasFile('fichie2')) {
            $validated['fichie2'] = $request->file('fichie2')->store('violence_files');
        }

        if ($request->hasFile('fichie3')) {
            $validated['fichie3'] = $request->file('fichie3')->store('violence_files');
        }

        $violence = Violence::create($validated);

        return response()->json($violence, 201);
    }

    public function updateAPI(Request $request, $code)
    {
        $violence = Violence::where('code', $code)
            ->where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'status' => 'sometimes|string|max:100',
            'contact' => 'sometimes|string|max:150',
            'occupation' => 'nullable|string|max:150',
            'age' => 'sometimes|integer|min:0|max:120',
            'sexe' => 'sometimes|in:M,F,Autre',
            'nationalite' => 'sometimes|string|max:100',

            'residence' => 'sometimes|string|max:255',
            'datesurvenue' => 'sometimes|date',
            'lieusurvenue' => 'sometimes|string|max:255',
            'situation' => 'sometimes|string|max:255',
            'auteurs' => 'nullable|string|max:255',

            'collecte_id' => 'sometimes|exists:collectes,id',

            'description_cas' => 'nullable|string',
            'mesure_obc' => 'nullable|string',
            'risque_victime' => 'nullable|string',
            'attente_victime' => 'nullable|string',

            'fichie1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fichie2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fichie3' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        if ($request->hasFile('fichie1')) {
            $validated['fichie1'] = $request->file('fichie1')->store('violence_files');
        }

        if ($request->hasFile('fichie2')) {
            $validated['fichie2'] = $request->file('fichie2')->store('violence_files');
        }

        if ($request->hasFile('fichie3')) {
            $validated['fichie3'] = $request->file('fichie3')->store('violence_files');
        }

        $violence->update($validated);

        return response()->json($violence);
    }
}
