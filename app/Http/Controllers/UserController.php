<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;

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

    public function show(Request $request, $uuid) 
    {
        $user = User::where('uuid', $uuid)->with('projects')->first();
        return $user;
    }
    
    public function getProfile()
    {
        $user = auth()->user();
        $projects = $user->projects()
        // ->with([
        //     'paymentTransactions' => fn ($q) => $q->where('user_id', $user->id)->whereIn('status', [
        //         PaymentTransaction::STATUSES['success'],
        //         PaymentTransaction::STATUSES['consumed']
        //     ])->orderByDesc('created_at'),
        //     'transactions' => fn ($q) => $q->where('user_id', $user->id)->orderByDesc('created_at')
        // ])->orderByDesc('user_project.created_at')
        ->get();
        // Return the user details along with the related data
        return response()->json([
            'user' => $user,
            'projects' => $projects
        ]);
    }
}
