<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        $user = User::updateOrCreate(
            [
                'numero' => $request->numero,
                'otp' => $otp,
                'type' => $type
            ],
        );

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

        $user = User::where('numero', $request->numero)
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
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request){
        $validator = Validator::make($request->all(),[
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'sexe' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $id = $request->user()->id;
        $user = User::where('id', $id)->first();

        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'sexe' => $request->sexe,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil mis a jour avec success',
            'user' => $user
        ]);
    }

       /** recupere la liste complete des utilisateurs */
    public function getUsersMobiles()
    {
        $users = User::where('type', 'mobile')->get();
        return response()->json($users);
    }
}
