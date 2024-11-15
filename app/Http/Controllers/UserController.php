<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) 
    {
        $user = auth()->user();
        $perPage = $request->input('per_page', 10);
        $perPage = is_numeric($perPage) && $perPage > 0 ? (int)$perPage : 10;
        $users = User::paginate($perPage);
        return $users;
    }  
}
