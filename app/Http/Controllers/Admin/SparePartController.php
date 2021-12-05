<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Filters\Admin\SparePartFilter;
use App\Forms\Admin\SparePart\EditSparePartForm;
use App\Forms\Admin\SparePart\FilterSparePartForm;
use App\Forms\SparePart\AddSparePartForm;
use App\Models\SparePart;
use App\Repositories\Criteria\Common\WhereCriteria;
use App\Repositories\Criteria\Common\WithRelationCriteria;
use App\Repositories\Criteria\CriteriaBuilder;
use App\Repositories\Dto\SparePart\CreateSparePartByAdminDto;
use App\Repositories\Dto\SparePart\UpdateSparePartByAdminDto;
use App\Repositories\SparePartRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Kris\LaravelFormBuilder\FormBuilder;

class SparePartController
{
    public function __construct(
        private SparePartRepository $sparePartRepository,
        private UserRepository $userRepository,
        private FormBuilder $formBuilder,
    ) {
    }

    public function index()
    {
        $criteria = new CriteriaBuilder();
        $criteria->add(new WithRelationCriteria('photos'))
            ->add(new WithRelationCriteria('seller'))
            ->add(new SparePartFilter());

        $spareParts = $this->sparePartRepository->paginate($criteria);

        $filterSparePartForm = $this->formBuilder->create(FilterSparePartForm::class);

        return view('admin.spare_parts.index')
            ->with('filterSparePartForm', $filterSparePartForm)
            ->with('spareParts', $spareParts);
    }

    public function create()
    {
        $sellers = $this->userRepository->sellerSelectArray();

        $sparePartForm = $this->formBuilder->create(AddSparePartForm::class, [
            'method' => 'POST',
            'url'    => route('admin.spare_parts.store'),
            'model'  => $this->sparePartRepository->newModel(),
        ], ['sellers' => $sellers]);

        return view('admin.spare_parts.create_edit')
            ->with('title', trans('spare_part.forms.add.title'))
            ->with('sparePartForm', $sparePartForm);
    }

    public function store()
    {
        $form = $this->addSparePartForm();

        if (!$form->isValid()) {
            return $this->formErrorMessage($form);
        }

        $fields = $form->getFieldValues();

        $sparePartExistCriteriaBuilder = $this->sparePartExistBaseCriteria($fields['seller'], $fields['article_number']);

        if ($this->sparePartRepository->exists($sparePartExistCriteriaBuilder)) {
            return $this->articleNumberErrorMessage($form);
        }

        $sellerCriteriaBuilder = new CriteriaBuilder();
        $sellerCriteriaBuilder->add(new WhereCriteria('id', $fields['seller']));
        $seller = $this->userRepository->findOrFail($sellerCriteriaBuilder);

        unset($fields['seller']);

        $this->sparePartRepository->createOrUpdate(new CreateSparePartByAdminDto($seller, ...$fields));

        return redirect()->route('admin.spare_parts.index');
    }

    public function edit(SparePart $sparePart)
    {
        $sellers = $this->userRepository->sellerSelectArray();

        $sparePartForm = $this->formBuilder->create(EditSparePartForm::class, [
            'method' => 'PATCH',
            'url'    => route('admin.spare_parts.update', [$sparePart->id]),
            'model'  => $sparePart,
        ], [
            'sellers' => $sellers,
        ]);

        return view('admin.spare_parts.create_edit')
            ->with('title', trans('spare_part.forms.edit.title'))
            ->with('sparePartForm', $sparePartForm);
    }

    public function update(SparePart $sparePart)
    {
        $form = $this->addSparePartForm();

        if (!$form->isValid()) {
            return $this->formErrorMessage($form);
        }

        $fields = $form->getFieldValues();

        $sparePartExistCriteriaBuilder = $this->sparePartExistBaseCriteria($fields['seller'], $fields['article_number'])
            ->add(new WhereCriteria('id', '!=', $sparePart->id));

        if ($this->sparePartRepository->exists($sparePartExistCriteriaBuilder)) {
            return $this->articleNumberErrorMessage($form);
        }

        $this->sparePartRepository->update(new UpdateSparePartByAdminDto($sparePart, ...$fields));

        return redirect()->route('admin.spare_parts.index');
    }

    public function destroy(SparePart $sparePart)
    {
        $sparePart->selfDestroy();

        return back()->with('flash_success', trans('actions.success'));
    }

    public function disable(SparePart $sparePart)
    {
        $sparePart->disable();

        return back()->with('flash_success', trans('actions.success'));
    }

    public function enable(SparePart $sparePart)
    {
        $sparePart->enable();

        return back()->with('flash_success', trans('actions.success'));
    }

    private function sparePartExistBaseCriteria(string $sellerId, string $articleNumber): CriteriaBuilder
    {
        $sparePartExistCriteriaBuilder = new CriteriaBuilder();
        $sparePartExistCriteriaBuilder->add(new WhereCriteria('seller_id', $sellerId))
            ->add(new WhereCriteria('article_number', $articleNumber));

        return $sparePartExistCriteriaBuilder;
    }

    private function formErrorMessage(AddSparePartForm $form): RedirectResponse
    {
        return back()->withInput()
            ->withErrors($form->getErrors(), $form->getErrorBag())
            ->with('flash_danger', trans('actions.incorrect_data'));
    }

    private function articleNumberErrorMessage(AddSparePartForm $form): RedirectResponse
    {
        return back()->withInput()
            ->withErrors(
                [
                    'article_number' => trans('spare_part.validation.article_number.exists'),
                ],
                $form->getErrorBag()
            )->with('flash_danger', trans('actions.incorrect_data'));
    }

    private function addSparePartForm(): AddSparePartForm
    {
        $sellers = $this->userRepository->sellerSelectArray();

        $form = $this->formBuilder->create(AddSparePartForm::class, [
            'model' => $this->sparePartRepository->newModel(),
        ], ['sellers' => $sellers]);

        $form->validate();

        return $form;
    }
}
