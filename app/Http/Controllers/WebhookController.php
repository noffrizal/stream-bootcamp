<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Transaction;
use App\Models\UserPremium;
use Carbon\Carbon;
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

        $status = '';

        if ($transactionStatus == 'capture'){
            if ($fraudStatus == 'accept'){
              $status = 'success';
            }
          } else if ($transactionStatus == 'settlement'){
            $status = 'success';
          } else if ($transactionStatus == 'cancel' ||
          $transactionStatus == 'deny' ||
          $transactionStatus == 'expire'){
            $status = 'failure';
          } else if ($transactionStatus == 'pending'){
            $status = 'pending';
          }

          $transaction = Transaction::with('package')->where('transaction_code', $orderId)->first();


          if ($status === 'success'){
            $userPremium = UserPremium::where([
                'user_id' => $transaction->user_id])->first();

                if($userPremium)
                {
                    $endOfSubscription =$userPremium->end_of_subscription;
                    $date = Carbon::createFromDate('Y-m-d', $endOfSubscription);
                    $newEndOfSubscription = $date->addDay($transaction->package->max_days)->format('Y-m-d');

                    $userPremium->update([
                      'package_id' => $transaction->package->id,
                      'end_of_subscription' => $newEndOfSubscription,

                    ]);
                }else{
                UserPremium::create([
                    'package_id' => $transaction->package->id,
                    'user_id' => $transaction->user_id,
                    'end_of_subscription' => now()->addDay($transaction->package->max_days),
                    ]);
                }

            // $endOfSubscription = Carbon::now()

          }

          $transaction->update([
            'status' => $status
          ]);

          return response()->json(null);
        }

      }

