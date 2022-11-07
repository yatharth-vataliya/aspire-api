<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(AdminLoginRequest $adminLoginRequest)
    {
        return $adminLoginRequest->authenticate();
    }
}
