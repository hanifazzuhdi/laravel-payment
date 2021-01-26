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
        //
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
        // dd($request->toArray());

        DB::transaction(function () use ($request) {
            $donation = Donation::create([
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'donation_type' => $request->donor_type,
                'amount' => $request->amount,
                'note' => $request->note
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => 'DEV-' . uniqid(),
                    'gross_amount' => $donation->amount
                ],
                'customers_detail' => [
                    'fist_name' => $donation->donor_name,
                    'email' => $donation->donor_email,
                ],
                // 'item_details' => [
                //     'price' => $donation->amount,
                //     'quantity' => 1,
                //     'name'  => $donation->donor_type
                // ]
            ];

            $snap_token =  Snap::getSnapToken($params);

            $donation->snap_token = $snap_token;
            $donation->save();

            $this->response['snap_token'] = $snap_token;
        });

        return response()->json($this->response);
    }
}
