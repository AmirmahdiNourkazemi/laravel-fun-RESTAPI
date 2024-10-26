<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Companies;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
class CompaniesController extends Controller
{
    public function index() 
    {
        $user = auth()->user();
        $companies = $user->companies()->get();
        return $companies;
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'agent_name' => 'required|string',
            'field' => 'required|string',
            'phone_number' => 'required|string',
            'description' => 'nullable|string',
            'fund_needed' => ['integer','required' , Rule::in(Companies::FUNDS)],
            'anual_income' => ['integer','required' , Rule::in(Companies::INCOMES)],
            'profit' => ['integer','required' , Rule::in(Companies::PROFITS)],
            'bounced_check' => ['integer','required' , Rule::in(Companies::BOUNCED_CHECK_STATUSES)],
        ]);
        $data['user_id'] = auth()->user()->id;
        $company = Companies::create($data);
        return $company;
    }

     public function update(Request $request, $uuid)
     {
        $data  = $request->validate([
            'title' => 'required|string',
            'agent_name' => 'required|string',
            'field' => 'required|string',
            'phone_number' => 'required|string',
            'description' => 'nullable|string',
            'fund_needed' => ['integer','required' , Rule::in(Companies::FUNDS)],
            'anual_income' => ['integer','required' , Rule::in(Companies::INCOMES)],
            'profit' => ['integer','required' , Rule::in(Companies::PROFITS)],
            'bounced_check' => ['integer','required' , Rule::in(Companies::BOUNCED_CHECK_STATUSES)],
        ]);
        $user = auth()->user();
        if (!$company = $user->companies()->where('uuid', $uuid)->first()) {
            return response()->json([
                'message' => 'company not found'
            ], 404);
        }

        $company->update($data);

        return $company;
     }

     public function delete(Request $request, $uuid)
     {
         $user = auth()->user();
         if (!$company = $user->companies()->where('uuid', $uuid)->first()) {
             return response()->json([
                 'message' => 'company not found'
             ], 404);
         }
 
         $company->delete();
 
         return $company;
     }

}
