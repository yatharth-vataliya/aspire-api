<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function register(RegisterRequest $registerRequest)
    {
        $validated = $registerRequest->safe()->all();

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"])
        ]);

        return response()->json([
            "message" => "Registered successfully, Please copy following access token You can successfully access API endpoints with this token but if you lost it then just call login API and it will flush all previous token and create new one for you",
            "accessToken" => $user->createToken("LoginToken",["access-customer"])->plainTextToken
        ], Response::HTTP_CREATED);

    }
}
