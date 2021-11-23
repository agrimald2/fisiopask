<?php

namespace App\Domain\ClinicHistories;


use Illuminate\Support\Str;

class ClinicHistoriesRepo
{

    protected $formKeys;

    public function enableForms($formKeys)
    {
        foreach ($formKeys as $formKey) {
            if (method_exists(Forms::class, $formKey)) {
                $this->formKeys[] = $formKey;
            } else {
                logs()->error("Form $formKey not exists on ClinicHistories/Forms");
            }
        }
    }


    public function getFormTypes()
    {
        return $this->formKeys;
    }


    public function getFormTypesWithName()
    {
        $repo = $this;

        return collect($this->formKeys)
            ->map(function ($formKey) use ($repo) {
                $form = $repo->getForm($formKey);
                return [
                    'key' => $formKey,
                    'name' => $form['name'],
                    'fields' => $form['fields'],
                ];
            })
            ->pluck('name', 'key')
            ->toArray();
    }


    public function getFormFieldsWithEmptyValues($formKey)
    {
        $fields = $this->getForm($formKey)['fields'];

        return collect($fields)
            ->mapWithKeys(function ($fieldName) {
                $key = Str::slug($fieldName, '_');
                return [
                    $key => ""
                ];
            })
            ->toArray();
    }


    public function getFormName($formKey)
    {
        return $this->getForm($formKey)['name'];
    }


    protected function getForm($formKey)
    {
        return array_key_exists($formKey, $this->formKeys) == -1
            ? null
            : app()->call(
                [app(Forms::class), $formKey]
            );
    }
}
