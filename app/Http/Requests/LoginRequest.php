<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginRequest extends FormRequest
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
            "email" => "required|string|max:100",
            "password" => "required|string"
        ];
    }

    public function attributes()
    {
        return [
            "email" => "User Email",
            "password" => "Password"
        ];
    }

    public function authenticate()
    {
        $validated = $this->safe()->all();
        $user = User::where("email", $validated["email"])->first();
        if(empty($user) || !Hash::check($validated["password"], $user->password)){
            return response()->json([
                "message" => "User or Password mismatched, Please try again",
            ], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }

        $user->tokens()->delete();

        return response()->json([
            "message" => "Credentials are matched, Please copy this accessToken for future API calls",
            "accessToken" => $user->createToken("LoginToken")->plainTextToken
        ], 200);

    }
}
