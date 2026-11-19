<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Produit;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientPortalController extends Controller
{
    /**
     * Résout la fiche client de l'utilisateur connecté et initialise le
     * contexte tenant pour que toutes les requêtes soient cloisonnées.
     */
    private function client(): Client
    {
        $client = Client::where('id_user', auth()->id())->first();

        abort_unless($client, 403, 'Aucune fiche client associée à ce compte.');

        if ($client->tenant_id) {
            app(TenantContext::class)->set($client->tenant);
        }

        return $client;
    }

    public function dashboard()
    {
        $client = $this->client();

        $stats = [
            ['label' => 'Commandes', 'value' => Commande::where('id_client', $client->id)->count(), 'icon' => 'shopping-bag', 'color' => 'primary'],
            ['label' => 'Commandes en cours', 'value' => Commande::where('id_client', $client->id)->whereIn('statut', ['confirmee', 'en_preparation', 'prete'])->count(), 'icon' => 'clock', 'color' => 'warning'],
            ['label' => 'Factures', 'value' => Facture::where('id_client', $client->id)->count(), 'icon' => 'file-text', 'color' => 'info'],
            ['label' => 'Total facturé', 'value' => number_format((float) Facture::where('id_client', $client->id)->sum('total_ttc'), 0, ',', ' ').' FCFA', 'icon' => 'credit-card', 'color' => 'success'],
        ];

        $commandes = Commande::where('id_client', $client->id)->latest()->take(5)->get();

        return view('client.pages.dashboard', compact('client', 'stats', 'commandes'));
    }

    public function catalogue()
    {
        $this->client();

        $produits = Produit::where('statut', 1)->orderBy('nom')->get();

        return view('client.pages.catalogue', compact('produits'));
    }

    public function commandeCreate()
    {
        $this->client();

        $cart = array_filter(session('cart', []), fn ($q) => (int) $q > 0);
        $produits = Produit::whereIn('id', array_keys($cart))->get()->keyBy('id');

        return view('client.pages.commande.create', compact('produits', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $this->client();

        $data = $request->validate([
            'id_produit' => ['required', 'integer', 'exists:produits,id'],
            'qte' => ['nullable', 'integer', 'min:1'],
        ]);

        $cart = session('cart', []);
        $id = $data['id_produit'];
        $cart[$id] = ($cart[$id] ?? 0) + ($data['qte'] ?? 1);
        session(['cart' => $cart]);

        return response()->json([
            'ok' => true,
            'message' => 'Produit ajouté au panier.',
            'cartCount' => $this->cartCount($cart),
        ]);
    }

    public function updateCart(Request $request)
    {
        $this->client();

        $data = $request->validate([
            'id_produit' => ['required', 'integer'],
            'qte' => ['required', 'integer', 'min:0'],
        ]);

        $cart = session('cart', []);
        $id = $data['id_produit'];
        $qte = (int) $data['qte'];

        if ($qte <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $qte;
        }

        session(['cart' => $cart]);

        $produit = Produit::find($id);
        $sousTotal = $produit ? (float) $produit->prix_unitaire * $qte : 0;

        $summary = $this->cartSummary($cart);
        $summary['sousTotal'] = $sousTotal;
        $summary['removed'] = $qte <= 0;

        return response()->json($summary);
    }

    public function removeFromCart(Request $request)
    {
        $this->client();

        $id = $request->input('id_produit');
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return response()->json($this->cartSummary($cart));
    }

    private function cartCount(array $cart): int
    {
        return array_sum(array_map('intval', $cart));
    }

    private function cartSummary(array $cart): array
    {
        $total = 0;

        if (count($cart) > 0) {
            $produits = Produit::whereIn('id', array_keys($cart))->get()->keyBy('id');

            foreach ($cart as $id => $qte) {
                $produit = $produits[$id] ?? null;

                if ($produit) {
                    $total += (float) $produit->prix_unitaire * (int) $qte;
                }
            }
        }

        return [
            'ok' => true,
            'cartCount' => $this->cartCount($cart),
            'total' => $total,
        ];
    }

    public function commandeStore(Request $request)
    {
        $client = $this->client();

        $cart = array_filter(session('cart', []), fn ($q) => (int) $q > 0);

        if (empty($cart)) {
            return redirect()->route('client.commande.create')->with('error', 'Votre panier est vide.');
        }

        // Prix recalculés côté serveur (jamais depuis le navigateur).
        $produits = Produit::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $lignes = [];
        $totalHt = 0;

        foreach ($cart as $id => $qte) {
            $qte = (int) $qte;

            $produit = $produits[$id] ?? null;

            if (! $produit) {
                continue;
            }

            $prix = (float) $produit->prix_unitaire;
            $totalLigne = $prix * $qte;
            $totalHt += $totalLigne;

            $lignes[] = [
                'id_produit' => $produit->id,
                'qte' => $qte,
                'prix_unitaire' => $prix,
                'total_ligne' => $totalLigne,
            ];
        }

        if (empty($lignes)) {
            return back()->with('error', 'Aucun produit valide dans la commande.');
        }

        $tva = 0;
        $totalTtc = $totalHt + $tva;

        $commande = DB::transaction(function () use ($client, $lignes, $totalHt, $tva, $totalTtc) {
            $commande = Commande::create([
                'matricule' => $this->nextMatricule(),
                'id_client' => $client->id,
                'statut' => Commande::STATUT_CONFIRMEE,
                'total_ht' => $totalHt,
                'tva' => $tva,
                'total_ttc' => $totalTtc,
                'id_saver' => auth()->id(),
                'date_commande' => now()->format('Y-m-d'),
            ]);

            foreach ($lignes as $ligne) {
                $commande->lignes()->create($ligne);
            }

            return $commande;
        });

        session()->forget('cart');

        return redirect()->route('client.commandes.show', $commande)
            ->with('message', 'Commande '.$commande->matricule.' enregistrée avec succès.');
    }

    public function commandes()
    {
        $client = $this->client();

        $commandes = Commande::with('lignes.produit')
            ->where('id_client', $client->id)
            ->latest()
            ->paginate(15);

        return view('client.pages.commande.index', compact('commandes'));
    }

    public function commandeShow(Commande $commande)
    {
        $client = $this->client();

        abort_unless($commande->id_client === $client->id, 403);

        $commande->load('lignes.produit');

        return view('client.pages.commande.show', compact('commande'));
    }

    public function factures()
    {
        $client = $this->client();

        $factures = Facture::where('id_client', $client->id)->latest('date_facturation')->paginate(15);

        return view('client.pages.facture.index', compact('factures'));
    }

    public function factureShow(Facture $facture)
    {
        $client = $this->client();

        abort_unless($facture->id_client === $client->id, 403);

        $facture->load('lignes_facture.produit', 'client_facture');

        return view('client.pages.facture.show', compact('facture'));
    }

    public function factureDownload(Facture $facture)
    {
        $client = $this->client();

        abort_unless($facture->id_client === $client->id, 403);

        $facture->load('lignes_facture.produit', 'client_facture');

        $pdf = Pdf::loadView('client.pages.facture.pdf', compact('facture'));

        return $pdf->download($facture->matricule.'.pdf');
    }

    public function profil()
    {
        $this->client();

        return view('client.pages.profil');
    }

    public function profilUpdate(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('message', 'Profil mis à jour.');
    }

    private function nextMatricule(): string
    {
        $year = now()->format('Y');
        $count = Commande::withTrashed()->whereYear('created_at', $year)->count() + 1;

        return 'CMD-'.$year.'-'.str_pad($count, 6, '0', STR_PAD_LEFT);
    }
}
