<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\Notification;

class TestEmail extends Command
{
    protected $signature = 'test:email {email=test@example.com}';
    protected $description = 'Test email configuration by sending a sample email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("📧 Testing email configuration...");
        $this->info("Sending test email to: {$email}");
        
        try {
            // Create a test user
            $testUser = new User([
                'name' => 'Test User',
                'email' => $email
            ]);
            
            // Send the notification
            Notification::route('mail', $email)
                ->notify(new UserRegistered($testUser));
            
            $this->info("✅ Email sent successfully!");
            $this->info("🔍 Check your MailTrap inbox at: https://mailtrap.io/inboxes");
            $this->line("📨 Subject: Welcome to " . config('app.name'));
            
        } catch (\Exception $e) {
            $this->error("❌ Email failed to send!");
            $this->error("Error: " . $e->getMessage());
            
            // Check common issues
            $this->line("\n🔧 Troubleshooting:");
            $this->line("1. Verify MailTrap credentials in .env");
            $this->line("2. Check internet connection");
            $this->line("3. Ensure MailTrap inbox is active");
        }
    }
}
