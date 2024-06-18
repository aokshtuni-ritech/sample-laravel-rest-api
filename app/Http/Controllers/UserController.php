<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        public UserService $service
    ) {
    }
    public function index(Request $request)
    {
        $users = User::query()
            ->orderBy('id', 'DESC')
            ->get();

        return $this->respond(UserResource::collection($users));
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        try {
            return $this->respond(
                new UserResource(
                    $this->service->create($request->all())
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error creating User.');
        }
    }

    public function show(User $user): JsonResponse
    {
        return $this->respond(
            new UserResource($user)
        );
    }

    public function update(
        User $user,
        UserUpdateRequest $request
    ): JsonResponse
    {
        try {
            return $this->respond(
                new UserResource(
                    $this->service->update($user, $request->all())
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error updating User.');
        }
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->delete()) {
            return $this->respond([
                'success' => true,
                'message' => 'Successfully deleted User.'
            ]);
        }

        return $this->respondErrorMessage('Error on deleting User.');
    }
}
