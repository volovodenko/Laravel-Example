<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Enums\UserProfileStatus;
use App\Models\User;
use App\Repositories\Criteria\CriteriaBuilder;
use App\Repositories\Dto\User\UserDto;
use App\Repositories\Dto\User\UserUpdateCommissionDto;

final class UserRepository extends BaseRepository
{
    public function create(UserDto $dto): bool
    {
        $user = $this->newModel();

        return $this->update($user, $dto);
    }

    public function update(User $user, UserDto $dto): bool
    {
        $user->fill($dto->toArray());
        $user->save();

        return true;
    }

    public function paginate(CriteriaBuilder $criteriaBuilder)
    {
        return $this->getBaseQuery($criteriaBuilder)
            ->orderBy('is_active', 'DESC')
            ->latest('id')
            ->paginate($this->perPage);
    }

    public function sellerSelectArray(): array
    {
        return $this->newModel()
            ->newQuery()
            ->without('wishList')
            ->sellers(UserProfileStatus::ACTIVE)
            ->get()
            ->mapWithKeys(fn (User $seller) => [$seller->id => $seller->fullName])
            ->all();
    }

    public function updateCommission(UserUpdateCommissionDto $dto)
    {
        $this->newModel()
            ->newQuery()
            ->where('default_commission', $dto->oldDefaultCommission())
            ->update([
                'default_commission' => $dto->newDefaultCommission(),
            ]);

        $this->newModel()
            ->newQuery()
            ->where('vip_commission', $dto->oldVipCommission())
            ->update([
                'vip_commission' => $dto->newVipCommission(),
            ]);
    }

    public function newModel(): User
    {
        return new User();
    }
}
