<?php

namespace Tests\Feature\Api\V1\Customer\Loan;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoanRequestTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_required_fields_for_loan_request()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.customer.loans.request'),[]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'amount' => ['The amount field is required.'],
                    'terms' => ['The terms field is required.']
                ]
            ]);
    }

    public function test_customer_can_request_loan_with_valid_data()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.customer.loans.request'),[
            'amount' => fake()->numberBetween(10, 10000),
            'terms'  => fake()->numberBetween(1, 100)
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Your loan request has been sent successfully.'
            ]);
    }
}
