<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admins;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 管理者がログインできることを確認する
     */
    public function testAdminLogin()
    {
        $this->seed();

        $validPayload = [
            'login_id' => 'system',
            'password' => '!YuMeMi+',
        ];

        $validResponse = $this->post('/api/management/login', $validPayload);

        $validResponse->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);

        $invalidPayload = [
            'login_id' => 'system',
            'password' => 'WrongPassword',
        ];

        $invalidResponse = $this->post('/api/management/login', $invalidPayload);

        $invalidResponse->assertStatus(401);
    }

    /**
     * 管理者APIの認証テスト
     */
    public function testAuthorization()
    {
        $this->seed();

        $loginPayload = [
            'login_id' => 'system',
            'password' => '!YuMeMi+',
        ];

        $loginResponse = $this->post('/api/management/login', $loginPayload);
        $bearerToken = 'Bearer ' . $loginResponse->json()['access_token'];

        $validRequestHeader = [
            'Authorization' => $bearerToken,
            'Accept' => 'application/json',
        ];
        $invalidRequestHeader = [
            'Authorization' => 'null',
            'Accept' => 'application/json',
        ];

        $testEndpoints = [
            '/api/management/account',
            '/api/management/restaurant',
        ];
        
        foreach ($testEndpoints as $endpoint) {
            $invalidResponse = $this->withHeaders($invalidRequestHeader)->get($endpoint);
            $invalidResponse->assertStatus(401);
        }
        
        foreach ($testEndpoints as $endpoint) {
            $validResponse = $this->withHeaders($validRequestHeader)->get($endpoint);
            $validResponse->assertStatus(200);
        }
    }

    /**
     * 管理者のロールによるアクセス制限テスト
     */
    public function testRoleRestriction()
    {
        $this->seed();
        
        $counterAccountLoginID = Admins::where('admin_role', 'counter')->first()->login_id;
        $counterLoginPayload = [
            'login_id' => $counterAccountLoginID,
            'password' => '!YuMeMi+',
        ];
        $counterLoginResponse = $this->post('/api/management/login', $counterLoginPayload);
        $counterBearerToken = 'Bearer ' . $counterLoginResponse->json()['access_token'];
        $counterRequestHeader = [
            'Authorization' => $counterBearerToken,
            'Accept' => 'application/json',
        ];

        $testEndpoints = [
            '/api/management/account',
            '/api/management/restaurant',
        ];

        foreach ($testEndpoints as $endpoint) {
            $counterResponse = $this->withHeaders($counterRequestHeader)->get($endpoint);
            $counterResponse->assertStatus(403);
        }
    }
}
