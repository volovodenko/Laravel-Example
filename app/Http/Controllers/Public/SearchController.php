<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Public;

use App\Enums\SortDirection;
use App\Filters\Public\SearchFilter;
use App\Forms\SparePart\SparePartFilterForm;
use App\Models\User;
use App\Repositories\Criteria\Common\OrderByCriteria;
use App\Repositories\Criteria\Common\WhereCriteria;
use App\Repositories\Criteria\Common\WithRelationCriteria;
use App\Repositories\Criteria\CriteriaBuilder;
use App\Repositories\Criteria\SparePart\SearchSortCriteria;
use App\Repositories\SparePartRepository;
use Kris\LaravelFormBuilder\FormBuilder;

class SearchController
{
    public function __construct(
        private FormBuilder $formBuilder,
        private SparePartRepository $sparePartRepository,
    ) {
    }

    public function search(User $seller)
    {
        $spareParts = $this->sparePartRepository->paginate($this->sparePartCriteriaFor($seller));

        return view('public.search_result')
            ->with('spareParts', $spareParts)
            ->with('seller', $seller->exists ? $seller : false)
            ->with('sellerSoldSparePartsCount', $this->soldSparePartsCountFor($seller))
            ->with('filterSparePartForm', $this->filterSparePartFormFor($seller));
    }

    private function sparePartCriteriaFor(User $seller)
    {
        $criteriaBuilder = $this->baseCriteria()
            ->add(new SearchSortCriteria())
            ->add(new SearchFilter());

        if ($seller->exists) {
            $seller->load('sellerReviews');

            return $criteriaBuilder->add(new WhereCriteria('seller_id', $seller->id));
        }

        return $criteriaBuilder->add(new WithRelationCriteria('seller.sellerReviews'));
    }

    private function baseCriteria(): CriteriaBuilder
    {
        $criteria = new CriteriaBuilder();

        return $criteria
            ->add(new WhereCriteria('spare_parts.is_active', true))
            ->add(new WithRelationCriteria('photos'))
            ->add(new WithRelationCriteria('seller.profile'))
            ->add(new OrderByCriteria('id', SortDirection::DESC()));
    }

    private function soldSparePartsCountFor(User $seller): int
    {
        return $seller->exists ? $this->sparePartRepository->soldSparePartsCountFor($seller) : 0;
    }

    private function filterSparePartFormFor(User $seller)
    {
        return !$seller->exists ? $this->formBuilder->create(SparePartFilterForm::class) : null;
    }
}
