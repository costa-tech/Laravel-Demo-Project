<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class TestConnections extends Command
{
    protected $signature = 'test:connections';
    protected $description = 'Test all page connections and verify CRM functionality';

    public function handle()
    {
        $this->info('🔗 Testing All Page Connections...');
        
        // Test main navigation routes
        $this->info('📋 Navigation Links:');
        $this->checkRoute('Dashboard', 'dashboard');
        $this->checkRoute('Customers Index', 'customers.index');
        $this->checkRoute('Proposals Index', 'proposals.index'); 
        $this->checkRoute('Invoices Index', 'invoices.index');
        $this->checkRoute('Transactions Index', 'transactions.index');
        
        // Test CRUD routes for Customers
        $this->info('👥 Customer Pages:');
        $this->checkRoute('Create Customer', 'customers.create');
        $this->checkRoute('Show Customer', 'customers.show', [1]);
        $this->checkRoute('Edit Customer', 'customers.edit', [1]);
        
        // Test CRUD routes for Proposals  
        $this->info('📝 Proposal Pages:');
        $this->checkRoute('Create Proposal', 'proposals.create');
        $this->checkRoute('Show Proposal', 'proposals.show', [1]);
        $this->checkRoute('Edit Proposal', 'proposals.edit', [1]);
        
        // Test CRUD routes for Invoices
        $this->info('💰 Invoice Pages:');
        $this->checkRoute('Create Invoice', 'invoices.create');
        $this->checkRoute('Show Invoice', 'invoices.show', [1]);
        $this->checkRoute('Edit Invoice', 'invoices.edit', [1]);
        
        // Test Transaction pages
        $this->info('💳 Transaction Pages:');
        $this->checkRoute('Show Transaction', 'transactions.show', [1]);
        
        // Test Payment pages
        $this->info('💸 Payment Pages:');
        $this->checkRoute('Payment Page', 'payment.show', [1]);
        $this->checkRoute('Payment Success', 'payment.success', [1]);
        
        // Test Auth pages
        $this->info('🔐 Authentication Pages:');
        $this->checkRoute('Login', 'login');
        $this->checkRoute('Register', 'register');
        $this->checkRoute('Profile Edit', 'profile.edit');
        
        // Check view files exist
        $this->info('📄 View Files Check:');
        $viewFiles = [
            'dashboard' => 'resources/views/dashboard.blade.php',
            'customers.index' => 'resources/views/customers/index.blade.php',
            'customers.create' => 'resources/views/customers/create.blade.php',
            'customers.edit' => 'resources/views/customers/edit.blade.php',
            'customers.show' => 'resources/views/customers/show.blade.php',
            'proposals.index' => 'resources/views/proposals/index.blade.php',
            'proposals.create' => 'resources/views/proposals/create.blade.php',
            'proposals.edit' => 'resources/views/proposals/edit.blade.php',
            'proposals.show' => 'resources/views/proposals/show.blade.php',
            'invoices.index' => 'resources/views/invoices/index.blade.php',
            'invoices.create' => 'resources/views/invoices/create.blade.php',
            'invoices.edit' => 'resources/views/invoices/edit.blade.php',
            'invoices.show' => 'resources/views/invoices/show.blade.php',
            'transactions.index' => 'resources/views/transactions/index.blade.php',
            'transactions.show' => 'resources/views/transactions/show.blade.php',
            'payments.show' => 'resources/views/payments/show.blade.php',
            'payments.success' => 'resources/views/payments/success.blade.php',
        ];
        
        foreach ($viewFiles as $name => $path) {
            if (file_exists(base_path($path))) {
                $this->line("✅ {$name} view exists");
            } else {
                $this->error("❌ {$name} view missing: {$path}");
            }
        }
        
        $this->info('🎉 Connection Test Complete!');
        $this->info('🌐 Visit: http://localhost:8000 to test all pages');
    }
    
    private function checkRoute($name, $routeName, $parameters = [])
    {
        try {
            if (Route::has($routeName)) {
                $url = route($routeName, $parameters);
                $this->line("✅ {$name}: {$url}");
            } else {
                $this->error("❌ {$name}: Route '{$routeName}' not found");
            }
        } catch (\Exception $e) {
            $this->error("❌ {$name}: Error - " . $e->getMessage());
        }
    }
}
