<?php
namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface {

    public function store(Request $request): User
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_type_id' => $request->user_type_id,
            'password' => Hash::make($request->password)
         ]);

         return $user;
    }
}