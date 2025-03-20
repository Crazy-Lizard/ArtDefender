<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function login(){
        return view('login');
    }

    public function register(){
        return view('register');
    }

    public function login_confirm(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->hasRole('user')) {
                return redirect()->intended('/map');
            } elseif (Auth::user()->hasRole('moderator')) {
                return redirect()->intended('/map');
            }
        }

        return back()->withErrors([
            'mismatch' => 'Неверная почта или пароль',
        ]);
    }

    public function register_confirm(Request $request)
    {
        $request->validate([
            'role' => 'user',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Назначение роли пользователю
        // $role = Role::findByName($request->role);
        // $user->assignRole($role);

        // Перенаправление пользователя на страницу входа с сообщением об успешной регистрации
        // return redirect('/login')->with('status', 'Регистрация прошла успешно! Теперь вы можете войти в систему.');
        return redirect('/map');
    }
}
