<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->get('/test-security-headers', fn () => response('ok'));
    }

    public function test_security_headers_are_present(): void
    {
        $this->get('/test-security-headers')
            ->assertOk()
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
    }

    public function test_hsts_is_absent_over_http(): void
    {
        $this->get('/test-security-headers')
            ->assertOk()
            ->assertHeaderMissing('Strict-Transport-Security');
    }
}
