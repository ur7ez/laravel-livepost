<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        $comments = Comment::query()->get();
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): CommentResource
    {
        $created = Comment::query()->create([
            'title' => $request->title,
            'body' => $request->body,
        ]);
        return new CommentResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): CommentResource
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment): CommentResource|JsonResponse
    {
        $updated = $comment->update([
            'title' => $request->title ?? $comment->title,
            'body' => $request->body ?? $comment->body,
        ]);
        if (!$updated) {
            response()->json([
                'errors' => ['Failed to update resource.'],
            ], 400);
        }
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $deleted = $comment->forceDelete();
        if (!$deleted) {
            response()->json([
                'errors' => ['Failed to delete resource.'],
            ], 400);
        }
        return response()->json([
            'data' => 'success',
        ]);
    }
}
