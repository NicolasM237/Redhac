<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Nature;
use App\Models\Collecte;
use App\Models\Violences;
use Illuminate\Support\Facades\DB;
use App\Models\Historique;


class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**connexion du user par son role */
   public function index()
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    $query = Violences::query();

    // Gestion de la visibilité des données de violence
    if ($user->profil !== 'Administrateur' && $user->profil !== 'Super_admin') {
        $query->where('user_id', $user->id);
    }

    // Statistiques réservées au Super_admin
    if ($user->profil === 'Super_admin') {
        $nb_users = User::count();
        $nb_natures = Nature::count();
        $nb_collectes = Collecte::count();
    } else {
        // Valeurs par défaut pour les autres profils (évite les erreurs undefined variable)
        $nb_users = 0; 
        $nb_natures = 0;
        $nb_collectes = 0;
    }

    $nb_violences = $query->count();

    // Données du graphe
    $dataGraphe = $query
        ->select('nationalite', DB::raw('count(*) as total'))
        ->groupBy('nationalite')
        ->orderByDesc('total')
        ->get();

    $labels = $dataGraphe->pluck('nationalite');
    $values = $dataGraphe->pluck('total');

    return view('home', compact(
        'nb_users',
        'nb_natures',
        'nb_collectes',
        'nb_violences',
        'labels',
        'values'
    ));
}

    /**affichage des users */
   public function viewusers(Request $request)
{
    $search = $request->input('search');
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    $users = User::where('type', 'web')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telephone', 'like', "%$search%")
                    ->orWhere('profil', 'like', "%$search%");
            });
        })
        ->latest()
        ->paginate(5); // Remplacé get() par paginate(10)

    // Pour que la recherche fonctionne aussi sur les pages suivantes
    $users->appends(['search' => $search]);

    if ($user->profil === 'Super_admin') {
        $nb_users = User::count();
        $nb_natures = Nature::count();
        $nb_collectes = Collecte::count();
        $nb_violences = Violences::count();
    } else {
        $nb_users = User::count();
        $nb_natures = Nature::count();
        $nb_collectes = Collecte::count();
        $nb_violences = Violences::where('user_id', $user->id)->count();
    }

    return view('utilisateurs', compact(
        'users',
        'nb_users',
        'nb_natures',
        'nb_collectes',
        'nb_violences'
    ));
}

    /**creation des users */
    public function createUser(Request $request)
{
    // 1. Sécurité : Vérifier si l'utilisateur connecté est un Super_admin
    if (auth()->user()->profil !== 'Super_admin') {
        return redirect()->back()->with('error', 'Action non autorisée. Seul le Super_admin peut créer des utilisateurs.');
    }

    // 2. Validation des données
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'telephone' => 'nullable|string|max:20',
        'adresse' => 'nullable|string|max:255',
        'profil' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    // 3. Création de l'utilisateur
    User::create([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'telephone' => $request->telephone,
        'adresse' => $request->adresse,
        'profil' => $request->profil,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->back()->with('success', 'Utilisateur créé avec succès');
}

    /**Mise a jour des users */
    public function updateUser(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'profil' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->id);

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->telephone = $request->telephone;
        $user->adresse = $request->adresse;
        $user->profil = $request->profil;
        $user->email = $request->email;

        // mise à jour du mot de passe seulement s'il est rempli
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Utilisateur modifié avec succès');
    }

    /**supprimer des users */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Empêcher la suppression d’un Super_admin principal si besoin
        if ($user->profil === 'super_admin') {
            return redirect()->back()->with('error', 'Impossible de supprimer un Super admin !');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès');
    }

    /**supprimer des users cote mobiles */
    public function destroy($id)
    {
        try {

            $user = User::findOrFail($id);

            // Vérifier que l'utilisateur est bien de type mobile
            if ($user->type !== 'mobile') {
                return redirect()->back()->with('error', 'Seuls les utilisateurs mobiles peuvent être supprimés.');
            }

            $user->delete();

            return redirect()->back()->with('success', 'Utilisateur mobile supprimé avec succès.');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }
}
