<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public function index()
    {
        return Company::query()
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create($data)
    {
        return Company::create($data);
    }

    public function show($id)
    {
        return Company::findOrFail($id);
    }

    public function update(Company $company, $data)
    {
        return $company->update($data);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return true;
    }
}