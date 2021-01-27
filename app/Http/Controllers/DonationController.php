<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $donation = Donation::create([
                'donation_code' => "PAYMENT - " . uniqid(),
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'donation_type' => $request->donation_type,
                'amount' => $request->amount,
                'note' => $request->note
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $donation->donation_code,
                    'gross_amount' => $donation->amount
                ],
                'customers_detail' => [
                    'fist_name' => $donation->donor_name,
                    'email' => $donation->donor_email,
                ],
                // 'item_details' => [
                //     'id' => $donation->donation_type,
                //     'price' => $donation->amount,
                //     'quantity' => 1,
                //     'name'  => $donation->donation_type
                // ]
            ];

            $snap_token =  Snap::getSnapToken($params);

            $donation->snap_token = $snap_token;
            $donation->save();

            $this->response['snap_token'] = $snap_token;
        });

        return response()->json($this->response);
    }

    public function notification()
    {
        $notif = new Notification();
        DB::transaction(function () use ($notif) {
            $transactionStatus = $notif->transaction_status;
            $paymenyType = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;
            $donation = Donation::where('donation_code', $orderId)->first();

            if ($transactionStatus == 'capture') {
                if ($paymenyType == 'credit_card') {
                    if ($fraudStatus == 'challange') {
                        $donation->setStatusPending();
                    } else {
                        $donation->setStatusSuccess();
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                $donation->setStatusSuccess();
            } elseif ($transactionStatus == 'pending') {
                $donation->setStatusPending();
            } elseif ($transactionStatus == 'deny') {
                $donation->setStatusFailed();
            } elseif ($transactionStatus == 'cancel') {
                $donation->setStatusFailed();
            } elseif ($transactionStatus == 'expire') {
                $donation->setStatusExpired();
            }
        });

        return;
    }
}
