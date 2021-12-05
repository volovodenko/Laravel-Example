<?php

declare(strict_types = 1);

namespace App\Forms;

use App\Models\Contracts\ValidationRules;
use Kris\LaravelFormBuilder\Form;

abstract class BaseForm extends Form
{
    protected $formOptions = [
        'autocomplete' => 'off',
    ];

    protected function setupFieldOptions($name, &$options)
    {
        if (empty($options['rules'])) {
            $options['rules'] = $this->modelRules($name, $this->getModel());
        }

        parent::setupFieldOptions($name, $options);
    }

    private function modelRules(string $name, $model): array
    {
        if ($model instanceof ValidationRules) {
            return \Arr::get($model->validationRules(), $name, []);
        }

        return [];
    }
}
