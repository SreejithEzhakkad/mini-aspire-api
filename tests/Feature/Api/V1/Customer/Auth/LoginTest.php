<?php

namespace Tests\Feature\Api\V1\Customer\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use WithFaker;

    public function test_required_fields_for_registration()
    {
        $response = $this->postJson(route('customer.login'),[]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    public function test_customer_cannot_login_with_incorrect_email()
    {
        $email = fake()->safeEmail();
        
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password = fake()->password), 
        ]);

        $response = $this->postJson(route('customer.login'),[
            'email'                 => 'another@mail.com',
            'password'              => $password
            ]);
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Email & Password does not match with our record.'
            ]);
    }

    public function test_customer_cannot_login_with_incorrect_password()
    {
        $email = fake()->safeEmail();
        
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password = fake()->password), 
        ]);

        $response = $this->postJson(route('customer.login'),[
            'email'                 => $email,
            'password'              => 'another-apassword'
            ]);
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Email & Password does not match with our record.'
            ]);
    }

    public function test_customer_can_login_with_correct_credentials()
    {
        $email = fake()->safeEmail();
        
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password = fake()->password), 
        ]);

        $response = $this->postJson(route('customer.login'),[
            'email'                 => $email,
            'password'              => $password
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'You have been logged in successfully.'
            ]);
    }
    
}
