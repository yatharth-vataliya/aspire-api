<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanCreateRequest;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    public function store(LoanCreateRequest $request)
    {
        $user = $request->user();
        $validated = $request->safe()->merge([
            "user_id" => $user->id,
        ])->all();

        $loan = Loan::create($validated);

        return response()->json([
            "message" => "Loan request successfully submitted",
            "loan" => $loan
        ], Response::HTTP_CREATED);

    }

    public function getCurrentUserLoan(Request $request)
    {
        return response()->json([
            "message" => "List of current user Loans",
            "loans" => $request->user()->loans
        ]);
    }

    public function approveLoan(Request $request, Loan $loan)
    {
        // TODO : need to implement approve loan logic
    }
}
