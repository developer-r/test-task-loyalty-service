<?php

namespace App\Repositories;

use App\Models\LoyaltyAccount;
use Illuminate\Database\Eloquent\Builder;

class LoyaltyAccountRepository
{
    private function builderByTypeAndId(string $type, int $id): Builder
    {
        return LoyaltyAccount::query()->where($type, '=', $id);
    }

    public function getByTypeAndId(string $type, int $id): ?LoyaltyAccount
    {
        return $this->builderByTypeAndId($type, $id)->first();
    }

    public function existByTypeAndId(string $type, int $id): bool
    {
        return $this->builderByTypeAndId($type, $id)->exists();
    }

    public function isActiveByTypeAndId(string $type, int $id): bool
    {
        return (bool) $this->getByTypeAndId($type, $id)?->active;
    }
}
