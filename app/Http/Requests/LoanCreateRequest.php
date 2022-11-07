<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "amount_required" => "required|integer|min:10|max:100000",
            "loan_terms" => "required|integer|min:2|max:35"
        ];
    }

    public function attributes()
    {
        return [
            "amount_required" => "Loan Amount",
            "loan_terms" => "Loan terms"
        ];
    }

}
