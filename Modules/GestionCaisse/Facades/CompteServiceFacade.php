<?php

namespace Modules\GestionCaisse\Facades;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;
use Modules\GestionCaisse\Entities\Compte;
use Modules\GestionCaisse\Entities\Devise;
use Modules\GestionCaisse\Entities\Transaction;
use Modules\GestionCaisse\Entities\Transfert;

class CompteServiceFacade extends Facade
{
    /**
     * Liste des comptes avec pagination.
     *
     * @param  int  $pagination  Le nombre d'éléments à afficher par page (par défaut 20).
     * @return LengthAwarePaginator La collection paginée des comptes.
     */
    public static function listComptes($pagination = 20)
    {
        return Compte::paginate($pagination);
    }

    /**
     * Création d'un compte.
     *
     * @param  array  $data  Les données pour créer le compte (type_account, nom_caisse, nom_banque, etc.).
     * @return Compte L'instance du compte créé.
     */
    public static function createCompte(array $data)
    {
        // Si le type de compte est "caisse", on définit le nom comme celui de la caisse, sinon null
        $data['nom'] = $data['type_account'] === 'caisse' ? $data['nom_caisse'] : null;
        // Si le type de compte est "banque", on définit le nom de la banque, sinon null
        $data['nom_banque'] = $data['type_account'] === 'banque' ? $data['nom_banque'] : null;
        // Initialisation du solde du compte à 0
        $data['initial_balance'] = 0;

        return Compte::create($data);  // Création et retour du compte
    }

    /**
     * Mise à jour d'un compte.
     *
     * @param  int  $id  L'ID du compte à mettre à jour.
     * @param  array  $data  Les nouvelles données à appliquer au compte.
     * @return Compte L'instance du compte mis à jour.
     */
    public static function updateCompte($id, array $data)
    {
        $compte = Compte::findOrFail($id);  // Recherche du compte par ID
        $compte->update($data);  // Mise à jour des données du compte

        return $compte;  // Retour du compte mis à jour
    }

    /**
     * Suppression d'un compte.
     *
     * @param  int  $id  L'ID du compte à supprimer.
     * @return void Aucun retour (la suppression est effectuée sans retour explicite).
     */
    public static function deleteCompte($id)
    {
        $compte = Compte::findOrFail($id);  // Recherche du compte par ID
        $compte->delete();  // Suppression du compte
    }

    /**
     * Liste des devises avec pagination.
     *
     * @param  int  $pagination  Le nombre d'éléments à afficher par page (par défaut 20).
     * @return LengthAwarePaginator La collection paginée des devises.
     */
    public static function listDevises($pagination = 20)
    {
        return Devise::paginate($pagination);
    }

    /**
     * Création d'une devise.
     *
     * @param  array  $data  Les données pour créer la devise (code, nom, etc.).
     * @return Devise L'instance de la devise créée.
     */
    public static function createDevise(array $data)
    {
        // Normalisation du code de la devise en majuscules
        $data['code'] = strtoupper($data['code']);

        return Devise::create($data);  // Création et retour de la devise
    }

    /**
     * Mise à jour d'une devise.
     *
     * @param  int  $id  L'ID de la devise à mettre à jour.
     * @param  array  $data  Les nouvelles données à appliquer à la devise.
     * @return Devise L'instance de la devise mise à jour.
     */
    public static function updateDevise($id, array $data)
    {
        // Recherche de la devise par ID
        $devise = Devise::findOrFail($id);

        // Normalisation du code en majuscules
        $data['code'] = strtoupper($data['code']);

        // Mise à jour de la devise avec les nouvelles données
        $devise->update($data);

        return $devise;  // Retour de la devise mise à jour
    }

    /**
     * Suppression d'une devise.
     *
     * @param  int  $id  L'ID de la devise à supprimer.
     * @return void Aucun retour (la suppression est effectuée sans retour explicite).
     */
    public static function deleteDevise($id)
    {
        // Recherche de la devise par ID
        $devise = Devise::findOrFail($id);

        // Suppression de la devise
        $devise->delete();
    }

    /**
     * Liste des transferts avec pagination.
     *
     * @param  int  $pagination  Le nombre d'éléments à afficher par page (par défaut 20).
     * @return LengthAwarePaginator La collection paginée des transferts.
     */
    public static function listTransferts($pagination = 20)
    {
        return Transfert::paginate($pagination);
    }

    /**
     * Création d'un transfert.
     *
     * @param  array  $data  Les données pour créer un transfert (caisse_source, destination_caisse, montant, etc.).
     * @return void Aucun retour explicite, la transaction est effectuée dans la base de données.
     *
     * @throws \Exception Si le solde du compte source est insuffisant.
     */
    public static function createTransfert(array $data)
    {
        // Recherche des comptes source et destination
        $sourceAccount = Compte::find($data['caisse_source']);
        $destinationAccount = Compte::find($data['destination_caisse']);

        // Vérification du solde du compte source
        if ($sourceAccount->current_balance < $data['montant']) {
            throw new \Exception('Solde insuffisant.');  // Si solde insuffisant, on lance une exception
        }

        // Utilisation d'une transaction pour garantir l'intégrité des données
        \DB::transaction(function () use ($data, $sourceAccount, $destinationAccount) {
            // Mise à jour des soldes des comptes
            $sourceAccount->current_balance -= $data['montant'];
            $destinationAccount->current_balance += $data['montant'];
            $sourceAccount->save();
            $destinationAccount->save();

            // Création du transfert
            Transfert::create([
                'from_account_id' => $sourceAccount->id,
                'to_account_id' => $destinationAccount->id,
                'amount' => $data['montant'],
                'transfer_date' => $data['date'],
                'description' => $data['description'],
            ]);
        });
    }

    /**
     * Suppression d'un transfert.
     *
     * @param  int  $id  L'ID du transfert à supprimer.
     * @return void Aucun retour explicite, la transaction est effectuée dans la base de données.
     */
    public static function deleteTransfert($id)
    {
        // Utilisation d'une transaction pour garantir l'intégrité des données
        \DB::transaction(function () use ($id) {
            $transaction = Transfert::findOrFail($id);  // Recherche du transfert par ID
            $sourceAccount = Compte::findOrFail($transaction->from_account_id);
            $destinationAccount = Compte::findOrFail($transaction->to_account_id);

            // Restauration des soldes des comptes
            $sourceAccount->current_balance += $transaction->amount;
            $destinationAccount->current_balance -= $transaction->amount;

            $sourceAccount->save();
            $destinationAccount->save();

            // Suppression du transfert
            $transaction->delete();
        });
    }

    /**
     * Vérifie si le solde d'une caisse est suffisant pour une dépense.
     *
     * @param  int  $compteId  L'ID du compte à vérifier.
     * @param  float  $montant  Le montant de la dépense à effectuer.
     * @return bool True si le solde est suffisant, false sinon.
     */
    public static function verifierSoldeSuffisant($compteId, $montant, $id_depense)
    {
        $compte = Compte::findOrFail($compteId);

        if ($compte->current_balance < $montant) {
            return false;
        }

        // Subtract the amount from the current balance
        $compte->current_balance -= $montant;
        $compte->save();

        // Create a new transaction record
        Transaction::create([
            'compte_id' => $compteId,
            'montant' => $montant,
            'user_id' => auth()->user()->id,
            'id_depense' => $id_depense,
            'transaction_type' => 'sortie', // Updated key for transaction type
            'transaction_date' => now(), // Updated key for transaction date
        ]);

        return true;
    }
}
