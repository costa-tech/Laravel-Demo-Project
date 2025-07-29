<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Invoice;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckPaymentStatus extends Command
{
    protected $signature = 'check:payment-status {transaction_id?}';
    protected $description = 'Check payment status for a transaction';

    public function handle()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $transactionId = $this->argument('transaction_id');
        
        if ($transactionId) {
            $transaction = Transaction::where('transaction_id', $transactionId)->first();
            
            if (!$transaction) {
                $this->error("Transaction {$transactionId} not found.");
                return;
            }
            
            $this->checkSingleTransaction($transaction);
        } else {
            // Check all pending transactions
            $pendingTransactions = Transaction::where('status', 'pending')->get();
            
            if ($pendingTransactions->isEmpty()) {
                $this->info('No pending transactions found.');
                return;
            }
            
            $this->info('Checking ' . $pendingTransactions->count() . ' pending transactions...');
            
            foreach ($pendingTransactions as $transaction) {
                $this->line('');
                $this->checkSingleTransaction($transaction);
            }
        }
    }
    
    private function checkSingleTransaction($transaction)
    {
        $this->info("Checking transaction: {$transaction->transaction_id}");
        $this->line("Current status: {$transaction->status}");
        $this->line("Stripe Payment Intent: {$transaction->stripe_payment_intent_id}");
        
        if (!$transaction->stripe_payment_intent_id) {
            $this->warn("No Stripe Payment Intent ID found.");
            return;
        }
        
        try {
            $paymentIntent = PaymentIntent::retrieve($transaction->stripe_payment_intent_id);
            $this->line("Stripe status: {$paymentIntent->status}");
            
            if ($paymentIntent->status === 'succeeded' && $transaction->status === 'pending') {
                $this->warn("⚠️  Payment succeeded in Stripe but transaction is still pending!");
                
                if ($this->confirm("Update transaction to completed?")) {
                    $transaction->update([
                        'status' => 'completed',
                        'payment_details' => json_encode([
                            'method' => 'stripe',
                            'payment_method' => $paymentIntent->payment_method ?? 'card',
                            'completed_at' => now()->toISOString(),
                            'manually_updated' => true
                        ])
                    ]);
                    
                    // Also update invoice
                    $invoice = $transaction->invoice;
                    if ($invoice && $invoice->status !== 'paid') {
                        $invoice->update(['status' => 'paid']);
                        $this->info("✅ Invoice status updated to paid");
                    }
                    
                    $this->info("✅ Transaction updated to completed");
                }
            } elseif ($paymentIntent->status === 'succeeded' && $transaction->status === 'completed') {
                $this->info("✅ Transaction status is correct (completed)");
            } elseif ($paymentIntent->status === 'requires_payment_method') {
                $this->warn("❌ Payment failed - requires payment method");
            } else {
                $this->line("Status is: {$paymentIntent->status}");
            }
            
        } catch (\Exception $e) {
            $this->error("Error checking Stripe: " . $e->getMessage());
        }
    }
}
