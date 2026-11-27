<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Build $build): RedirectResponse
    {
        abort_if($build->is_blocked, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ], [
            'body.required' => 'Введите текст комментария.',
            'body.min' => 'Комментарий слишком короткий.',
            'body.max' => 'Максимум 1000 символов.',
        ]);

        $build->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return back()->with('status', 'Комментарий добавлен.');
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        abort_unless(
            $request->user()->hasRole('Администратор') || $comment->user_id === $request->user()->id,
            403
        );

        $comment->delete();

        return back()->with('status', 'Комментарий удалён.');
    }
}
