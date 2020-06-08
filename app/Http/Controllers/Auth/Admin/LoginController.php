<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminStoreRequest;
use Config;
use JWTAuth;
use JWTFactory;
use JWTAuthException;
use App\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function __construct(){
        $this->admin = new Admin;
        Config::set('jwt.user', App\Admin::class);
        Config::set('auth.providers',['users' =>[
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]
        ]);
    }

    public function adminRegister(Request $request){

        
        $admin = Admin::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'avatar' => $request->avatar,
            'password' => Hash::make($request->password),
        ]);
        

        $token = auth()->login($admin);

        return response()->json([
                'success' => true,
                'data' => $admin],200);

    }

    public function login(Request $request){
        $credentials = $request->only('email','password');
        $token = null;
        
        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'success' => false,
                    'message' => 'Email ou Mot de Passe est incorrect',

                ],401);
            }
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
       return response()->json(compact('token'));
    }

    
    public function logout(Request $request){
        $this->validate($request, [
            'token' => 'required'
        ]);

        try{
            JWTAuth::invalidate($request->token);
            return respone()->json([
                'success' => true,
                'message' =>'User logged out successfully'
            ]);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function getAuthAdmin(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $admin = JWTAuth::authenticate($request->token);
 
        return response()->json(['admin' => $admin]);
    }
}
