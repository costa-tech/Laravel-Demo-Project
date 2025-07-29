<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserRegistered;
use App\Models\User;

class TestSetup extends Command
{
    protected $signature = 'test:setup';
    protected $description = 'Test email and Stripe configuration';

    public function handle()
    {
        $this->info('🧪 Testing CRM Setup...');
        
        // Test Database Connection
        try {
            \DB::connection()->getPdo();
            $this->info('✅ Database connection: SUCCESS');
        } catch (\Exception $e) {
            $this->error('❌ Database connection: FAILED - ' . $e->getMessage());
        }

        // Test Email Configuration
        $this->info('📧 Email Configuration:');
        $this->line('Host: ' . config('mail.mailers.smtp.host'));
        $this->line('Port: ' . config('mail.mailers.smtp.port'));
        $this->line('Username: ' . config('mail.mailers.smtp.username'));
        
        // Test Stripe Configuration
        $this->info('💳 Stripe Configuration:');
        $this->line('Publishable Key: ' . (env('STRIPE_KEY') ? '✅ Set' : '❌ Not Set'));
        $this->line('Secret Key: ' . (env('STRIPE_SECRET') ? '✅ Set' : '❌ Not Set'));
        $this->line('Webhook Secret: ' . (env('STRIPE_WEBHOOK_SECRET') ? '✅ Set' : '❌ Not Set'));

        // Count Records
        $this->info('📊 Database Records:');
        $this->line('Customers: ' . \App\Models\Customer::count());
        $this->line('Proposals: ' . \App\Models\Proposal::count());
        $this->line('Invoices: ' . \App\Models\Invoice::count());
        $this->line('Transactions: ' . \App\Models\Transaction::count());

        $this->info('🎉 Setup test completed!');
        $this->info('👉 Next steps:');
        $this->line('1. Update MailTrap credentials in .env');
        $this->line('2. Update Stripe keys in .env');
        $this->line('3. Register at: http://localhost:8000/register');
    }
}
