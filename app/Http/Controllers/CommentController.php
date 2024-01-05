<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $comments = Comment::query()->get();
        return response()->json([
            'data' => $comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $created = Comment::query()->create([
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
    public function show(Comment $comment): JsonResponse
    {
        return response()->json([
            'data' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment): JsonResponse
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
        return response()->json([
            'data' => $comment,
        ]);
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
