<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Proposal;
use App\Models\Invoice;
use App\Models\Transaction;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample customers
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1-555-0123',
                'company' => 'Acme Corporation',
                'address' => '123 Main St, New York, NY 10001',
                'status' => 'active'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1-555-0124',
                'company' => 'Tech Solutions Inc',
                'address' => '456 Oak Ave, Los Angeles, CA 90210',
                'status' => 'active'
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'phone' => '+1-555-0125',
                'company' => 'Global Industries',
                'address' => '789 Pine St, Chicago, IL 60601',
                'status' => 'active'
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Create sample proposals
        $proposals = [
            [
                'title' => 'Website Development Project',
                'customer_id' => 1,
                'amount' => 5000.00,
                'description' => 'Complete website development with modern design and CMS integration.',
                'status' => 'accepted',
                'valid_until' => now()->addDays(30)
            ],
            [
                'title' => 'Mobile App Development',
                'customer_id' => 2,
                'amount' => 8000.00,
                'description' => 'Native mobile application for iOS and Android platforms.',
                'status' => 'sent',
                'valid_until' => now()->addDays(15)
            ],
            [
                'title' => 'Digital Marketing Campaign',
                'customer_id' => 3,
                'amount' => 3000.00,
                'description' => 'Comprehensive digital marketing strategy and implementation.',
                'status' => 'draft',
                'valid_until' => now()->addDays(45)
            ]
        ];

        foreach ($proposals as $proposalData) {
            Proposal::create($proposalData);
        }

        // Create sample invoices
        $invoice1 = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'customer_id' => 1,
            'proposal_id' => 1,
            'amount' => 5000.00,
            'tax_amount' => 500.00,
            'total_amount' => 5500.00,
            'description' => 'Website development project - Final payment',
            'status' => 'sent',
            'due_date' => now()->addDays(30)
        ]);

        $invoice2 = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'customer_id' => 2,
            'proposal_id' => null,
            'amount' => 2000.00,
            'tax_amount' => 200.00,
            'total_amount' => 2200.00,
            'description' => 'Consulting services for Q1 2025',
            'status' => 'paid',
            'due_date' => now()->subDays(5)
        ]);

        // Create a sample transaction for the paid invoice
        Transaction::create([
            'customer_id' => 2,
            'invoice_id' => $invoice2->id,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'amount' => 2200.00,
            'status' => 'completed',
            'payment_details' => json_encode(['method' => 'stripe', 'payment_method' => 'card'])
        ]);
    }
}
