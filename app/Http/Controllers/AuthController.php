<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        return $this->attemptLogin($request->email, $request->password);
    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if(!$this->createUser($request->name, $request->email, $request->password)) {
            return response()->json(['error' => 'User creation failed']);
        }

        return $this->attemptLogin($request->email, $request->password);
    }

    private function createUser($name, $email, $password) {
        try {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password)
            ]);
            return true;
        }
        catch (QueryException $e) {
            Log::error($e->errorInfo);
            return false;
        }
    }

    private function attemptLogin($email, $password) {
        $token = Auth::attempt([ 'email' => $email, 'password' => $password ]);

        if(!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::user()
        ]);
    }
}
