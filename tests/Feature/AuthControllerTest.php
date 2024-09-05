<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

it('requires email and password to login', function () {

    $response = $this->postJson('/api/v1/auth/login', []);

    $response->assertStatus(422)
    ->assertJsonValidationErrors(['email', 'password']);
});

it('does not allow login with invalid credentials', function () {

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'invalid@example.com',
        'password' => 'invalidpassword',
    ]);

    $response->assertStatus(401)
    ->assertJson(['error' => 'Unauthorized']);
});

it('allows login with valid credentials', function () {

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
    ->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in',
    ]);
});
