<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['bail', 'required', 'string', 'min:3', 'max:30', 'alpha_dash:ascii', 'unique:users,login'],
            'nickname' => ['required', 'string', 'min:2', 'max:50'],
            'password' => ['bail', 'required', 'string', 'min:6', 'confirmed'],
        ], [
            'login.required' => 'Введите логин.',
            'login.min' => 'Минимум 3 символа.',
            'login.max' => 'Максимум 30 символов.',
            'login.alpha_dash' => 'Используйте латинские буквы, цифры, дефис или подчёркивание.',
            'login.unique' => 'Этот логин уже занят.',
            'nickname.required' => 'Введите никнейм.',
            'nickname.min' => 'Минимум 2 символа.',
            'password.required' => 'Введите пароль.',
            'password.min' => 'Пароль должен быть не короче 6 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);

        $userRole = Role::where('name', 'Пользователь')->firstOrFail();

        $user = User::create([
            'login' => $data['login'],
            'nickname' => $data['nickname'],
            'bio' => 'Пользователь сайта.',
            'role_id' => $userRole->id,
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Регистрация прошла успешно.');
    }

    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Введите логин.',
            'password.required' => 'Введите пароль.',
        ]);

        if (! Auth::attempt($data)) {
            return back()->withErrors(['login' => 'Неверный логин или пароль.'])->onlyInput('login');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Вы вышли из аккаунта.');
    }
}
