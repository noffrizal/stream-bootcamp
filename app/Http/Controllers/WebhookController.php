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

          $transaction = Transaction::where('transaction_code', $orderId)->first();
          $package = Package::find($transaction->package_id);

          if ($status === 'success'){
            $userPremium = UserPremium::where([
                'user_id' => $transaction->user_id,
                'package_id' => $package->id,
                ]);

                if($userPremium)
                {
                    $endOfSubscription =$userPremium->end_of_subscription;
                    $date = Carbon::createFromDate('Y-m-d', $endOfSubscription);
                    $newEndOfSubscription = $date->addDay($package->max_days);
                }else{
                UserPremium::create([
                    'package_id' => $package->id,
                    'user_id' => $transaction->user_id,
                    'end_of_subscription' => now()->addDay($package->max_days),
                    ]);
                }

            // $endOfSubscription = Carbon::now()

          }

          return response()->json(null);
        }

      }

