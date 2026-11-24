<?php

namespace Tests\Feature;

use App\Http\Controllers\PaiementController;
use App\Models\PlatformSetting;
use App\Services\MamoniService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MamoniServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('platform_settings');
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        PlatformSetting::set('mamoni.base_url', 'https://console-v2.mamonipay.me/api');
        PlatformSetting::set('mamoni.login', 'merchant-old');
        PlatformSetting::set('mamoni.password', 'password-old');
        PlatformSetting::set('mamoni.channel', 'SELLER_DIRECT');
        PlatformSetting::set('mamoni.enabled', '1');
        Cache::flush();
    }

    public function test_cached_token_is_refreshed_when_mamoni_credentials_change(): void
    {
        $authenticationRequests = 0;
        $paymentAuthorization = null;

        Http::fake(function ($request) use (&$authenticationRequests, &$paymentAuthorization) {
            if (str_contains($request->url(), '/v1/auth/login')) {
                $authenticationRequests++;

                return Http::response([
                    'data' => ['access_token' => 'token-'.$authenticationRequests],
                ]);
            }

            $paymentAuthorization = $request->header('Authorization')[0] ?? null;

            return Http::response([
                'data' => [
                    'gateway_reference' => 'GATEWAY-123',
                    'payment_provider_slug' => 'mtn',
                ],
            ], 201);
        });

        $mamoni = new MamoniService;
        $this->assertSame('token-1', $mamoni->token());

        PlatformSetting::set('mamoni.login', 'merchant-new');
        PlatformSetting::set('mamoni.password', 'password-new');

        $result = $mamoni->initPayment('237690000000', 5000, 'SUB-TEST-123');

        $this->assertTrue($result['ok']);
        $this->assertSame(2, $authenticationRequests);
        $this->assertSame('Bearer token-2', $paymentAuthorization);
    }

    public function test_payment_initiation_reauthenticates_once_when_cached_token_is_rejected(): void
    {
        $authenticationRequests = 0;
        $paymentAttempts = 0;
        $paymentAuthorizations = [];

        Http::fake(function ($request) use (&$authenticationRequests, &$paymentAttempts, &$paymentAuthorizations) {
            if (str_contains($request->url(), '/v1/auth/login')) {
                $authenticationRequests++;

                return Http::response([
                    'data' => ['access_token' => 'token-'.$authenticationRequests],
                ]);
            }

            $paymentAttempts++;
            $paymentAuthorizations[] = $request->header('Authorization')[0] ?? null;

            if ($paymentAttempts === 1) {
                return Http::response(['message' => 'Token expiré'], 401);
            }

            return Http::response([
                'data' => ['gateway_reference' => 'GATEWAY-RETRIED'],
            ], 201);
        });

        $result = (new MamoniService)->initPayment('237690000000', 5000, 'SUB-TEST-456');

        $this->assertTrue($result['ok']);
        $this->assertSame(2, $authenticationRequests);
        $this->assertSame(2, $paymentAttempts);
        $this->assertSame(['Bearer token-1', 'Bearer token-2'], $paymentAuthorizations);
    }

    public function test_saving_mamoni_settings_with_a_blank_password_keeps_the_saved_password(): void
    {
        $controller = new PaiementController(new MamoniService);
        $request = Request::create('/admin/paiement/settings', 'POST', [
            'base_url' => 'https://console-v2.mamonipay.me/api',
            'login' => 'merchant-old',
            'password' => '',
            'channel' => 'SELLER_DIRECT',
            'enabled' => 'on',
        ]);

        $controller->updateSettings($request);

        $this->assertSame('password-old', PlatformSetting::get('mamoni.password'));
    }
}
