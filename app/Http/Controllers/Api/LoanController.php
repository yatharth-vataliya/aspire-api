<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanCreateRequest;
use App\Models\Loan;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
            "loan" => $loan->refresh()
        ], Response::HTTP_CREATED);

    }

    public function getCurrentUserLoan(Request $request)
    {
        return response()->json([
            "message" => "List of current user Loans",
            "loans" => $request->user()->loans
        ], Response::HTTP_OK);
    }

    public function getAllLoan()
    {
        return response()->json([
            "message" => "List of All Loan",
            "loans" => Loan::with(["user:id,name,email"])->get()
        ], Response::HTTP_OK);
    }

    public function approveLoan(Request $request, Loan $loan)
    {
        if ($loan->loan_status === "APPROVED") {
            return response()->json([
                "message" => "Loan is already approved and repayments were generated previously",
            ], Response::HTTP_OK);
        }
        $loan->loan_status = "APPROVED";
        $loan->save();
        $terms = $loan->loan_terms;
        $termData = [
            "admin_id" => $request->user()->id,
            "user_id" => $loan->user_id,
            "loan_id" => $loan->id,
            "term_amount" => number_format((float)$loan->amount_required / $terms, 5)
        ];

        $createdTerms = [];
        for ($i = 1; $i <= $terms; $i++) {
            $createdTerms[] = Term::create([
                ...$termData,
                "term_pay_date" => $loan->created_at->add(($i * 7) . " days")->toDateString()
            ]);
        }

        return response()->json([
            "message" => "Loan approved and repayments are created",
            "loan" => $loan,
            "terms" => $createdTerms
        ], Response::HTTP_CREATED);
    }

    public function getLoan(Request $request, Loan $loan)
    {
        $canView = Gate::inspect("view", $loan);
        if ($canView->denied()) {
            return response()->json([
                "message" => "You don't have ownership of this Loan"
            ], Response::HTTP_FORBIDDEN);
        }
        return response()->json([
            "message" => "Here is your desired Loan",
            "loan" => $loan,
            "loan_terms" => $loan->terms()->get()
        ], Response::HTTP_OK);
    }

    public function repayLoanTerm(Request $request, Loan $loan)
    {

        $request->validate([
            "term_pay_date" => "required|date",
            "amount_to_pay" => "required|numeric|min:1"
        ]);
        $canPayLoan = Gate::inspect("payLoan", $loan);
        if ($canPayLoan->denied()) {
            return response()->json([
                "message" => "You don't have ownership of this Loan"
            ], Response::HTTP_FORBIDDEN);
        }
        if ($loan->loan_status !== "APPROVED" || $loan->is_loan_paid !== "PENDING") {
            $message = $loan->is_loan_paid == "PAID" ? "Loan is already paid no need to pay again" : "Loan is not approved by admin please contact your admin";
            return response()->json([
                "message" => $message
            ], Response::HTTP_FORBIDDEN);
        }

        $totalPaidAmount = $loan->terms()->where("payment_status", "PAID")->sum("term_amount_paid");

        $totalPayableAmount = $loan->amount_required;

        if (($request->amount_to_pay + $totalPaidAmount) >= $totalPayableAmount) {
            $loan->is_loan_paid = "PAID";
            $loan->save();
            $term = $loan->terms()->where("payment_status", "PENDING")->first();
            $term->term_amount_paid = $request->amount_to_pay;
            $term->save();
            $loan->terms()->update(["payment_status" => "PAID"]);
            return response()->json([
                "message" => "You paid all pending terms. so now no need to pay again for this loan",
                "loan" => $loan,
                "terms" => $loan->terms()->get()
            ], Response::HTTP_OK);
        }

        $term = $loan->terms()->whereDate("term_pay_date",$request->term_pay_date)->first();

        if ($term->payment_status !== "PENDING") {
            return response()->json([
                "message" => "This term ({$request->term_pay_date}) is already paid please select other term"
            ], Response::HTTP_FORBIDDEN);
        }

        $term->term_amount_paid = $request->amount_to_pay;
        $term->payment_status = "PAID";
        $term->save();

        return response()->json([
            "message" => "Loan term is paid successfully",
            "term" => $term
        ], Response::HTTP_OK);
    }

}
