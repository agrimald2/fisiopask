<?php

namespace App\Domain\PaymentGateways;

use MercadoPago;

class MercadoPagoGateway
{
    public function __construct()
    {
        $token = env("MERCADOPAGO_ACCESS_TOKEN");
        MercadoPago\SDK::setAccessToken($token);
    }


    public function generate($reference, $price, $url)
    {
        /**
         * Generate a preferenceId that we will use later
         * in frontend mercadopago.v2.js
         */
        $preference = new MercadoPago\Preference();

        /**
         * Redirect urls
         * success, failure, pending
         */
        $preference->back_urls = [
            "success" => $url,
            "failure" => $url,
            "pending" => $url,
        ];

        /**
         * List of `MercadoPago\Item` class
         */

        $item = new MercadoPago\Item();
        $item->title = env('APP_NAME');
        $item->quantity = 1;
        $item->unit_price = $price;
        $preference->items = array($item);

        /**
         * Save preference and return id
         */
        $preference->external_reference = $reference;

        $preference->save();

        // Return URL
        return [
            $preference->id,
            $preference->init_point
        ];
    }


    public function get($id)
    {
        if (!$id) return null;

        $payment = MercadoPago\Payment::get($id);
        if (!$payment) return null;

        return $payment;
    }
}
