<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateLoanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_loan_test()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['access-customer']
        );

        $response = $this->postJson("/api/create-loan", [
            "amount_required" => 10,
            "loan_terms" => 3
        ]);

        $response->assertJson([
            "message" => true,
            "loan" => true
        ]);

        $response->assertStatus(201);
    }

    public function test_create_loan_test_wrong_data()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['access-customer']
        );

        $response = $this->postJson("/api/create-loan", [
            "amount_required" => 10,
            "loan_tms" => 3
        ]);

        $response->assertJson([
            "message" => true,
            "errors" => true
        ]);

        $response->assertStatus(422);
    }

    public function test_create_loan_test_wrong_input_validation()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['access-customer']
        );

        $response = $this->postJson("/api/create-loan", [
            "amount_required" => 10,
            "loan_terms" => 36
        ]);

        $response->assertJson([
            "message" => true,
            "errors" => true
        ]);

        $response->assertStatus(422);
    }
}
