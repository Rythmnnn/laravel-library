<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class DendaController extends Controller
{
    public function bayar(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'DENDA-' . time(),
                'gross_amount' => $request->jumlah_denda,
            ],
            'customer_details' => [
                'first_name' => $request->nama,
                'email' => $request->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('denda.bayar', compact('snapToken'));
    }

    public function callback(Request $request)
{
    $serverKey = config('midtrans.server_key');
    $signatureKey = hash('sha512',
        $request->order_id .
        $request->status_code .
        $request->gross_amount .
        $serverKey
    );

    if ($signatureKey != $request->signature_key) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    if ($request->transaction_status == 'settlement') {
        // update status denda -> LUNAS
    }

    return response()->json(['message' => 'OK']);
}

}
