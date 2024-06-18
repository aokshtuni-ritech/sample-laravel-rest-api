<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function create(
        array $data
    ): User
    {
        $user = new User();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->role = $data['role'];
        $user->email_verified_at = now();
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function update(
        User $user,
        array $data
    ): User
    {
        $user->name = $data['name'];
        $user->role = $data['role'];
        $user->password = Hash::make($data['password']);
        $user->update();

        return $user;
    }
}
