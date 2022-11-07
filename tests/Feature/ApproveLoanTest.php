<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApproveLoanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_approve_loan()
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
        ])->assertStatus(201);

        $this->actingAs(Admin::factory()->create(),"admin");

        $loan = Loan::first();

        $response = $this->postJson("/api/admin/approve-loan/{$loan->id}");

        $response->assertJson([
            "message" => true,
            "loan" => true,
            "terms" => true,
        ])->assertStatus(201);
    }

    public function test_approve_loan_wrong_data()
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
        ])->assertStatus(201);

        $this->actingAs(Admin::factory()->create(),"admin");

        $loan = Loan::first();

        $response = $this->postJson("/api/admin/approve-loan/8")->assertStatus(404);

    }
}
