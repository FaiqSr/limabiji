<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        $email = 'admin@limabiji.com';

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => $email,
                'password' => 'wrong-password',
            ])->assertStatus(302);
        }

        $response = $this->post('/admin/login', [
            'email' => $email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }

    public function test_rate_limit_is_keyed_per_email(): void
    {
        $blockedEmail = 'admin@limabiji.com';
        $otherEmail = 'someone-else@example.com';

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => $blockedEmail,
                'password' => 'wrong-password',
            ])->assertStatus(302);
        }

        $this->post('/admin/login', [
            'email' => $blockedEmail,
            'password' => 'wrong-password',
        ])->assertStatus(429);

        // A different e-mail address gets its own bucket.
        $this->post('/admin/login', [
            'email' => $otherEmail,
            'password' => 'wrong-password',
        ])->assertStatus(302);
    }
}
