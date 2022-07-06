<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartCollection;
use App\Http\Resources\OrderCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup() {
        $attr = request()->validate([
            'firstname' => ['required', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'min:7', 'max:255', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/']
        ]);

        $user = User::create($attr);
        $token = $user->createToken('authToken')->plainTextToken;

        return ['token' => $token];
    }

    public function login() {
        $attr = request()->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        $user = User::where('email', request('email'))->first();
        if(!$user) {
            return ['msg' => 'Invalid credentials'];
        } elseif(!auth()->attempt($attr)) {
            return ['msg' => 'Invalid credentials'];
        } else {
            $token = $user->createToken('authToken')->plainTextToken;
            return ['token' => $token];
        }
    }

    public function index() {
        return [
            'cart' => new CartCollection(request()->user()->products),
            'orders' => new OrderCollection(request()->user()->orders),
            'you' => request()->user()
        ];
    }

    public function logout() {
        request()->user()->currentAccessToken()->delete();
        return ['msg' => 'Logged out.'];
    }
}
