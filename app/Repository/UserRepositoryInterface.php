<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface {
    public function store(Request $request): User;
}