<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Transaction;

class TestPaymentFlow extends Command
{
    protected $signature = 'test:payment-flow';
    protected $description = 'Test the payment flow and transaction updates';

    public function handle()
    {
        $this->info('Testing Payment Flow...');

        // Check if there are any invoices
        $invoices = Invoice::with('customer', 'transactions')->get();
        
        if ($invoices->isEmpty()) {
            $this->error('No invoices found. Please create some test data first.');
            return;
        }

        $this->info('Found ' . $invoices->count() . ' invoice(s):');
        
        foreach ($invoices as $invoice) {
            $this->line('');
            $this->info("Invoice #{$invoice->invoice_number}");
            $this->line("Customer: {$invoice->customer->name}");
            $this->line("Amount: $" . number_format($invoice->total_amount, 2));
            $this->line("Status: {$invoice->status}");
            $this->line("Stripe Payment Intent: " . ($invoice->stripe_payment_intent_id ?? 'Not created'));
            
            // Show related transactions
            $transactions = $invoice->transactions;
            if ($transactions->count() > 0) {
                $this->info("Transactions ({$transactions->count()}):");
                foreach ($transactions as $transaction) {
                    $this->line("  - {$transaction->transaction_id}: {$transaction->status} ($" . number_format($transaction->amount, 2) . ")");
                }
            } else {
                $this->line("No transactions found for this invoice.");
            }
        }

        $this->info('');
        $this->info('Payment flow test completed!');
        
        // Show payment URL for testing
        $testInvoice = $invoices->where('status', '!=', 'paid')->first();
        if ($testInvoice) {
            $paymentUrl = route('payment.show', $testInvoice->id);
            $this->info("Test payment URL: {$paymentUrl}");
        }
    }
}
