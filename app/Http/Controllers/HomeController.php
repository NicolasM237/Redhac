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

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $query = Violences::query();

        if ($user->profil !== 'Administrateur') {
            $query->where('user_id', $user->id);
        }

        if ($user->profil === 'Administrateur') {
            $nb_users = User::count();
            $nb_natures = Nature::count();
            $nb_collectes = Collecte::count();
        } else {
            $nb_users = 1;
            $nb_natures = Nature::count();
            $nb_collectes = Collecte::count();
        }

        $nb_violences = $query->count();

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

    /**
     * Page des utilisateurs avec liste
     */
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
            ->paginate(10)
            ->withQueryString();
        if ($user->profil === 'Administrateur') {
            $nb_users = User::count();
            $nb_natures = Nature::count();
            $nb_collectes = Collecte::count();
            $nb_violences = Violences::count();
        } else {
            $nb_users = 1; // lui-même
            $nb_natures = Nature::count(); // généralement global (référentiel)
            $nb_collectes = Collecte::count(); // idem
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
