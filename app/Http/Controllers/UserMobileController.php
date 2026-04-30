<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserMobileController extends Controller
{
    
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'required|string|min:9'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //genere le code otp 4 chiffres
        $otp = rand(1000, 9999);

        //creer l'utilisateur (seul le numero est requis ici)
        $user = User::query()->where('email', $request->numero)->first();
        if ($user == null) {
            $user = User::create(
                [
                    'email' => $request->numero,
                    'otp' => $otp,
                    'type' => 'mobile'
                ],
            );
        } else {
            $user->otp = $otp;
            $user->type = 'mobile';
            $user->save();
        }

        Mail::to($user->email)->send(new OTPMail($otp));
        //logique sms ici 

        return response()->json([
            'status' => 'success',
            'message' => 'OTP envoye avec succes',
            'code_debug' => $otp
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'numero' => 'required',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->numero)
            ->where('otp', $request->otp)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Code OTP invalide'
            ], 401);
        }

        $user->otp = null;
        $user->save();
        $token = $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Utilisateur verifie',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:50',
            'prenom' => 'sometimes|string|max:50',
            'sexe' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = $request->user()->id;
        $user = User::where('id', $id)->first();
        $updates = [];
        $path = null;
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('violences', 'public');
            $updates['path'] = $path;
        }

        $updates['nom'] = $request->nom;
        $updates['prenom'] = $request->prenom ?? "";
        $updates['sexe'] = $request->sexe;
        $user->update($updates);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil mis a jour avec success',
            'user' => $user
        ]);
    }

   public function viewmobiles()
    {
        $search = request('search');
        $status = request('status');

        $mobiles = User::where('type', 'mobile')
            // Filtre Recherche (Nom, Prénom, Téléphone)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%");
                });
            })
            // Filtre Statut (Actif = 1, Inactif = 0)
            ->when($status, function ($query, $status) {
                if ($status === 'active') {
                    return $query->where('active', 1);
                } elseif ($status === 'desactive') {
                    return $query->where('active', 0);
                }
            })
            ->latest() // Optionnel : pour voir les derniers inscrits en premier
            ->paginate(10); // Remplacement de get() par paginate

        // On conserve les paramètres de filtrage dans les liens de pagination
        $mobiles->appends([
            'search' => $search,
            'status' => $status
        ]);

        return view('mobiles', compact('mobiles'));
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->active = true;
        $user->save();

        return back()->with('success', 'Utilisateur activé avec succès !');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->active = false;
        $user->save();

        return back()->with('success', 'Utilisateur désactivé avec succès !');
    }
}
