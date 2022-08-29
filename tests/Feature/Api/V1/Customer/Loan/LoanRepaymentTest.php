<?php

namespace Tests\Feature\Api\V1\Customer\Loan;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoanRepaymentTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->hasLoans(1)->create();
    }

    public function test_customer_can_only_repay_if_loan_approved()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.customer.loans.repay',[
            'loan' => $this->user->loans[0]->id,
            'repayment' => $this->user->loans[0]->repayments[0]->id]),[
                'amount' => $this->user->loans[0]->repayments[0]->amount
            ]);
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'This loan is not approved yet.'
            ]);
    }
    
}
