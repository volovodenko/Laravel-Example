<?php

declare(strict_types = 1);

namespace App\Forms\SparePart;

use App\Forms\BaseForm;
use App\Models\Enums\SparePartCondition;

class AddSparePartForm extends BaseForm
{
    public function buildForm()
    {
        $this->add('vendor_name', 'text', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.vendor_name'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('vendor_code', 'required_text', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.vendor_code'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('article_number', 'required_text', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.article_number'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('city', 'required_text', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.city'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('private_name', 'required_text', [
            'wrapper'    => ['class' => 'col-sm-12 mb-3'],
            'label'      => trans('spare_part.forms.add.private_name'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('public_name', 'text', [
            'wrapper'    => ['class' => 'col-sm-12 mb-3'],
            'label'      => trans('spare_part.forms.add.public_name'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('price', 'price', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'attr'       => ['step' => 'any'],
            'label'      => trans('spare_part.forms.add.price'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('quantity', 'required_number', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.quantity'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('condition', 'required_select', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'choices'    => SparePartCondition::getSelectArray(),
            'label'      => trans('spare_part.forms.add.condition'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('is_vat', 'checkbox', [
            'wrapper'    => ['class' => null],
            'label'      => trans('spare_part.forms.add.is_vat'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('is_oversized', 'checkbox', [
            'wrapper'    => ['class' => null],
            'label'      => trans('spare_part.forms.add.is_oversized'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('is_checked', 'checkbox', [
            'wrapper'    => ['class' => null],
            'label'      => trans('spare_part.forms.add.is_checked'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('weight', 'number', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.weight'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('height', 'number', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.height'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('width', 'number', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.width'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('depth', 'number', [
            'wrapper'    => ['class' => 'col-sm-6 mb-3'],
            'label'      => trans('spare_part.forms.add.depth'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('description', 'textarea', [
            'wrapper' => ['class' => 'form-group mb-4'],
            'attr'    => [
                'style' => 'resize: none;',
            ],
            'label'      => trans('spare_part.forms.add.description'),
            'label_attr' => ['class' => 'form-label'],
        ]);

        $this->add('photos', 'upload_file', [
            'wrapper'    => ['class' => 'col-sm-12 mb-3'],
            'label'      => trans('spare_part.forms.add.photos'),
            'label_attr' => ['class' => 'form-label'],
            'url'        => route('public.files.upload'),
            'multiple'   => true,
            'accept'     => 'image/jpg,image/jpeg,image/png',
        ]);

        $this->add('submit', 'submit', [
            'label'   => trans('spare_part.forms.add.submit'),
            'wrapper' => ['class' => 'd-flex justify-content-end'],
            'attr'    => ['class' => 'btn btn-success mt-3 mt-sm-0'],
        ]);
    }
}
