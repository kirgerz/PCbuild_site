<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->load('role');
        $ownBuilds = $user->builds()
            ->with(['category', 'components'])
            ->latest()
            ->get();

        return view('profile', [
            'user' => $user,
            'ownBuilds' => $ownBuilds,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nickname' => ['required', 'string', 'min:2', 'max:50'],
            'bio' => ['nullable', 'string', 'max:500'],
        ], [
            'nickname.required' => 'Укажите никнейм.',
            'nickname.min' => 'Минимум 2 символа.',
            'nickname.max' => 'Максимум 50 символов.',
            'bio.max' => 'Максимум 500 символов.',
        ]);

        $request->user()->update([
            'nickname' => $data['nickname'],
            'bio' => $data['bio'] ?? '',
        ]);

        return redirect()->route('profile')->with('status', 'Данные профиля сохранены.');
    }
}
