<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $comments = Comment::query()->paginate($request->page_size ?? 20);
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CommentRepository $repository): CommentResource
    {
        $created = $repository->create($request->only([
            'title',
            'body',
            'user_id',
            'post_id',
        ]));
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
    public function update(Request $request, Comment $comment, CommentRepository $repository): CommentResource|JsonResponse
    {
        $updated = $repository->update($comment, $request->only([
            'title',
            'body',
            'user_id',
            'post_id',
        ]));
        return new CommentResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, CommentRepository $repository): JsonResponse
    {
        $deleted = $repository->forceDelete($comment);
        return response()->json([
            'data' => 'success',
        ]);
    }
}
