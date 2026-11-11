<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Services\MamoniService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    public function __construct(
        private MamoniService $mamoni,
        private SettingService $settings,
    ) {}

    /**
     * Page de configuration MaMoNi.
     */
    public function settings()
    {
        $config = $this->mamoni->config();

        return view('admin.pages.paiement.settings', compact('config'));
    }

    /**
     * Enregistre la configuration MaMoNi.
     */
    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'base_url' => 'required|url',
            'login' => 'required|string',
            'password' => 'nullable|string',
            'channel' => 'required|string',
            'enabled' => 'nullable',
        ]);

        $this->settings->setMany([
            'mamoni.base_url' => rtrim($data['base_url'], '/'),
            'mamoni.login' => $data['login'],
            'mamoni.password' => $data['password'] ?? '',
            'mamoni.channel' => $data['channel'],
            'mamoni.enabled' => $request->has('enabled') ? '1' : '0',
        ]);

        return redirect()->route('paiement.settings')->with('message', 'Configuration MaMoNi enregistrée avec succès.');
    }

    /**
     * Teste les identifiants MaMoNi sans les enregistrer (AJAX).
     */
    public function testConnection(Request $request)
    {
        $data = $request->validate([
            'base_url' => 'required|url',
            'login' => 'required|string',
            'password' => 'nullable|string',
        ]);

        $result = $this->mamoni->testCredentials($data['base_url'], $data['login'], $data['password'] ?? '');

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    /**
     * Liste des transactions de paiement.
     */
    public function index()
    {
        $transactions = PaymentTransaction::latest()->paginate(25);

        return view('admin.pages.paiement.index', compact('transactions'));
    }

    /**
     * Espace de test : initier un paiement réel.
     */
    public function test()
    {
        return view('admin.pages.paiement.test');
    }

    /**
     * Initie un paiement (AJAX).
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'amount' => 'required|integer|min:1',
            'client_name' => 'nullable|string|max:255',
        ]);

        $phone = $this->normalizePhone($request->input('phone_number'));

        if (! preg_match('/^2376\d{8}$/', $phone)) {
            return response()->json([
                'ok' => false,
                'message' => 'Numéro invalide. Format attendu : 2376XXXXXXXX (12 chiffres).',
            ], 422);
        }

        $externalRef = 'PAY-'.date('Ymd').'-'.strtoupper(Str::random(6));

        $result = $this->mamoni->initPayment(
            $phone,
            (int) $request->input('amount'),
            $externalRef,
            $request->input('client_name')
        );

        $transaction = PaymentTransaction::create([
            'amount' => (int) $request->input('amount'),
            'phone_number' => $phone,
            'external_reference' => $externalRef,
            'gateway_reference' => $result['data']['gateway_reference'] ?? null,
            'provider_slug' => $result['data']['payment_provider_slug'] ?? null,
            'status' => $result['ok'] ? 'pending' : 'failed',
            'client_name' => $request->input('client_name'),
            'response' => json_encode($result),
        ]);

        return response()->json([
            'ok' => $result['ok'],
            'transaction_id' => $transaction->id,
            'gateway_reference' => $transaction->gateway_reference,
            'status' => $transaction->status,
            'message' => $result['message'] ?? null,
        ], $result['ok'] ? 200 : 422);
    }

    /**
     * Vérifie le statut d'un paiement (AJAX / polling).
     */
    public function status(PaymentTransaction $transaction)
    {
        if (! $transaction->gateway_reference) {
            return response()->json(['final' => true, 'payment_status' => $transaction->status, 'receipt_url' => null]);
        }

        $result = $this->mamoni->verify($transaction->gateway_reference);

        if ($result['final']) {
            $transaction->update([
                'status' => $result['payment_status'] ?? 'failed',
                'receipt_url' => $result['data']['receipt']['receipt_url'] ?? null,
                'response' => json_encode($result['data']),
            ]);
        }

        return response()->json([
            'final' => $result['final'],
            'payment_status' => $result['final'] ? ($result['payment_status'] ?? 'failed') : 'pending',
            'receipt_url' => $transaction->receipt_url,
        ]);
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '237')) {
            return $phone;
        }

        if (str_starts_with($phone, '6') && strlen($phone) === 9) {
            return '237'.$phone;
        }

        return $phone;
    }
}
