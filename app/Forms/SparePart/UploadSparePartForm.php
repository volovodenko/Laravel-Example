<?php

declare(strict_types = 1);

namespace App\Forms\SparePart;

use App\Forms\BaseForm;

class UploadSparePartForm extends BaseForm
{
    public function buildForm()
    {
        $this->add('city', 'text', [
            'rules'         => ['required', 'string', 'min:3', 'max:255'],
            'wrapper'       => ['class' => 'col-12 mb-3'],
            'label'         => trans('spare_part.forms.upload_xls.city.label'),
            'label_attr'    => ['class' => 'form-label fs-base'],
            'attr'          => ['class' => 'form-select'],
            'default_value' => auth()->user()->profile->city,
        ]);

        $this->add('file', 'file', [
            'rules' => [
                'required',
                'file',
                'mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel',
                'max:5120',
            ],
            'wrapper'        => false,
            'attr'           => ['class' => 'form-control', 'accept' => '.xls, .xlsx'],
            'label'          => trans('spare_part.forms.upload_xls.file.label'),
            'label_attr'     => ['class' => 'form-label fs-base'],
            'error_messages' => [
                'file.mimetypes' => trans('validation.single_file_mimes', ['values' => '.xls, .xlsx']),
            ],
        ]);

        $this->add('submit', 'submit', [
            'label'   => trans('spare_part.forms.upload_xls.submit'),
            'wrapper' => ['class' => 'col-12 pt-4 justify-content-end d-flex'],
            'attr'    => ['class' => 'btn btn-success mt-3 mt-sm-0'],
        ]);
    }
}
