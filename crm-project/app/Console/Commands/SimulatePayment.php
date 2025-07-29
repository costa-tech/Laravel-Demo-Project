<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Invoice;

class SimulatePayment extends Command
{
    protected $signature = 'simulate:payment {transaction_id}';
    protected $description = 'Simulate a successful payment for testing';

    public function handle()
    {
        $transactionId = $this->argument('transaction_id');
        
        $transaction = Transaction::where('transaction_id', $transactionId)->first();
        
        if (!$transaction) {
            $this->error("Transaction {$transactionId} not found.");
            return;
        }

        // Update transaction status
        $transaction->update([
            'status' => 'completed',
            'payment_details' => json_encode([
                'method' => 'stripe',
                'completed_at' => now()->toISOString(),
                'simulated' => true
            ])
        ]);

        // Update invoice status
        $invoice = $transaction->invoice;
        if ($invoice) {
            $invoice->update(['status' => 'paid']);
            $this->info("Invoice {$invoice->invoice_number} marked as paid.");
        }

        $this->info("Transaction {$transactionId} updated to completed!");
    }
}
