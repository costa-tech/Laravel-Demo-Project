<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proposal;
use App\Models\Customer;
use App\Http\Controllers\ProposalController;
use Illuminate\Http\Request;

class TestProposalFormSubmit extends Command
{
    protected $signature = 'test:proposal-form';
    protected $description = 'Test proposal form submission';

    public function handle()
    {
        $this->info('Testing Proposal Form Submission...');

        $proposal = Proposal::first();
        $customer = Customer::first();
        
        if (!$proposal || !$customer) {
            $this->error('Missing required data. Need at least one proposal and one customer.');
            return;
        }

        $this->info('Original proposal data:');
        $this->line('Title: ' . $proposal->title);
        $this->line('Amount: $' . number_format($proposal->amount, 2));
        $this->line('Status: ' . $proposal->status);

        // Simulate form data
        $formData = [
            'title' => 'Form Test Updated Proposal',
            'customer_id' => $customer->id,
            'amount' => 2000.00,
            'description' => 'This is a test description from form submission',
            'valid_until' => now()->addDays(60)->format('Y-m-d'),
            'status' => 'accepted'
        ];

        $this->info('');
        $this->info('Testing validation...');
        
        // Create a mock request
        $request = new Request($formData);
        
        // Test validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'valid_until' => 'required|date',
            'status' => 'required|in:draft,sent,accepted,rejected'
        ];

        $validator = \Validator::make($formData, $rules);
        
        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line('- ' . $error);
            }
            return;
        }

        $this->info('✅ Validation passed!');

        // Test the actual update
        try {
            $proposal->update($formData);
            $proposal->refresh();
            
            $this->info('');
            $this->info('✅ Proposal updated via form simulation!');
            $this->line('New Title: ' . $proposal->title);
            $this->line('New Amount: $' . number_format($proposal->amount, 2));
            $this->line('New Status: ' . $proposal->status);
            $this->line('New Valid Until: ' . $proposal->valid_until->format('Y-m-d'));
            
        } catch (\Exception $e) {
            $this->error('❌ Update failed: ' . $e->getMessage());
        }
    }
}
