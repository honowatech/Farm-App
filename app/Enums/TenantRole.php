<?php

namespace App\Enums;

/**
 * Rôles d'un utilisateur au sein d'une entreprise (tenant_user.role_key).
 *
 * IMPORTANT : les valeurs doivent rester synchronisées avec les clés de
 * config('permissions.roles') qui définissent les permissions associées.
 */
enum TenantRole: string
{
    case Admin = 'admin';
    case Gestionnaire = 'gestionnaire';
    case Agent = 'agent';
    case Veterinaire = 'veterinaire';
    case Magasinier = 'magasinier';
    case Commercial = 'commercial';
    case Comptable = 'comptable';
    case Caissier = 'caissier';
    case Rh = 'rh';
    case Client = 'client';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}
