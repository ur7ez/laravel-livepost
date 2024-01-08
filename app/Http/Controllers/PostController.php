<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Rules\IntegerArray;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $posts = Post::query()->paginate($request->page_size ?? 20);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PostRepository $repository): PostResource
    {
        $payload = $request->only([
            'title',
            'body',
            'user_ids',
        ]);
        Validator::validate($payload, [
            'title' => 'string|required',
            'body' => 'array|required',
            'user_ids' => [
                'array',
                'required',
                new IntegerArray(),
            ]
        ], [
            'title.string' => 'Hey use a string',
            'body.required' => 'Please enter a value for body',
        ], [
            'user_ids' => 'USER ID',
        ]);

        $created = $repository->create($payload);
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
    public function update(Request $request, Post $post, PostRepository $repository): PostResource|JsonResponse
    {
        $updated = $repository->update($post, $request->only([
            'title',
            'body',
            'user_ids',
        ]));
        return new PostResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, PostRepository $repository): JsonResponse
    {
        $res = $repository->forceDelete($post);
        return response()->json([
            'data' => 'success',
        ]);
    }
}
