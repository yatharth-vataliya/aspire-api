<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AdminLoginRequest extends FormRequest
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
            "email" => "required|string|email|max:100",
            "password" => "required|string"
        ];
    }

    public function attributes()
    {
        return [
            "email" => "Admin User Email",
            "password" => "Password"
        ];
    }

    public function authenticate()
    {
        $validated = $this->safe()->all();
        $admin = Admin::where("email", $validated["email"])->first();
        if (empty($admin) || !Hash::check($validated["password"], $admin->password)) {
            return response()->json([
                "message" => "User or Password mismatched, Please try again",
            ], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }

        $admin->tokens()->delete();

        return response()->json([
            "message" => "Credentials are matched, Please copy this accessToken for future API calls",
            "accessToken" => $admin->createToken("LoginToken", ['access-customer'])->plainTextToken
        ], Response::HTTP_CREATED);
    }
}
