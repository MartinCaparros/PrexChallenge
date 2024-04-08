<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(
            User::create(['email' => 'admintest@mail.com', 'password' => Hash::make('secret')]),
            ['api']
        );
    }

    /** @test */
    public function it_returns_unauthorized_with_incorrect_credentials()
    {
        $user = User::where('email', 'admintest@mail.com')->first();
        $this->actingAs($user);
        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => 'incorrect-password'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'UNAUTHORIZED'
            ]);
    }

    /** @test */
    public function it_returns_user_with_token_on_successful_login()
    {
        $user = User::where('email', 'admintest@mail.com')->first();
        $this->actingAs($user);

        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'email',
                'token'
            ]);
    }

    public function test_user_registration_with_valid_data()
    {
        $userData = [
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
        ];
        $response = $this->postJson('/api/user/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'email',
                'created_at'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    public function test_user_registration_with_invalid_data()
    {
        $response = $this->postJson('/api/user/register', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
