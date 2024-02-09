<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; //  pentru a utiliza Log


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            Log::info('Autentificare reușită.', ['email' => $request->input('email')]);
            return redirect()->intended('/');
        } else {
            Log::warning('Eșec la autentificare.', ['email' => $request->input('email')]);
            return back()->withErrors(['email' => 'Autentificarea a eșuat.']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('register')
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        Log::info('Utilizator nou înregistrat și autentificat.', ['email' => $request->input('email')]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Log::info('Utilizator deconectat.', ['user_id' => Auth::id()]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
