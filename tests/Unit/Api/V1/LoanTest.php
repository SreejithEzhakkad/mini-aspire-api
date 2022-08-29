<?php

namespace Tests\Unit\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanTest extends TestCase
{
    use WithFaker,RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->hasLoans(1)->create();
        $this->admin = Admin::factory()->create();
    }

    public function test_request_a_loan_with_middleware()
    {
        $response = $this->postJson(route('v1.customer.loans.request'),[
            'amount' => fake()->numberBetween(10, 10000),
            'terms'  => fake()->numberBetween(1, 100)
            ]);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_create_a_loan()
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

    public function test_update_a_loan()
    {
        $response = $this->actingAs($this->admin)->postJson(route('v1.admin.loans.approve',[
            'loan' => $this->user->loans[0]->id
        ]));
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'The loan request has been approved successfully.'
            ]);
    }

    
}
