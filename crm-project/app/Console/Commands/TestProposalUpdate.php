<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proposal;
use App\Models\Customer;

class TestProposalUpdate extends Command
{
    protected $signature = 'test:proposal-update';
    protected $description = 'Test proposal update functionality';

    public function handle()
    {
        $this->info('Testing Proposal Update...');

        // Get a test proposal
        $proposal = Proposal::first();
        
        if (!$proposal) {
            $this->error('No proposals found. Creating a test proposal...');
            
            $customer = Customer::first();
            if (!$customer) {
                $this->error('No customers found. Please seed some data first.');
                return;
            }

            $proposal = Proposal::create([
                'customer_id' => $customer->id,
                'title' => 'Test Proposal',
                'description' => 'This is a test proposal',
                'amount' => 1000.00,
                'status' => 'draft',
                'valid_until' => now()->addDays(30)
            ]);
            
            $this->info('Test proposal created with ID: ' . $proposal->id);
        }

        $this->info('Current proposal data:');
        $this->line('ID: ' . $proposal->id);
        $this->line('Title: ' . $proposal->title);
        $this->line('Amount: $' . number_format($proposal->amount, 2));
        $this->line('Status: ' . $proposal->status);
        $this->line('Valid Until: ' . $proposal->valid_until->format('Y-m-d'));

        // Test update
        $updateData = [
            'title' => 'Updated Test Proposal',
            'amount' => 1500.00,
            'status' => 'sent',
            'valid_until' => now()->addDays(45)->format('Y-m-d')
        ];

        try {
            $proposal->update($updateData);
            $proposal->refresh();
            
            $this->info('');
            $this->info('✅ Proposal updated successfully!');
            $this->line('New Title: ' . $proposal->title);
            $this->line('New Amount: $' . number_format($proposal->amount, 2));
            $this->line('New Status: ' . $proposal->status);
            $this->line('New Valid Until: ' . $proposal->valid_until->format('Y-m-d'));
            
        } catch (\Exception $e) {
            $this->error('❌ Update failed: ' . $e->getMessage());
        }
    }
}
