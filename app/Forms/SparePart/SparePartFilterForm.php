<?php

declare(strict_types = 1);

namespace App\Forms\SparePart;

use App\Forms\BaseForm;

class SparePartFilterForm extends BaseForm
{
    protected $formOptions = [
        'autocomplete' => 'off',
        'class'        => 'row row-cols-lg-auto g-3 align-items-center mb-4',
        'method'       => 'GET',
    ];

    public function buildForm()
    {
        $this->add('vendor_name', 'text', [
            'attr'          => ['class' => 'form-control'],
            'label_attr'    => ['class' => 'form-label'],
            'label'         => trans('spare_part.filter.vendor_name'),
            'default_value' => $this->request->query('vendor_name'),
        ]);

        $this->add('city', 'text', [
            'attr'          => ['class' => 'form-control'],
            'label_attr'    => ['class' => 'form-label'],
            'label'         => trans('spare_part.forms.add.city'),
            'default_value' => $this->request->query('city'),
        ]);

        $this->add('condition', 'select', [
            'attr'       => ['class' => 'form-select'],
            'label_attr' => ['class' => 'form-label'],
            'choices'    => trans('spare_part.filter.condition'),
            'label'      => trans('spare_part.forms.add.condition'),
            'selected'   => $this->request->query('condition'),
        ]);

        $this->add('is_checked', 'select', [
            'attr'       => ['class' => 'form-select'],
            'label_attr' => ['class' => 'form-label'],
            'choices'    => trans('spare_part.filter.is_checked'),
            'label'      => trans('spare_part.forms.add.is_checked'),
            'selected'   => $this->request->query('is_checked'),
        ]);

        $this->add('text', 'hidden', [
            'default_value' => $this->request->query('text'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('spare_part.filter.submit'),
            'attr'  => ['class' => 'btn btn-success me-0 me-sm-2'],
        ]);

        $this->add('reset', 'reset', [
            'label' => trans('buttons.reset'),
            'attr'  => [
                'class'   => 'btn btn-secondary mt-2 mt-sm-0',
                'onclick' => 'location = "' . route('public.search', ['text' => $this->request->query('text')]) . '"',
            ],
        ]);
    }
}
