<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function getPaymentTransactions(Request $request)
    {
        $data = $request->validate([
            'per_page' => 'integer',
            'status' => [Rule::in(PaymentTransaction::STATUSES)],
        ]);

        $transactions = auth()->user()->paymentTransactions()->filter([
            'status' => $data['status'] ?? null
        ])->orderByDesc('created_at')->paginate($data['per_page'] ?? 30);

        return $transactions;
    }

    public function getTransactions(Request $request)
    {
        $data = $request->validate([
            'per_page' => 'integer',
            'type' => [Rule::in(Transaction::TYPES)],
        ]);

        $trades = auth()->user()->transactions()->filter([
            'type' => $data['type'] ?? null
        ])->orderByDesc('created_at')->paginate($data['per_page'] ?? 30);

        return $trades;
    }

    public function getGateway(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'project_id' => 'nullable|integer',
            'amount' => 'required|numeric',
            'from_wallet' => 'bool|nullable',
            'public' => 'bool|nullable',
        ]);

        $user = auth()->user();
        $price = $data['amount'];

        if (isset($data['project_id'])) {
            if (!$project = Project::where('id', $data['project_id'])->first()) {
                return response()->json([
                    'message' => 'project not found'
                ], 404);
            }

            if ($price % 100 > 0) {
                return response()->json([
                    'message' => 'مبلغ سرمایه گذاری باید مضربی از 100 تومان باشد'
                ], 400);
            }

            $projectAvailability = $project->isProjectAvailableToInvest($user, $data['amount']);
            if (!$projectAvailability['status']) {
                return response()->json([
                    'message' => $projectAvailability['message']
                ], 400);
            }

            if (isset($data['from_wallet']) && $data['from_wallet']) {
                $price -= $user->wallet;
            }
        }

        $transaction = $user->paymentTransactions()->create([
            'amount' => $price,
            'wallet_amount' => ($data['from_wallet'] ?? false) == true ? $user->wallet : 0,
            'project_id' => $data['project_id'] ?? null,
            'inviter_id' => $user->currentAccessToken()->inviter_id,
            'public' => $data['public'] ?? false,
        ]);

        // $response = SepApi::getGateway($transaction, auth()->user());
        // $response = Zibal::getGateway($transaction, $data['description'], $user);
        if (!$response['status']) {
            return response()->json(["message" => $response['message']], 400);
        }

        $transaction->update(['authority' => $response['data']['authority']]);

        return response()->json([
            'url' => $response['data']['url']
        ]);
    }
}
