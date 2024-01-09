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
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        // event(new UserCreated(User::factory()->make()));
        $users = User::query()->paginate($request->page_size ?? 20);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created user in storage.
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
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserRepository $repository): JsonResponse
    {
        $deleted = $repository->forceDelete($user);
        return response()->json([
            'data' => 'success',
        ]);
    }
}
