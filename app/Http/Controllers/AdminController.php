<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\Component;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin', [
            'builds' => Build::with(['user', 'category', 'components'])->latest()->orderByDesc('id')->paginate(6),
            'users' => User::with('role')->orderBy('login')->get(),
            'roles' => Role::orderBy('id')->get(),
            'components' => Component::orderBy('type')->orderBy('price')->get(),
        ]);
    }

    public function toggleBlock(Build $build): RedirectResponse
    {
        $build->is_blocked = ! $build->is_blocked;
        $build->save();

        return redirect()->route('admin')->with(
            'status',
            $build->is_blocked ? 'Сборка заблокирована.' : 'Сборка разблокирована.'
        );
    }

    public function destroyBuild(Build $build): RedirectResponse
    {
        $build->delete();

        return redirect()->route('admin')->with('status', 'Сборка удалена.');
    }

    public function updateRole(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        $adminRole = Role::where('name', 'Администратор')->firstOrFail();
        $user = User::findOrFail((int) $data['user_id']);

        $adminRoleId = (int) $adminRole->id;
        $currentRoleId = (int) $user->role_id;
        $newRoleId = (int) $data['role_id'];

        // 1. Запрещаем админу снимать права администратора с самого себя
        if ((int) $user->id === (int) $request->user()->id && $newRoleId !== $adminRoleId) {
            return back()->withErrors([
                'role_id' => 'Вы не можете снять права администратора с самого себя.',
            ]);
        }

        // 2. Запрещаем снимать роль с последнего администратора в БД
        $adminCount = User::where('role_id', $adminRoleId)->count();
        if ($currentRoleId === $adminRoleId && $newRoleId !== $adminRoleId && $adminCount <= 1) {
            return back()->withErrors([
                'role_id' => 'Нельзя снять роль с единственного администратора сайта.',
            ]);
        }

        $user->role_id = $newRoleId;
        $user->save();

        return redirect()->route('admin')->with('status', "Роль пользователя {$user->login} изменена.");
    }

    public function storeComponent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(Component::TYPES))],
            'name' => ['required', 'string', 'max:150'],
            'socket' => ['nullable', 'string', 'max:30'],
            'ram_type' => ['nullable', 'string', 'max:15'],
            'power_w' => ['required', 'integer', 'min:0', 'max:2500'],
            'tier' => ['required', 'integer', 'min:1', 'max:5'],
            'price' => ['required', 'integer', 'min:100', 'max:1000000'],
        ], [
            'name.required' => 'Укажите название комплектующего.',
            'price.required' => 'Укажите цену.',
        ]);

        Component::create($data);

        return redirect()->route('admin')->with('status', 'Комплектующее добавлено.');
    }
}
