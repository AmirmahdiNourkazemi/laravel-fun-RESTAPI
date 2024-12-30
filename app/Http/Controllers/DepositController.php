<?php

namespace App\Http\Controllers;


use App\Models\Deposit;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'per_page' => 'integer',
            'status' => ['integer', Rule::in(Deposit::STATUSES)],
        ]);

        $deposits = auth()->user()->deposits()->with(['project:id,title,uuid'])->orderByDesc('created_at')->paginate($data['per_page'] ?? 30);

        return $deposits;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'integer|required',
            'ref_id' => 'required|string',
            'deposit_date' => 'required|date',
            'image' => 'image|nullable',
            'project_id' => 'integer|required'
        ]);

        $user = auth()->user();
        if ($user->deposits()->where('status', Deposit::STATUSES['pending'])->first()) {
            return response()->json([
                'message' => 'شما یک درخواست بررسی شده دارید لطفا منتظر تایید شدن آن بمانید'
            ], 400);
        }

        if (!$project = Project::where('id', $data['project_id'])->first()) {
            return response()->json([
                'message' => 'project not found'
            ], 404);
        }

        // $projectAvailability = $project->isProjectAvailableToInvest($user, $data['amount']);
        // if (!$projectAvailability['status']) {
        //     return response()->json([
        //         'message' => $projectAvailability['message']
        //     ], 400);
        // }

        $deposit = Deposit::create([
            'amount' => $data['amount'],
            'ref_id' => $data['ref_id'],
            'deposit_date' => $data['deposit_date'],
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);

        if (isset($data['image'])) {
            $path = $request->file('image')->store('temps', 'public');
            $deposit->addMediaFromDisk($path, 'public')
                ->toMediaCollection('image');
        }
        return $deposit;
    }
}
