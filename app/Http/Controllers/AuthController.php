<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTExceptions;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username','password');

        try{
            if(! $token = JWTAuth::attempt($credentials)){
                return response()-> json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e){
            return response()-> json(['message'=>'Generate token failed']);
        }

        $user = JWTAuth::user();

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'token' => $token,
            'user' => $user
        ]);

        return response()->json(['token' => $token]);
    }
    

    public function loginCheck()
    {
        try {
            if(! $user = JWTAuth::parseToken()->authenticate()) {
                return response()-> json(['message' => 'Invalid Token']);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()-> json(['message' => 'Token expired']);
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()-> json(['message' => 'Invalid Token']);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()-> json(['message' => 'Token absent']);
        }

        return response()-> json([
          'success' => true,
          'message' => 'Success'
        ]);
    }

    public function logout(Request $request)
    {
      if(JWTAuth::invalidate(JWTAuth::getToken())) {
        return response()->json(['message' => 'Anda sudah Logout']);
      } else {
        return response()->json(['message' => 'Gagal Logout']);
      }
    }
}
