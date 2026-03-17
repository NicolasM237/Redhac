<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nature;
use App\Models\Collecte;
use App\Models\Violences;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ViolencesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ViolencesController extends Controller
{


    public function viewviolences(Request $request)
    {
        $nationalites = ['Camerounaise', 'Gabonaise', 'Tchadienne', 'Centrafricaine', 'Congolaise', 'Autre'];

        // On récupère l'utilisateur connecté
        $user = auth()->user();

        // SÉCURITÉ : Si l'utilisateur n'est pas connecté, on le redirige ou on gère l'erreur
        if (!$user) {
            return redirect()->route('login');
        }

        $violences = Violences::with(['nature', 'collecte', 'user'])
            // On vérifie le rôle de manière sécurisée
            ->when($user->profil !== 'Administrateur', function ($q) use ($user) {
                return $q->where('user_id', $user->id);
            })
            ->when($request->filled('nationalite'), function ($q) use ($request) {
                return $q->where('nationalite', $request->nationalite);
            })
            ->when($request->filled('searchTerm'), function ($q) use ($request) {
                return $q->where('code', 'like', '%' . $request->searchTerm . '%');
            })
            ->latest()
            ->paginate(10);

        return view('violences', compact('violences', 'nationalites'));
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

    public function destroy($id)
    {
        $violence = Violences::findOrFail($id);
        $violence->delete();

        return redirect()->route('view.violences')->with('success', 'Violence supprimée avec succès');
    }
    public function store(Request $request)
    {
        if (!auth()->user()->active) {
            return redirect()->back()
                ->with('error', 'Votre compte est désactivé. Vous ne pouvez pas enregistrer de nouveaux cas.')
                ->withInput(); 
        }

        // 2. Votre validation existante
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

        // 3. Logique de création
        $code = 'VIO-' . date('Y') . '-' . strtoupper(Str::random(5));

        $data = $request->all();
        $data['code'] = $code;
        $data['user_id'] = auth()->id();

        // Gestion des fichiers...
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

        return redirect()->route('view.violences')->with('success', 'Déclaration du cas enregistrée avec succès');
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

        return redirect()->route('view.violences')->with('success', 'Declaration de cas mise à jour avec succès');
    }


    public function listApi(Request $request)
    {
        $user = Auth::user();
        $violences = Violences::with('collecte', 'nature')->where('user_id', $user->id)->get();
        return response()->json($violences);
    }

    public function storeAPI(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:100',
            'contact' => 'required|string|max:150',
            'occupation' => 'nullable|string|max:150',
            'age' => 'required|string',
            'sexe' => 'required|in:M,F,Autre',
            'nationalite' => 'required|string|max:100',

            'residence' => 'required|string|max:255',
            'datesurvenue' => 'required|date',
            'lieusurvenue' => 'required|string|max:255',
            'situation' => 'required|string|max:255',
            'auteurs' => 'nullable|string|max:255',

            'collecte_id' => 'required|exists:collectes,id',
            'nature_id' => 'required|exists:natures,id',

            'description_cas' => 'nullable|string',
            'mesure_obc' => 'nullable|string',
            'risque_victime' => 'nullable|string',
            'attente_victime' => 'nullable|string',

            'fichie1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fichie2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fichie3' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $validated['code'] =  $code = 'VIO-' . Auth::id() . '' . date('Y') . '-' . strtoupper(Str::random(5));

        $validated['user_id'] = Auth::id();

        // handle files
        if ($request->hasFile('fichie1')) {
            $validated['fichie1'] = $request->file('fichie1')->store('violences', 'public');
        }

        if ($request->hasFile('fichie2')) {
            $validated['fichie2'] = $request->file('fichie2')->store('violences', 'public');
        }

        if ($request->hasFile('fichie3')) {
            $validated['fichie3'] = $request->file('fichie3')->store('violences', 'public');
        }

        $violence = Violences::create($validated);

        return response()->json($violence->loadMissing('nature', 'collecte'), 201);
    }

    public function updateAPI(Request $request, $code)
    {
        $violence = Violences::where('code', $code)
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


    public function exportExcel()
    {
        if (ob_get_contents()) ob_end_clean(); // Nettoie le tampon de sortie
        return Excel::download(new ViolencesExport, 'liste-violences.xlsx');
    }

    public function exportCSV()
    {
        return Excel::download(new ViolencesExport, 'liste-violences.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF()
    {
        // Récupérer les données avec les relations
        $violences = Violences::with(['nature', 'collecte'])->get();

        // Charger une vue spécifique pour le PDF
        $pdf = Pdf::loadView('pdf.violences', compact('violences'));

        // Optionnel : Définir le format (A4) et l'orientation (paysage si le tableau est large)
        $pdf->setPaper('a4', 'landscape');

        // Télécharger le fichier
        return $pdf->download('liste-violences.pdf');
    }
}
