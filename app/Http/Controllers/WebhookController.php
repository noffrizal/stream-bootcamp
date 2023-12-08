<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handler(Request $request)
    {
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        $notif = new \Midtrans\Notification();

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;

        if ($transactionStatus == 'capture'){
            if ($fraudStatus == 'accept'){
                    // TODO set transaction status on your database to 'success'
                    // and response with 200 OK
                }
            } else if ($transactionStatus == 'settlement'){
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
            } else if ($transactionStatus == 'cancel' ||
              $transactionStatus == 'deny' ||
              $transactionStatus == 'expire'){
              // TODO set transaction status on your database to 'failure'
              // and response with 200 OK
            } else if ($transactionStatus == 'pending'){
              // TODO set transaction status on your database to 'pending' / waiting payment
              // and response with 200 OK
            }
    }
}
