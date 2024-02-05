<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $api_key = config("services.tmdb.api_key");
        $details = [];

        foreach (Auth::user()->favorites as $favorite) {
            $response = Http::get("https://api.themoviedb.org/3/{$favorite->media_type}/{$favorite->media_id}?api_key={$api_key}");

            if ($response->successful()) {
                $details[] = array_merge($response->json(), ["media_type" => $favorite->media_type]);
            }
        }

        return response()->json($details);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleFavorite(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            "media_type" => ["required", Rule::enum(MediaType::class)],
            "media_id" => ["required", "integer"],
        ]);

        $existingFavorite = Favorite::userId(Auth::id())
            ->mediaType(MediaType::from($validatedData["media_type"]))
            ->mediaId(
                $validatedData["media_id"]
            )->first();

        if ($existingFavorite) {
            // お気に入りがすでに存在している場合
            $existingFavorite->delete();

            return response()->json(["status" => "removed"]);
        } else {
            // お気に入りが存在していない場合
            Favorite::create([
                "media_type" => $validatedData["media_type"],
                "media_id" => $validatedData["media_id"],
                "user_id" => Auth::id()
            ]);

            return response()->json(["status" => "added"]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkFavoriteStatus(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            "media_type" => ["required", Rule::enum(MediaType::class)],
            "media_id" => ["required", "integer"],
        ]);

        $isFavorite = Favorite::userId(Auth::id())
            ->mediaType(MediaType::from($validatedData["media_type"]))
            ->mediaId($validatedData["media_id"])
            ->exists();

        return response()->json($isFavorite);
    }
}
