<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\DynamicController;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends DynamicController
{
    protected $resourceName = "Metodos de Pago";
    protected $resourcePath = "Backend/PaymentMethods";
    protected $resourceRoute = "paymentMethods";

    public function index(Request $request)
    {
        $model = PaymentMethod::query()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->grid($model, $request->all(), [
            'enableSearch' => false
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return $this->form($paymentMethod);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required',
            'active' => 'required|boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('paymentMethods.index');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'payment_method' => 'required',
            'active' => 'required|boolean',
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('paymentMethods.index');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('paymentMethods.index');
    }
}
