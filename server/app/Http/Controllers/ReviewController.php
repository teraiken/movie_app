<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(string $media_type, int $media_id): JsonResponse
    {
        $reviews = Review::query()->with("user")->mediaType(MediaType::from($media_type))->mediaId($media_id)->get();

        return response()->json($reviews);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            "content" => ["required", "string"],
            "rating" => ["required", "integer"],
            "media_id" => ["required", "integer"],
            "media_type" => ["required", Rule::enum(MediaType::class)],
        ]);

        $review = Review::create([
            "content" => $validatedData["content"],
            "rating" => $validatedData["rating"],
            "user_id" => Auth::id(),
            "media_id" => $validatedData["media_id"],
            "media_type" => $validatedData["media_type"],
        ]);

        $review->load("user");

        return response()->json($review);
    }

    /**
     * @param Request $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        $validatedData = $request->validate([
            "content" => ["required", "string"],
            "rating" => ["required", "integer"],
        ]);

        $review->update([
            "content" => $validatedData["content"],
            "rating" => $validatedData["rating"],
        ]);

        return response()->json($review);
    }

    /**
     * @param Review $review
     * @return JsonResponse
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(["message" => "正常にレビューを削除しました。"]);
    }
}
