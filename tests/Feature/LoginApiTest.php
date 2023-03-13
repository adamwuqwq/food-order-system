<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    /**
     * superuserがログインできることを確認する
     */
    public function testUserLogin()
    {
        $payload = [
            'login_id' => 'system',
            'password' => '!YuMeMi+',
        ];
    
        $response = $this->post('/api/management/login', $payload);
    
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                 ]);
    }

    /**
     * ログインIDが間違っている場合、ログインできないことを確認する
     */
    public function testInvalidWrongCredential()
    {
        $payload = [
            'login_id' => 'system',
            'password' => 'WrongPassword',
        ];

        $response = $this->post('/api/management/login', $payload);

        $response->assertStatus(401);
    }
}
