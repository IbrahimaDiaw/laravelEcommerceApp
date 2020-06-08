<?php

namespace App\Http\Controllers\Auth\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VendorStoreRequest;
use JWTAuth;
use JWTFactory;
use JWTAuthException;
use Config;
use App\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function __construct(){
        $this->vendor = new Vendor;
        Config::set('jwt.user', App\Vendor::class);
        Config::set('auth.providers',['users' =>[
            'driver' => 'eloquent',
            'model' => Vendor::class,
        ]
        ]);
    }

    public function vendorRegister(Request $request){

        
        $vendor = Vendor::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'shop' => $request->shop,
            'phone' => $request->phone,
            'avatar' => $request->avatar,
           
        ]);
        

        $token = auth()->login($vendor);

        return response()->json([
                'success' => true,
                'data' => $vendor],200);

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
        return response()->json([
            'response' => 'success',
            'result' => [
                'token' => $token,
                'message' => 'I am Vendor user',
            ],
        ]);
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

    public function getAuthVendor(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $vendor = JWTAuth::authenticate($request->token);
 
        return response()->json(['vendor' => $vendor]);
    }
}
