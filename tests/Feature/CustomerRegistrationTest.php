<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_customer()
    {

        $response = $this->postJson("/api/register", [
            "name" => "Test user",
            "email" => "test@gmail.com",
            "password" => "password",
            "password_confirmation" => "password"
        ]);

        $response->assertJson([
            "message" => true,
            "accessToken" => true
        ]);

        $response->assertStatus(201);
    }

    public function test_register_customer_with_wrong_input()
    {

        $response = $this->postJson("/api/register", [
            "name" => "Test user",
            "email" => "test@gmail.com",
            "password" => "passw",
            "password_confirmation" => "password"
        ]);

        $response->assertJson([
            "message" => true,
            "errors" => true
        ]);

        $response->assertStatus(422);
    }
}
