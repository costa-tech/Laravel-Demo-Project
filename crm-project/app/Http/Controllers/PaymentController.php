<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function show(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('payment.success', $invoice->id);
        }

        try {
            // Create or retrieve payment intent
            if ($invoice->stripe_payment_intent_id) {
                $paymentIntent = PaymentIntent::retrieve($invoice->stripe_payment_intent_id);
            } else {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $invoice->total_amount * 100, // Convert to cents
                    'currency' => 'usd',
                    'metadata' => [
                        'invoice_id' => $invoice->id,
                        'customer_id' => $invoice->customer_id,
                    ],
                ]);

                $invoice->update(['stripe_payment_intent_id' => $paymentIntent->id]);

                // Create a pending transaction record
                Transaction::create([
                    'customer_id' => $invoice->customer_id,
                    'invoice_id' => $invoice->id,
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'amount' => $invoice->total_amount,
                    'status' => 'pending',
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'payment_details' => json_encode([
                        'method' => 'stripe',
                        'created_at' => now()->toISOString()
                    ])
                ]);
            }

            return view('payments.show', compact('invoice', 'paymentIntent'));
        } catch (\Exception $e) {
            Log::error('Payment intent creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment setup failed. Please try again.');
        }
    }

    public function success(Invoice $invoice)
    {
        // Verify payment status with Stripe and update transaction if needed
        if ($invoice->stripe_payment_intent_id) {
            try {
                $paymentIntent = PaymentIntent::retrieve($invoice->stripe_payment_intent_id);
                
                if ($paymentIntent->status === 'succeeded') {
                    // Update invoice status if not already paid
                    if ($invoice->status !== 'paid') {
                        $invoice->update(['status' => 'paid']);
                    }
                    
                    // Find and update pending transaction
                    $transaction = Transaction::where('stripe_payment_intent_id', $paymentIntent->id)
                        ->where('invoice_id', $invoice->id)
                        ->where('status', 'pending')
                        ->first();
                    
                    if ($transaction) {
                        $transaction->update([
                            'status' => 'completed',
                            'payment_details' => json_encode([
                                'method' => 'stripe',
                                'payment_method' => $paymentIntent->payment_method ?? 'card',
                                'completed_at' => now()->toISOString(),
                                'verified_on_success_page' => true
                            ])
                        ]);
                        
                        Log::info('Transaction updated on success page for invoice: ' . $invoice->id);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error verifying payment on success page: ' . $e->getMessage());
            }
        }

        // Refresh invoice to get latest data
        $invoice->refresh();
        
        return view('payments.success', compact('invoice'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                $this->handleSuccessfulPayment($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event['data']['object'];
                $this->handleFailedPayment($paymentIntent);
                break;
            default:
                Log::info('Received unknown event type ' . $event['type']);
        }

        return response('Success', 200);
    }

    private function handleSuccessfulPayment($paymentIntent)
    {
        try {
            $invoice = Invoice::where('stripe_payment_intent_id', $paymentIntent['id'])->first();
            
            if ($invoice) {
                // Update invoice status
                $invoice->update(['status' => 'paid']);

                // Find and update existing transaction or create new one
                $transaction = Transaction::where('stripe_payment_intent_id', $paymentIntent['id'])
                    ->where('invoice_id', $invoice->id)
                    ->first();

                if ($transaction) {
                    // Update existing transaction
                    $transaction->update([
                        'status' => 'completed',
                        'payment_details' => json_encode([
                            'method' => 'stripe',
                            'payment_method' => $paymentIntent['payment_method'] ?? 'card',
                            'completed_at' => now()->toISOString()
                        ])
                    ]);
                } else {
                    // Create new transaction if none exists
                    Transaction::create([
                        'customer_id' => $invoice->customer_id,
                        'invoice_id' => $invoice->id,
                        'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                        'amount' => $invoice->total_amount,
                        'status' => 'completed',
                        'stripe_payment_intent_id' => $paymentIntent['id'],
                        'payment_details' => json_encode([
                            'method' => 'stripe',
                            'payment_method' => $paymentIntent['payment_method'] ?? 'card',
                            'completed_at' => now()->toISOString()
                        ])
                    ]);
                }

                Log::info('Payment processed successfully for invoice: ' . $invoice->id);
            }
        } catch (\Exception $e) {
            Log::error('Error processing successful payment: ' . $e->getMessage());
        }
    }

    private function handleFailedPayment($paymentIntent)
    {
        try {
            $invoice = Invoice::where('stripe_payment_intent_id', $paymentIntent['id'])->first();
            
            if ($invoice) {
                // Find and update existing transaction or create new one
                $transaction = Transaction::where('stripe_payment_intent_id', $paymentIntent['id'])
                    ->where('invoice_id', $invoice->id)
                    ->first();

                if ($transaction) {
                    // Update existing transaction
                    $transaction->update([
                        'status' => 'failed',
                        'payment_details' => json_encode([
                            'method' => 'stripe',
                            'error' => 'Payment failed',
                            'failed_at' => now()->toISOString()
                        ])
                    ]);
                } else {
                    // Create failed transaction record
                    Transaction::create([
                        'customer_id' => $invoice->customer_id,
                        'invoice_id' => $invoice->id,
                        'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                        'amount' => $invoice->total_amount,
                        'status' => 'failed',
                        'stripe_payment_intent_id' => $paymentIntent['id'],
                        'payment_details' => json_encode([
                            'method' => 'stripe',
                            'error' => 'Payment failed',
                            'failed_at' => now()->toISOString()
                        ])
                    ]);
                }

                Log::info('Payment failed for invoice: ' . $invoice->id);
            }
        } catch (\Exception $e) {
            Log::error('Error processing failed payment: ' . $e->getMessage());
        }
    }
}
