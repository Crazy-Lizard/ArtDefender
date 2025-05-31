<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    // Создание комментария
    public function store(Request $request)
    {
        try {
            // Минимальная валидация
            $request->validate([
                'body' => 'required|string',
                'art_id' => 'required|exists:arts,id',
                'parent_id' => 'nullable|exists:comments,id'
            ]);

            // Создаем комментарий без дополнительных проверок
            $comment = Comment::create([
                'body' => $request->body,
                'art_id' => $request->art_id,
                'user_id' => auth()->id(),
                'parent_id' => $request->parent_id
            ]);
            
            // $comment->load('user');
            // $html = view('comments.comment', ['comment' => $comment])->render();

            return response()->json([
                'success' => true,
                'comment' => [
                    'body' => $comment->body,
                    'user' => [
                        'name' => auth()->user()->name
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() // Возвращаем текст ошибки
            ], 500);
        }
    }

    // Редактирование комментария
    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Только автор может редактировать комментарий'], 403);
        }

        $validated = $request->validate(['body' => 'required|string|max:1000']);
        $comment->update(['body' => $validated['body']]);

        return response()->json($comment);
    }

    // Удаление комментария
    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        
        if ($user->id !== $comment->user_id && !$user->isModerator()) {
            return response()->json(['error' => 'Недостаточно прав для удаления'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Комментарий перемещен в корзину']);
    }

    // Восстановление комментария
    public function restore($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Только автор может восстановить комментарий'], 403);
        }

        $comment->restore();
        return response()->json($comment);
    }

    // Получение комментариев
    public function index($art_id)
    {
        // $comments = Comment::with(['user', 'replies' => function($query) {
        //     $query->orderBy('created_at', 'asc')->with('user'); // Ответы сортируем по возрастанию (старые сверху)
        // }])
        $comments = Comment::with(['user', 'replies.user'])
        ->where('art_id', $art_id)
        ->whereNull('parent_id')
        ->orderBy('created_at', 'desc') // Основные комментарии по убыванию (новые сверху)
        ->get();

        return response()->json($comments);
    }
}
