<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_customer()
    {
        $user = User::factory()->create();

        $response = $this->postJson("/api/login", [
            "email" => $user->email,
            "password" => "password"
        ]);

        $response->assertJson([
            "message" => true,
            "accessToken" => true
        ]);

        $response->assertStatus(201);
    }

    public function test_login_customer_wrong_data()
    {

        $response = $this->postJson("/api/login", [
            "email" => "test@ail.com",
            "password" => "password"
        ]);

        $response->assertJson([
            "message" => true,
        ]);

        $response->assertStatus(203);
    }

    public function test_login_customer_wrong_input_validation()
    {

        $response = $this->postJson("/api/login", [
            "email" => "testl.com",
            "password" => "password"
        ]);

        $response->assertJson([
            "message" => true,
            "errors" => true
        ]);

        $response->assertStatus(422);
    }
}
