<?php

namespace App\Services;

use App\Models\Bill;

class BillService
{
    public function index()
    {
        return Bill::query()
            ->orderBy('id', 'desc')
            ->BillServiceget();
    }

    public function create($data)
    {
        return Bill::create($data);
    }

    public function show($id)
    {
        return Bill::findOrFail($id);
    }

    public function update(Bill $bill, $data)
    {
        return $bill->update($data);
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        return true;
    }
}
