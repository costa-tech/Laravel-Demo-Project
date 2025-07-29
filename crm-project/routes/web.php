<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Project - Modern Business Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Inter", sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Navigation */
        .nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: white;
            font-size: 20px;
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .btn-secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            text-align: center;
            padding: 80px 0;
            color: white;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            padding: 18px 36px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .btn-white {
            background: white;
            color: #667eea;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }
        
        /* Features Section */
        .features {
            background: white;
            padding: 100px 0;
            margin-top: -50px;
            border-radius: 30px 30px 0 0;
            position: relative;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #666;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
        }
        
        .feature-icon.blue { background: linear-gradient(45deg, #667eea, #764ba2); }
        .feature-icon.green { background: linear-gradient(45deg, #11998e, #38ef7d); }
        .feature-icon.orange { background: linear-gradient(45deg, #fc7c7c, #f55a5a); }
        .feature-icon.purple { background: linear-gradient(45deg, #667eea, #764ba2); }
        
        .feature-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        
        /* Stats Section */
        .stats {
            background: linear-gradient(45deg, #667eea, #764ba2);
            padding: 80px 0;
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }
        
        .stat {
            padding: 20px;
        }
        
        .stat h3 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stat p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* CTA Section */
        .cta {
            background: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }
        
        .cta p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Footer */
        .footer {
            background: #1a1a1a;
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-section h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #667eea;
        }
        
        .footer-section p {
            color: #ccc;
            line-height: 1.6;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 10px;
        }
        
        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-section ul li a:hover {
            color: #667eea;
        }
        
        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 30px;
            text-align: center;
            color: #999;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1.1rem; }
            .hero-buttons { flex-direction: column; align-items: center; }
            .nav-content { flex-direction: column; gap: 20px; }
            .features { padding: 60px 0; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="container">
            <div class="nav-content">
                <div class="logo">
                    <div class="logo-icon">💼</div>
                    CRM Project
                </div>
                <div class="nav-buttons">
                    <a href="/login" class="btn btn-secondary">Sign In</a>
                    <a href="/register" class="btn btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Transform Your Business Relationships</h1>
            <p>The complete CRM solution that helps you manage customers, create proposals, send invoices, and process payments - all in one beautiful, easy-to-use platform.</p>
            <div class="hero-buttons">
                <a href="/register" class="btn btn-white btn-hero">Start Free Trial</a>
                <a href="/login" class="btn btn-primary btn-hero">Sign In</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Everything You Need to Grow</h2>
                <p>Powerful features designed to streamline your business operations and boost your productivity.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon blue">👥</div>
                    <h3>Customer Management</h3>
                    <p>Keep track of all your customers, their contact information, communication history, and preferences in one organized place.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon green">📝</div>
                    <h3>Smart Proposals</h3>
                    <p>Create professional proposals quickly with our intuitive editor. Track proposal status and get notified when clients view them.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon orange">🧾</div>
                    <h3>Automated Invoicing</h3>
                    <p>Generate beautiful, professional invoices automatically. Send them via email and track payment status in real-time.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon purple">💳</div>
                    <h3>Secure Payments</h3>
                    <p>Accept online payments securely with Stripe integration. Automatic transaction recording and comprehensive payment tracking.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat">
                    <h3>99.9%</h3>
                    <p>Uptime Guarantee</p>
                </div>
                <div class="stat">
                    <h3>24/7</h3>
                    <p>Customer Support</p>
                </div>
                <div class="stat">
                    <h3>256-bit</h3>
                    <p>SSL Encryption</p>
                </div>
                <div class="stat">
                    <h3>10k+</h3>
                    <p>Happy Customers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Transform Your Business?</h2>
            <p>Join thousands of businesses already using our CRM solution to increase sales, improve customer relationships, and streamline operations.</p>
            <a href="/register" class="btn btn-primary btn-hero">Start Your Free Trial Today</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>CRM Project</h3>
                    <p>The modern business management platform that helps you grow your revenue, improve customer relationships, and streamline your operations.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Features</h3>
                    <ul>
                        <li><a href="#">Customer Management</a></li>
                        <li><a href="#">Proposal Creation</a></li>
                        <li><a href="#">Invoice Generation</a></li>
                        <li><a href="#">Payment Processing</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Support</a></li>
                        <li><a href="#">System Status</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 CRM Project. All rights reserved. Built with Laravel.</p>
            </div>
        </div>
    </footer>
</body>
</html>';
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Resource routes - these create all CRUD routes automatically
    Route::resource('customers', CustomerController::class);
    Route::resource('proposals', ProposalController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
    
    // Invoice specific routes
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
});

// Payment routes (accessible without authentication for customers)
Route::get('payment/{invoice}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('payment/{invoice}/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

require __DIR__.'/auth.php';