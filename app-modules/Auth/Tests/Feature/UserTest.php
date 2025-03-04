<?php

use AppModules\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('user successfully registered', function () {
    $requestData = [
        'email' => 'test@test.com',
        'name' => 'Test',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];
    $response = $this->postJson('/api/auth/v1/register', $requestData);
    $response->assertStatus(201);
});

it('user successfully logged in', function () {
    /** @var User $user */
    $user = User::factory()->create(['password' => 'password']);

    $requestData = [
      'email' => $user->email,
      'password' => 'password',
    ];

    $response = $this->postJson('/api/auth/v1/login', $requestData);
    $response->assertStatus(200);
});
