<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Show
    public function show(User $user)
    {
        return response()->json([
            'status' => true,
            'data' => $user
        ], 200);
    }
}
