<?php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(array $payload): array
    {
        // payload: order_id, gross_amount, items, customer
        $params = [
            'transaction_details' => [
                'order_id'     => $payload['order_id'],
                'gross_amount' => (int) $payload['gross_amount'],
            ],
            'item_details' => $payload['items'] ?? [],
            'customer_details' => $payload['customer'] ?? [],
            'callbacks' => [
                'finish'   => route('midtrans.finish'),
                'unfinish' => route('midtrans.unfinish'),
                'error'    => route('midtrans.error'),
            ],
        ];

        $token = Snap::getSnapToken($params);
        $redirect = "https://app".(Config::$isProduction ? "" : ".sandbox").".midtrans.com/snap/v2/vtweb/" . $token;

        return ['token' => $token, 'redirect_url' => $redirect];
    }
}