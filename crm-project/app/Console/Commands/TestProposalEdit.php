<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proposal;

class TestProposalEdit extends Command
{
    protected $signature = 'test:proposal-edit';
    protected $description = 'Test proposal edit page access';

    public function handle()
    {
        $this->info('Testing Proposal Edit Page Access...');

        $proposal = Proposal::first();
        
        if (!$proposal) {
            $this->error('No proposals found.');
            return;
        }

        $this->info('Proposal found:');
        $this->line('ID: ' . $proposal->id);
        $this->line('Title: ' . $proposal->title);
        $this->line('Customer: ' . $proposal->customer->name);
        $this->line('Amount: $' . number_format($proposal->amount, 2));
        $this->line('Status: ' . $proposal->status);
        $this->line('Valid Until: ' . $proposal->valid_until->format('Y-m-d'));

        $editUrl = route('proposals.edit', $proposal->id);
        $this->info('Edit URL: ' . $editUrl);

        $updateUrl = route('proposals.update', $proposal->id);
        $this->info('Update URL: ' . $updateUrl);
        
        $this->info('✅ Proposal edit test completed!');
    }
}
