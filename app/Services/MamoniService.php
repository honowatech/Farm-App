<?php

namespace App\Services;

use App\Models\PlatformSetting;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MamoniService
{
    private const TOKEN_CACHE_KEY = 'mamoni_access_token';

    /**
     * Retourne la configuration MaMoNi (globale plateforme, modifiable par le
     * super administrateur via l'écran dédié).
     */
    public function config(): array
    {
        return [
            'base_url' => rtrim((string) PlatformSetting::get('mamoni.base_url', 'https://console-v2.mamonipay.me/api'), '/'),
            'login' => (string) PlatformSetting::get('mamoni.login', ''),
            'password' => (string) PlatformSetting::get('mamoni.password', ''),
            'channel' => (string) PlatformSetting::get('mamoni.channel', 'SELLER_DIRECT'),
            'enabled' => PlatformSetting::get('mamoni.enabled', '1') === '1',
        ];
    }

    public function isConfigured(): bool
    {
        $config = $this->config();

        return $config['enabled'] && $config['login'] !== '' && $config['password'] !== '';
    }

    /**
     * Authentifie et met le token JWT en cache (45 min < 50 min d'expiration).
     */
    public function token(): ?string
    {
        $config = $this->config();
        $fingerprint = hash('sha256', implode("\0", [
            $config['base_url'],
            $config['login'],
            $config['password'],
        ]));
        $cached = Cache::get(self::TOKEN_CACHE_KEY);

        if (
            is_array($cached)
            && ($cached['fingerprint'] ?? null) === $fingerprint
            && is_string($cached['token'] ?? null)
            && $cached['token'] !== ''
        ) {
            return $cached['token'];
        }

        Cache::forget(self::TOKEN_CACHE_KEY);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($config['base_url'].'/v1/auth/login', [
            'login' => $config['login'],
            'password' => $config['password'],
        ]);
        $token = $response->successful() ? $response->json('data.access_token') : null;

        if (is_string($token) && $token !== '') {
            Cache::put(self::TOKEN_CACHE_KEY, [
                'fingerprint' => $fingerprint,
                'token' => $token,
            ], now()->addMinutes(45));

            return $token;
        }

        return null;
    }

    /**
     * Initie un paiement (déclenche le prompt USSD).
     */
    public function initPayment(string $phone, int $amount, string $externalRef, ?string $clientName = null): array
    {
        $config = $this->config();
        $token = $this->token();

        if (! $token) {
            return [
                'ok' => false,
                'status' => 401,
                'message' => 'Impossible de s\'authentifier auprès de MaMoNi. Vérifiez les identifiants.',
            ];
        }

        $payload = [
            'channel' => $config['channel'],
            'phone_number' => $phone,
            'amount' => $amount,
            'external_reference' => $externalRef,
            'client_name' => $clientName,
        ];
        $response = $this->postWithToken($config['base_url'].'/v1/payments/init', $payload, $token);

        if ($response->status() === 401) {
            Cache::forget(self::TOKEN_CACHE_KEY);
            $refreshedToken = $this->token();

            if ($refreshedToken) {
                $response = $this->postWithToken($config['base_url'].'/v1/payments/init', $payload, $refreshedToken);
            }
        }

        $ok = $response->successful() || $response->status() === 201;

        return [
            'ok' => $ok,
            'status' => $response->status(),
            'data' => $response->json('data'),
            'message' => $ok ? null : ($response->json('message') ?? $response->body()),
        ];
    }

    /**
     * Vérifie le statut d'un paiement (polling).
     */
    public function verify(string $gatewayReference): array
    {
        $config = $this->config();
        $token = $this->token();

        if (! $token) {
            return ['status' => 401, 'final' => false, 'payment_status' => null, 'data' => null];
        }

        $url = $config['base_url'].'/v1/payments/verify/'.$gatewayReference;
        $response = $this->postWithToken($url, [], $token);

        if ($response->status() === 401) {
            Cache::forget(self::TOKEN_CACHE_KEY);
            $refreshedToken = $this->token();

            if ($refreshedToken) {
                $response = $this->postWithToken($url, [], $refreshedToken);
            }
        }

        $data = $response->json('data');
        $paymentStatus = $data['status'] ?? null;
        $finalStatuses = ['successful', 'failed', 'cancelled', 'expired'];

        return [
            'status' => $response->status(),
            'final' => in_array($paymentStatus, $finalStatuses, true),
            'payment_status' => $paymentStatus,
            'data' => $data,
        ];
    }

    /**
     * Teste la connexion avec des identifiants donnés (sans les enregistrer).
     */
    public function testCredentials(string $baseUrl, string $login, string $password): array
    {
        $baseUrl = rtrim($baseUrl, '/');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post($baseUrl.'/v1/auth/login', [
                'login' => $login,
                'password' => $password,
            ]);
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => 'Service injoignable : '.$e->getMessage()];
        }

        if ($response->successful() && $response->json('data.access_token')) {
            return ['ok' => true, 'message' => 'Connexion réussie.'];
        }

        $message = $response->json('message') ?? 'identifiants invalides ou service indisponible';

        return ['ok' => false, 'message' => 'Échec de connexion : '.$message];
    }

    private function postWithToken(string $url, array $payload, string $token): Response
    {
        return Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post($url, $payload);
    }
}
