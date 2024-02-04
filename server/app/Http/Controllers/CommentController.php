<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validationData = $request->validate([
            "content" => ["required", "string", "max:200"],
            "review_id" => ["required", "integer", "exists:reviews,id"]
        ]);

        $comment = Comment::create(
            [
                "content" => $validationData["content"],
                "user_id" => Auth::id(),
                "review_id" => $validationData["review_id"]
            ]
        );

        $comment->load("user");

        return response()->json($comment);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        if (Auth::id() !== $comment->user_id) {
            return response()->json(["message" => "権限がありません"], 401);
        }

        $validatedData = $request->validate([
            "content" => ["required", "string"],
        ]);

        $comment->update([
            "content" => $validatedData["content"],
        ]);

        return response()->json($comment);
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(["message" => "無事に削除できました"]);
    }
}
