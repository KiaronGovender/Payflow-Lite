<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = payments::latest()->get();

        return $payments;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'merchant_reference' => ['required','string'],
            'amount' => ['required','numeric','min=>0']
        ]);

        $validated['status'] = 'Processing';
        $payment = payments::create($validated);
        $order = orders::where('merchant_reference', $payment->merchant_reference)->first();

        if (!$order){
            $payment->update(['status' => 'Failed']);
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->amount != $payment->amount){
            $payment->update(['status' => 'Mismatch']);
            $order->update(['status' => 'mismatch']);
            return response()->json([
                'message' => 'Payment mismatch'
            ], 200);
        }

        $order->update(['status' => 'paid']);
        $payment->update(['status' => 'Matched']);

        return response()->json([
            "message"=> "Payment processed successfully",
            "payment_status"=> "Matched",
            "order_status"=> "paid",
            "merchant_reference"=> "ORD-1001"
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(payments $payments)
    {
        //
    }
}
