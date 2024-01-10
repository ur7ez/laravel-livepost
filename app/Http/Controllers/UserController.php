<?php

namespace App\Http\Controllers;

use App\Events\Models\User\UserCreated;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group User Management
 *
 * APIs to manage the user resource.
 */
class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * Gets a list of users.
     *
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        // event(new UserCreated(User::factory()->make()));
        $users = User::query()->paginate($request->page_size ?? 20);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created user in storage.
     *
     * @bodyParam name string required Name of the user. Example: John Doe
     * @bodyParam email string required Email of the user. Example: doe@doe.com
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @param Request $request
     * @return UserResource
     * @throws \Throwable
     */
    public function store(Request $request, UserRepository $repository): UserResource
    {
        $payload = $request->only([
            'name',
            'email',
            // 'password',
        ]);
        $created = $repository->create($payload);
        return new UserResource($created);
    }

    /**
     * Display the specified user.
     *
     * @urlParam id int required User ID
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified user in storage.
     * @bodyParam name string Name of the user. Example: John Doe
     * @bodyParam email string Email of the user. Example: doe@doe.com
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param Request $request
     * @param User $user
     * @param UserRepository $repository
     * @return UserResource|JsonResponse
     */
    public function update(Request $request, User $user, UserRepository $repository): UserResource|JsonResponse
    {
        $updated = $repository->update($user, $request->only([
            'name',
            'email',
            // 'password',
        ]));

        return new UserResource($updated);
    }

    /**
     * Remove the specified user from storage.
     * @response 200 {
            "data": "success"
     * }
     * @param User $user
     * @param UserRepository $repository
     * @return JsonResponse
     */
    public function destroy(User $user, UserRepository $repository): JsonResponse
    {
        $deleted = $repository->forceDelete($user);
        return response()->json([
            'data' => 'success',
        ]);
    }
}
