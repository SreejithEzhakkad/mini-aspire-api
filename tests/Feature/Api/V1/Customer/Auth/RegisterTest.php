<?php

namespace Tests\Feature\Api\V1\Customer\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use WithFaker;

    public function test_required_fields_for_registration()
    {
        $response = $this->postJson(route('v1.customer.register'),[]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'name'     => ['The name field is required.'],
                    'email'    => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    public function test_password_confirmation_should_be_match()
    {
        $response = $this->postJson(route('v1.customer.register'),[
            'name'                  => fake()->name(),
            'email'                 => fake()->safeEmail(),
            'password'              => fake()->password(8,20),
            'password_confirmation' => 'incorrect-password'
            ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'password' => ['The password confirmation does not match.']
                ]
            ]);
    }

    public function test_customer_can_register_with_valid_inputs()
    {
        $password = fake()->password(8,20);
        $response = $this->postJson(route('v1.customer.register'),[
            'name'                  => fake()->name(),
            'email'                 => fake()->safeEmail(),
            'password'              => $password,
            'password_confirmation' => $password
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'You have been registered successfully.'
            ]);
    }

    public function test_customer_cannot_register_with_existing_email()
    {
        $user = User::factory()->create();
        
        $password = fake()->password(8,20);
        $response = $this->postJson(route('v1.customer.register'),[
            'name'                  => fake()->name(),
            'email'                 => $user->email,
            'password'              => $password,
            'password_confirmation' => $password
            ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'email'    => ['The email has already been taken.'],
                ]
            ]);
    }
    
}
