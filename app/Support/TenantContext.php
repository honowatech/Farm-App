<?php

namespace App\Support;

use App\Models\Tenant;
use LogicException;

class TenantContext
{
    private ?Tenant $tenant = null;

    private ?int $membershipUserId = null;

    private ?string $membershipRole = null;

    public function set(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function tenant(): Tenant
    {
        if ($this->tenant === null) {
            throw new LogicException('A tenant context is required for this operation.');
        }

        return $this->tenant;
    }

    public function id(): string
    {
        return $this->tenant()->getKey();
    }

    public function hasTenant(): bool
    {
        return $this->tenant !== null;
    }

    public function setMembership(int $userId, string $role): void
    {
        $this->membershipUserId = $userId;
        $this->membershipRole = $role;
    }

    public function membershipRole(int $userId): ?string
    {
        return $this->membershipUserId === $userId
            ? $this->membershipRole
            : null;
    }

    public function clear(): void
    {
        $this->tenant = null;
        $this->membershipUserId = null;
        $this->membershipRole = null;
    }
}
