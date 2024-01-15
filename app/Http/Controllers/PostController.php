<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostSharedNotification;
use App\Repositories\PostRepository;
use App\Rules\IntegerArray;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use URL;

/**
 * @group Post Management
 * APIs to manage post.
 */
class PostController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * Gets a list of posts.
     * @apiResourceCollection App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $posts = Post::query()->paginate($request->page_size ?? 20);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required The author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     *
     * @param Request $request
     * @param PostRepository $repository
     * @return PostResource
     * @throws \Illuminate\Validation\ValidationException
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
     * Display the specified post.
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Update the specified post in storage.
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required The author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     *
     * @param Request $request
     * @param Post $post
     * @param PostRepository $repository
     * @return PostResource|JsonResponse
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
     * Remove the specified post from storage.
     * @response 200 {
     * "data": "success"
     * }
     * @param Post $post
     * @param PostRepository $repository
     * @return JsonResponse
     */
    public function destroy(Post $post, PostRepository $repository): JsonResponse
    {
        $res = $repository->forceDelete($post);
        return response()->json([
            'data' => 'success',
        ]);
    }

    /**
     * Share a specified post from storage.
     * @response 200 {
     * "data": "signed url..."
     * }
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function share(Request $request, Post $post): JsonResponse
    {
        $url = URL::temporarySignedRoute('shared.post', now()->addDays(30), [
            'post' => $post->id,
        ]);

        $users = User::query()->whereIn('id', $request->user_ids)->get(); // collection
        Notification::send($users, new PostSharedNotification($post, $url));
        // or via User model class:
        /*$users->each(function (User $user) use ($post, $url) {
            $user->notify(new PostSharedNotification($post, $url));
        });*/

        return response()->json([
            'data' => $url,
        ]);
    }
}
