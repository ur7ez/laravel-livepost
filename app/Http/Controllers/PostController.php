<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        $posts = Post::query()->get();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): PostResource
    {
        $created = DB::transaction(function () use ($request) {
            $created = Post::query()->create([
                'title' => $request->title,
                'body' => $request->body,
            ]);
            $created->users()->sync($request->user_ids);
            return $created;
        });

        return new PostResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): PostResource|JsonResponse
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
        return new PostResource($post);
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
