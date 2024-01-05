<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
            //->where('id', '=', 1)
            ->get();
        return response()->json([
            'data' => $posts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $created = Post::query()->create([
            'title' => $request->title,
            'body' => $request->body,
        ]);
        return response()->json([
            'data' => $created,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'data' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        //$post->update($request->only(['title', 'body']));
        $updated = $post->update([
            'title' => $request->title ?? $post->title,
            'body' => $request->body ?? $post->body,
        ]);
        if (!$updated) {
            response()->json([
                'errors' => ['Failed to update model.'],
            ], 400);
        }
        return response()->json([
            'data' => $post,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $deleted = $post->forceDelete();
        if (!$deleted) {
            response()->json([
                'errors' => ['Failed to delete model.'],
            ], 400);
        }
        return response()->json([
            'data' => 'success',
        ]);
    }
}
