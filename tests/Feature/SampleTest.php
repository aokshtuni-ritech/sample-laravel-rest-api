<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_api_login_missing_fields(): void
    {
        $response = $this->json('POST', '/api/v1/login', []);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJson([
                'message' => "The email field is required. (and 1 more error)",
                'errors' => [
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                ]
            ]);
    }

    public function test_api_not_authenticated(): void
    {
        $response = $this->json('GET', '/api/v1/employees');

        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_api_as_logged_in(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->json('GET', '/api/v1/employees');

        $response
            ->assertStatus(Response::HTTP_OK);
    }
}
