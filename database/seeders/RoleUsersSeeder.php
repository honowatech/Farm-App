<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crée un compte de démonstration par profil.
 * Mot de passe commun : 11111111 (uniquement défini à la création).
 */
class RoleUsersSeeder extends Seeder
{
    /**
     * @var array<string, array{name: string, email: string}>
     */
    private array $accounts = [
        'superadmin' => ['name' => 'Super Administrateur', 'email' => 'superadmin@farmapp.test'],
        'admin' => ['name' => 'Administrateur FarmApp', 'email' => 'admin@farmapp.test'],
        'gestionnaire' => ['name' => 'Gestionnaire de ferme', 'email' => 'gestionnaire@farmapp.test'],
        'agent' => ['name' => 'Agent de terrain', 'email' => 'agent@farmapp.test'],
        'veterinaire' => ['name' => 'Dr Vétérinaire', 'email' => 'veterinaire@farmapp.test'],
        'magasinier' => ['name' => 'Magasinier', 'email' => 'magasinier@farmapp.test'],
        'commercial' => ['name' => 'Commercial', 'email' => 'commercial@farmapp.test'],
        'comptable' => ['name' => 'Comptable', 'email' => 'comptable@farmapp.test'],
        'caissier' => ['name' => 'Caissier', 'email' => 'caissier@farmapp.test'],
        'rh' => ['name' => 'Responsable RH', 'email' => 'rh@farmapp.test'],
        'client' => ['name' => 'Client de démonstration', 'email' => 'client@farmapp.test'],
    ];

    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo'],
            [
                'name' => 'Ferme de démonstration',
                'status' => 'active',
                'is_demo' => true,
                'demo_expires_at' => now()->addDays(30),
            ]
        );
        $context = app(TenantContext::class);
        $context->set($tenant);

        try {
            foreach ($this->accounts as $role => $account) {
                $typeUser = Typeusers::firstOrCreate(['libelle' => $role]);

                $user = User::firstOrNew(['email' => $account['email']]);
                $user->name = $account['name'];
                $user->id_type_user = $typeUser->id;
                $user->statut = 1;

                if (! $user->exists) {
                    $user->password = Hash::make('11111111');
                }

                $user->save();

                if ($role !== 'superadmin') {
                    $tenant->users()->syncWithoutDetaching([$user->id => [
                        'role_key' => $role,
                        'status' => 'active',
                        'accepted_at' => now(),
                    ]]);
                }
            }

            // Rattache le compte client à une fiche client dédiée.
            $clientUser = User::where('email', $this->accounts['client']['email'])->first();

            if ($clientUser) {
                Client::firstOrCreate(
                    ['email' => $this->accounts['client']['email']],
                    [
                        'nom' => 'Client Démo FarmApp',
                        'telephone' => '690000000',
                        'adresse' => 'Douala',
                        'id_user' => $clientUser->id,
                        'statut' => 1,
                    ]
                );
            }
        } finally {
            $context->clear();
        }
    }
}
