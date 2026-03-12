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

    public function index()
    {
        Historique::create([
            'user_id' => auth()->id(),
        ]);
        // 1. Comptage pour les widgets (Statistiques)
        $nb_users = User::count();
        $nb_natures = Nature::count();
        $nb_collectes = Collecte::count();
        $nb_violences = Violences::count();

        // 2. Récupération des données pour le graphe (Violences par Nationalité)
        // On récupère le nom de la nationalité et le nombre de répétitions
        $dataGraphe = Violences::select('nationalite', DB::raw('count(*) as total'))
            ->groupBy('nationalite')
            ->orderBy('total', 'desc')
            ->get();

        $labels = $dataGraphe->pluck('nationalite'); // Les noms des pays
        $values = $dataGraphe->pluck('total');      // Les nombres de cas

        // 3. Envoi de toutes les données à la vue
        return view('home', compact(
            'nb_users',
            'nb_natures',
            'nb_collectes',
            'nb_violences',
            'labels',
            'values'
        ));
    }

    /**
     * Page des utilisateurs avec liste
     */
    public function viewusers(Request $request)
    {
        $search = $request->input('search');

        // 1. Récupération des utilisateurs avec recherche et pagination
        $users = User::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telephone', 'like', "%$search%")
                    ->orWhere('profil', 'like', "%$search%");
            });
        })
            ->latest()
            ->paginate(10);

        // 2. Récupération des statistiques pour les widgets/sidebar
        $nb_users = User::count();
        $nb_natures = Nature::count();
        $nb_collectes = Collecte::count();
        $nb_violences = Violences::count();

        // 3. Envoi de toutes les données à la vue
        return view('utilisateurs', compact(
            'users',
            'nb_users',
            'nb_natures',
            'nb_collectes',
            'nb_violences'
        ));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'profil' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

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

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Empêcher la suppression d’un administrateur principal si besoin
        if ($user->profil === 'Administrateur') {
            return redirect()->back()->with('error', 'Impossible de supprimer un administrateur !');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès');
    }
}
