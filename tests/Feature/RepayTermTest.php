<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RepayTermTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_repay_full_loan()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['access-customer']
        );

        $response = $this->postJson("/api/create-loan", [
            "amount_required" => 10,
            "loan_terms" => 3
        ]);

        $admin = Admin::factory()->create();
        $this->actingAs($admin,"admin");

        $loan = Loan::first();

        $response = $this->postJson("/api/admin/approve-loan/{$loan->id}");

        Sanctum::actingAs(
            $user,
            ['access-customer']
        );

        $this->postJson("/api/repay-loan-term/{$loan->id}",[
            "term_pay_date" => now()->toDateString(),
            "amount_to_pay" => 11
        ])->assertJson([
            "message" => true,
            "loan" => true,
            "terms" => true
        ])->assertStatus(200);

    }

    public function test_repay_term_only()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['access-customer']
        );

        $response = $this->postJson("/api/create-loan", [
            "amount_required" => 10,
            "loan_terms" => 3
        ]);

        $admin = Admin::factory()->create();
        $this->actingAs($admin,"admin");

        $loan = Loan::first();

        $response = $this->postJson("/api/admin/approve-loan/{$loan->id}")->assertStatus(201);

        Sanctum::actingAs(
            $user,
            ['access-customer']
        );

        $this->postJson("/api/repay-loan-term/{$loan->id}",[
            "term_pay_date" => now()->add("7 days")->toDateString(),
            "amount_to_pay" => 3
        ])->assertJson([
            "message" => true,
            "term" => true
        ])->assertStatus(200);

    }

    public function test_repay_without_approved()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['access-customer']
        );

        $response = $this->postJson("/api/create-loan", [
            "amount_required" => 10,
            "loan_terms" => 3
        ]);

        $admin = Admin::factory()->create();
        $this->actingAs($admin,"admin");

        $loan = Loan::first();

        $this->postJson("/api/repay-loan-term/{$loan->id}",[
            "term_pay_date" => now()->toDateString(),
            "amount_to_pay" => 11
        ])->assertJson([
            "message" => true,
        ])->assertStatus(403);

    }
}
