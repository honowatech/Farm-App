<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MamoniService
{
    public function __construct(private SettingService $settings) {}

    /**
     * Retourne la configuration MaMoNi (modifiable via l'interface).
     */
    public function config(): array
    {
        return [
            'base_url' => rtrim((string) $this->settings->get('mamoni.base_url', 'https://console-v2.mamonipay.me/api'), '/'),
            'login' => (string) $this->settings->get('mamoni.login', ''),
            'password' => (string) $this->settings->get('mamoni.password', ''),
            'channel' => (string) $this->settings->get('mamoni.channel', 'SELLER_DIRECT'),
            'enabled' => (string) $this->settings->get('mamoni.enabled', '1') === '1',
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
        return Cache::remember('mamoni_access_token', now()->addMinutes(45), function () {
            $config = $this->config();
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($config['base_url'].'/v1/auth/login', [
                'login' => $config['login'],
                'password' => $config['password'],
            ]);

            return $response->successful() ? $response->json('data.access_token') : null;
        });
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

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post($config['base_url'].'/v1/payments/init', [
                'channel' => $config['channel'],
                'phone_number' => $phone,
                'amount' => $amount,
                'external_reference' => $externalRef,
                'client_name' => $clientName,
            ]);

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

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post($config['base_url'].'/v1/payments/verify/'.$gatewayReference);

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
}
