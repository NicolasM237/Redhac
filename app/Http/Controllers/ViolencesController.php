<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nature;
use App\Models\Collecte;
use App\Models\Violences;
use App\Models\Activite; // Modèle pour la table activites
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ViolencesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ViolencesController extends Controller
{
    /**
     * Centralise l'enregistrement des activités dans la table 'activites'
     */
    private function logActivity($type, $id = null, $description = null)
    {
        Activite::create([
            'user_id'     => Auth::id(),
            'action_type' => $type,
            'table_name'  => 'violences',
            'entity_id'   => $id,
            'description' => $description,
        ]);
    }

    private function applyFilters($query, Request $request)
    {
        return $query->when($request->filled('nationalite'), function ($q) use ($request) {
            return $q->where('nationalite', $request->nationalite);
        })
            ->when($request->filled('searchTerm'), function ($q) use ($request) {
                $search = $request->searchTerm;
                return $q->where(function ($sub) use ($search) {
                    $sub->where('code', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")
                        ->orWhereHas('nature', function ($sq) use ($search) {
                            $sq->where('nom', 'like', "%$search%");
                        });
                });
            });
    }

    public function viewviolences(Request $request)
    {
        $nationalites = ['Camerounaise', 'Gabonaise', 'Tchadienne', 'Centrafricaine', 'Congolaise', 'Autre'];
        $user = auth()->user();

        if (!$user) return redirect()->route('login');

        $query = Violences::with(['nature', 'collecte', 'user'])
            ->when($user->profil !== 'Administrateur', function ($q) use ($user) {
                return $q->where('user_id', $user->id);
            });

        $violences = $this->applyFilters($query, $request)->latest()->paginate(10);

        return view('violences', compact('violences', 'nationalites'));
    }

    public function addViolences()
    {
        $natures = Nature::all();
        $collectes = Collecte::all();
        $nationalites = ['Camerounaise', 'Gabonaise', 'Tchadienne', 'Centrafricaine', 'Congolaise', 'Nigériane', 'Ghanéenne', 'Ivoirienne', 'Sénégalaise', 'Malienne', 'Burkinabè', 'Béninoise', 'Togolaise', 'Guinéenne'];

        $this->logActivity('Consultation', null, "Accès au formulaire d'ajout");

        return view('addviolences', compact('natures', 'collectes', 'nationalites'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->active) {
            return redirect()->back()->with('error', 'Votre compte est désactivé.')->withInput();
        }

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
            'fichier1' => 'nullable|file|max:10240',
            'fichier2' => 'nullable|file|max:10240',
            'fichier3' => 'nullable|file|max:10240',
        ]);

        $data = $request->all();
        $data['code'] = 'VIO-' . date('Y') . '-' . strtoupper(Str::random(5));
        $data['user_id'] = auth()->id();

        foreach (['fichier1', 'fichier2', 'fichier3'] as $f) {
            if ($request->hasFile($f)) $data[$f] = $request->file($f)->store('violences', 'public');
        }

        $violence = Violences::create($data);

        $this->logActivity('Création', $violence->id, "Nouveau cas enregistré : " . $data['code']);

        return redirect()->route('view.violences')->with('success', 'Déclaration enregistrée avec succès');
    }

    public function edit($id)
    {
        $violence = Violences::findOrFail($id);
        $natures = Nature::all();
        $collectes = Collecte::all();
        $statuses = ['Victime', 'Temoin', 'DDH'];
        $nationalites = ['Camerounaise', 'Sénégalaise', 'Ivoirienne', 'Malienne', 'Autre'];

        $this->logActivity('Consultation', $violence->id, "Ouverture édition pour ID: " . $violence->code);

        return view('updateviolence', compact('violence', 'natures', 'collectes', 'statuses', 'nationalites'));
    }

    public function update(Request $request)
    {
        $violence = Violences::findOrFail($request->id);

        if (!$violence->permis) {
            $this->logActivity('Echec Modification', $violence->id, "Tentative interdite");
            return redirect()->route('view.violences')->with('error', 'Opération non autorisée sur ce cas.');
        }

        $data = $request->all();
        foreach (['fichier1', 'fichier2', 'fichier3'] as $f) {
            if ($request->hasFile($f)) $data[$f] = $request->file($f)->store('violences', 'public');
        }

        $violence->update($data);
        $this->logActivity('Modification', $violence->id, "Mise à jour réussie du code: " . $violence->code);

        return redirect()->route('view.violences')->with('success', 'Mise à jour effectuée');
    }

    public function destroy($id)
    {
        $violence = Violences::findOrFail($id);
        $code = $violence->code;
        $violence->delete();

        $this->logActivity('Suppression', $id, "Suppression du cas : $code");

        return redirect()->route('view.violences')->with('success', 'Supprimé avec succès');
    }

    public function togglePermis($id)
    {
        $violence = Violences::findOrFail($id);
        $violence->permis = !$violence->permis;
        $violence->save();

        $this->logActivity('Droit', $violence->id, "Changement permission modification pour ID: " . $violence->code . " vers " . ($violence->permis ? 'Autorisé' : 'Interdit'));

        return response()->json(['success' => true, 'permis' => $violence->permis]);
    }

    // --- SECTION API ---

    public function storeAPI(Request $request)
    {
        // Validation et Logique identique à Store (simplifiée ici pour l'exemple)
        $validated = $request->validate([
            'status' => 'required|string|max:100',
            'contact' => 'required|string|max:150',
            'occupation' => 'required|string|max:150',
            'age' => 'required|integer|min:0|max:120',
            'sexe' => 'required|in:M,F,Autre',
            'nationalite' => 'required|string|max:100',
            'residence' => 'required|string|max:255',
            'datesurvenue' => 'required|date',
            'lieusurvenue' => 'required|string|max:255',
            'situation' => 'required|string|max:255',
            'auteurs' => 'required|string|max:255',
            'collecte_id' => 'required|exists:collectes,id',
            'nature_id' => 'required|exists:natures,id',
            'description_cas' => 'nullable|string',
            'mesure_obc' => 'nullable|string',
            'risque_victime' => 'nullable|string',
            'attente_victime' => 'nullable|string',
            'coordinates' => 'sometimes|string',
            'fichier1' => 'nullable|file|max:21120',
            'fichier2' => 'nullable|file|max:21120',
            'fichier3' => 'nullable|file|max:21120'
        ]);

        $validated['code'] = 'VIO-' . Auth::id() . date('Y') . '-' . strtoupper(Str::random(5));
        $validated['user_id'] = Auth::id();

        $user = $request->user();
        if($user->active == 0){
            return response()->json("Account not active", 403);
        }

        // handle files
        if ($request->hasFile('fichier1')) {
            $validated['fichier1'] = $request->file('fichier1')->store('violences', 'public');
        }

        if ($request->hasFile('fichier2')) {
            $validated['fichier2'] = $request->file('fichier2')->store('violences', 'public');
        }

        if ($request->hasFile('fichier3')) {
            $validated['fichier3'] = $request->file('fichier3')->store('violences', 'public');
        }

        $violence = Violences::create($validated);
        $this->logActivity('Création API', $violence->id, "Création mobile : " . $validated['code']);

        return response()->json($violence->loadMissing('nature', 'collecte'), 201);
    }

    public function updateAPI(Request $request, $code)
    {
        $violence = Violences::where('code', $code)->where('user_id', Auth::id())->firstOrFail();

        if (!$violence->permis) {
            $this->logActivity('Echec Modification API', $violence->id, "Tentative API interdite");
            return response()->json(['message' => "Modification interdite"], 400);
        }

        $validated = $request->validate([
            'status' => 'sometimes|string|max:100',
            'contact' => 'sometimes|string|max:150',
            'occupation' => 'nullable|string|max:150',
            'age' => 'sometimes|integer|min:0|max:120',
            'sexe' => 'sometimes|in:M,F,Autre',
            'nationalite' => 'sometimes|string|max:100',
            'coordinates' => 'nullable|string',
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
            'fichier1' => 'nullable|file|max:21120',
            'fichier2' => 'nullable|file|max:21120',
            'fichier3' => 'nullable|file|max:21120'
        ]);

        // handle files
        if ($request->hasFile('fichier1')) {
            $validated['fichier1'] = $request->file('fichier1')->store('violences', 'public');
        }

        if ($request->hasFile('fichier2')) {
            $validated['fichier2'] = $request->file('fichier2')->store('violences', 'public');
        }

        if ($request->hasFile('fichier3')) {
            $validated['fichier3'] = $request->file('fichier3')->store('violences', 'public');
        }

        $violence->update($validated);
        $this->logActivity('Modification API', $violence->id, "Mise à jour via API du code : $code");

        return response()->json($violence);
    }

    // --- SECTION EXPORTS ---

    public function exportExcel(Request $request)
    {
        if (ob_get_contents()) ob_end_clean();

        // On récupère les colonnes choisies (ou toutes par défaut)
        $columns = $request->input('columns') ? explode(',', $request->input('columns')) : [];

        $violences = $this->applyFilters(Violences::with(['nature', 'collecte']), $request)->get();

        return Excel::download(new ViolencesExport($violences, $columns), 'liste-violences.xlsx');
    }

    public function exportCSV(Request $request)
    {
        // On récupère les colonnes choisies (ou toutes par défaut)
        $columns = $request->input('columns') ? explode(',', $request->input('columns')) : [];

        $violences = $this->applyFilters(Violences::with(['nature', 'collecte']), $request)->get();

        $this->logActivity('Export', null, "Exportation CSV générée");
        return Excel::download(new ViolencesExport($violences, $columns), 'liste-violences.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $columns = $request->input('columns') ? explode(',', $request->input('columns')) : [];
        $violences = $this->applyFilters(Violences::with(['nature', 'collecte']), $request)->get();
        $pdf = Pdf::loadView('pdf.violences', compact('violences', 'columns'))->setPaper('a4', 'landscape');

        $this->logActivity('Export', null, "Exportation PDF générée");
        return $pdf->download('liste-violences.pdf');
    }
}
