<?php

declare(strict_types = 1);

namespace App\Forms\SparePart;

use App\Http\Resources\FileResource;

class EditSparePartForm extends AddSparePartForm
{
    public function buildForm()
    {
        parent::buildForm();

        $sparePart = $this->getData('sparePart');

        $this->modify('vendor_name', 'text', [
            'value' => $sparePart->vendor_name,
        ]);

        $this->modify('vendor_code', 'required_text', [
            'value' => $sparePart->vendor_code,
        ]);

        $this->modify('article_number', 'required_text', [
            'rules' => ['nullable'],
            'attr'  => ['disabled' => 'disabled'],
            'value' => $sparePart->article_number,
        ]);

        $this->modify('city', 'required_text', [
            'value' => $sparePart->city,
        ]);

        $this->modify('private_name', 'required_text', [
            'rules' => ['nullable'],
            'attr'  => ['disabled' => 'disabled'],
            'value' => $sparePart->private_name,
        ]);

        $this->modify('public_name', 'text', [
            'value' => $sparePart->public_name,
        ]);

        $this->modify('price', 'price', [
            'rules' => ['nullable'],
            'attr'  => ['disabled' => 'disabled', 'step' => 'any'],
            'value' => format_to_money($sparePart->price),
        ]);

        $this->modify('quantity', 'required_number', [
            'rules' => ['nullable'],
            'attr'  => ['disabled' => 'disabled'],
            'value' => $sparePart->quantity,
        ]);

        $this->modify('condition', 'required_select', [
            'default_value' => $sparePart->condition,
        ]);

        $this->modify('is_vat', 'checkbox', [
            'checked' => $sparePart->is_vat,
        ]);

        $this->modify('is_oversized', 'checkbox', [
            'checked' => $sparePart->is_oversized,
        ]);

        $this->modify('is_checked', 'checkbox', [
            'rules'   => ['nullable'],
            'attr'    => ['disabled' => 'disabled'],
            'checked' => $sparePart->is_checked,
        ]);

        $this->modify('weight', 'number', [
            'value' => $sparePart->weight,
        ]);

        $this->modify('height', 'number', [
            'value' => $sparePart->height,
        ]);

        $this->modify('width', 'number', [
            'value' => $sparePart->width,
        ]);

        $this->modify('depth', 'number', [
            'value' => $sparePart->depth,
        ]);

        $this->modify('description', 'textarea', [
            'value' => $sparePart->description,
        ]);

        $this->modify('photos', 'upload_file', [
            'value' => FileResource::collection($sparePart->photos),
        ]);
    }

    /**
     * Get all Request values from all fields, and nothing else.
     *
     * @param bool $with_nulls
     *
     * @return array
     */
    public function getFieldValues($with_nulls = true)
    {
        $fields = parent::getFieldValues($with_nulls);

        \Arr::forget($fields, ['article_number', 'private_name', 'price', 'quantity', 'is_checked']);

        return $fields;
    }
}
