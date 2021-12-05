<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Models\SparePart;
use App\Models\User;
use App\Repositories\Criteria\Contracts\CriteriaBuilder;
use App\Repositories\Dto\Contracts\CreateOrUpdateSparePart;
use App\Repositories\Dto\Contracts\SparePartFields;
use App\Repositories\Dto\Contracts\SparePartUnique;
use App\Repositories\Dto\Contracts\UpdateSparePart;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class SparePartRepository extends BaseRepository
{
    public function createOrUpdate(CreateOrUpdateSparePart $dto)
    {
        \DB::transaction(function () use ($dto) {
            $sparePart = $this->getSparePart($dto);

            if (!$sparePart->id) {
                $sparePart->seller()->associate($dto->seller());
            }

            $this->fillSparePart($sparePart, $dto);

            if (!empty($dto->photos())) {
                $sparePart->photos()->sync($dto->photos());
            }
        });
    }

    public function update(UpdateSparePart $dto)
    {
        \DB::transaction(function () use ($dto) {
            $sparePart = $dto->sparePart();
            $sparePart->fill($dto->fields());
            $sparePart->save();

            $sparePart->photos()->sync($dto->photos());
        });
    }

    public function paginate(?CriteriaBuilder $criteriaBuilder = null)
    {
        return $this->getBaseQuery($criteriaBuilder)
            ->latest('spare_parts.id')
            ->select('spare_parts.*')
            ->paginate($this->perPage);
    }

    public function soldSparePartsCountFor(User $seller): int
    {
        return \DB::table('order_spare_part', 'osp')
            ->whereIn('osp.order_id', function (QueryBuilder $query) use ($seller) {
                return $query->select('id')
                    ->from('orders', 'o')
                    ->where('seller_id', $seller->id)
                    ->where('status', OrderStatus::RECEIVED);
            })
            ->sum('quantity');
    }

    public function newModel(): SparePart
    {
        return new SparePart();
    }

    private function getSparePart(SparePartUnique $dto): SparePart
    {
        return $this->newModel()->firstOrNew([
            'seller_id'      => $dto->seller()->id,
            'article_number' => $dto->articleNumber(),
        ]);
    }

    private function fillSparePart(SparePart $sparePart, SparePartFields $dto)
    {
        $sparePart->fill($dto->fields());
        $sparePart->is_active = true;
        $sparePart->save();
    }
}
