<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->toArray())) {
            /** @var User $user */
            $user = User::where('email', $request->input('email'))
                ->first();

            Auth::login($user);

            $token = $user->createToken('api');

            return $this->respond([
                'user' => new UserResource($user),
                'token' => [
                    'type' => 'Bearer',
                    'accessToken' => $token->accessToken,
                ]
            ]);
        }

        return $this->respondErrorMessage('Wrong credentials.');
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::user()->token()->revoke();

        return $this->respond([
            'message' => 'Successfully logged out.',
            'success' => true,
        ]);
    }
}
