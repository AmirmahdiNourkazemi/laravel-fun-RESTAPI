<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDepositController extends Controller
{
    public function update(Request $request, $depositUuid)
    {
        $data = $request->validate([
            'amount' => 'integer|required',
            'ref_id' => 'required|string',
            'deposit_date' => 'required|date',
        ]);
        if (!$deposit = Deposit::where('uuid', $depositUuid)->first()) {
            return response()->json([
                'message' => 'deposit not found'
            ], 404);
        }

        $deposit->update($data);

        return $deposit;
    }

    public function changeDepositStatus(Request $request, $depositUuid)
    {
        $data = $request->validate([
            'status' => ['integer', Rule::in(Deposit::STATUSES)],
        ]);
        if (!$deposit = Deposit::where('uuid', $depositUuid)->first()) {
            return response()->json([
                'message' => 'deposit not found'
            ], 404);
        }

        $oldStatus = $deposit->status;
        $deposit->status = $data['status'];
        $deposit->save();

        if ($data['status'] != $oldStatus && $data['status'] == Deposit::STATUSES['success']) {
          
            $deposit->project->buy($deposit->amount, $deposit->user, false, $deposit['ref_id']);
        }

      

        // return response()->json([
        //     'message' => 'success'
        // ]);
    }
    public function getDeposits(Request $request)
    {
        $data = $request->validate([
            'per_page' => 'integer',
            'search' => 'string|nullable',
            'status' => ['integer', Rule::in(Deposit::STATUSES)],
        ]);

        $withdraws = Deposit::with([
            'user',
            'project:id,title,uuid'
        ])->orderByDesc('created_at')
            ->paginate($data['per_page'] ?? 30);

        return $withdraws;
    }
}
