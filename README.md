# 🚀 Laravel CRM Project

A comprehensive Customer Relationship Management (CRM) system built with Laravel 10, featuring customer management, proposals, invoicing, and integrated Stripe payments.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

### 🔐 Authentication & Authorization
- **User Registration & Login** with Laravel Breeze
- **Email Verification** for new accounts
- **Password Reset** functionality
- **Profile Management** with secure password updates
- **Session Management** with remember me option

### 👥 Customer Management
- **Complete CRUD Operations** (Create, Read, Update, Delete)
- **Customer Status Management** (Active/Inactive)
- **Company Information** tracking
- **Contact Details** management
- **Customer Search & Filtering**

### 📝 Proposal Management
- **Create Proposals** linked to customers
- **Proposal Status Tracking** (Draft, Sent, Accepted, Rejected)
- **Amount & Description** management
- **Valid Until Dates** for proposal expiry
- **Complete Proposal Lifecycle** management

### 💰 Invoice Management
- **Auto-generated Invoice Numbers** (INV-000001 format)
- **Link Invoices to Customers** and proposals
- **Tax Calculation** support
- **Multiple Invoice Statuses** (Draft, Sent, Paid, Overdue)
- **Due Date Management**
- **Email Invoice Delivery** with payment links

### 💳 Payment Processing
- **Stripe Integration** for secure payments
- **Payment Intent Creation** for each invoice
- **Real-time Payment Status** updates
- **Webhook Support** for payment confirmations
- **Multiple Payment Methods** (Cards, etc.)
- **Payment Success/Failure** handling

### 📊 Transaction Management
- **Complete Transaction History**
- **Payment Status Tracking** (Pending, Completed, Failed)
- **Transaction Details** with Stripe metadata
- **Customer-wise Transaction** filtering
- **Revenue Reporting**

### 📧 Email Notifications
- **Registration Success** emails
- **Invoice Delivery** emails with payment buttons
- **MailTrap Integration** for development
- **Professional Email Templates**

### 📱 Dashboard & UI
- **Modern Responsive Design** with Tailwind CSS
- **Statistics Dashboard** with key metrics
- **Quick Action Buttons**
- **Navigation Menu** with active states
- **Mobile-friendly Interface**

## 🛠️ Tech Stack

- **Framework:** Laravel 10.x
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Payments:** Stripe
- **Email:** MailTrap (Development)
- **Frontend:** Tailwind CSS, Alpine.js
- **Build Tool:** Vite
- **PHP Version:** 8.1+

## ⚡ Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL Database
- Stripe Account (for payments)
- MailTrap Account (for emails)

### Step 1: Clone the Repository
```bash
git clone <your-repository-url>
cd crm-project
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
```bash
# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### Step 5: Build Assets
```bash
# Build frontend assets
npm run build

# Or for development
npm run dev
```

## ⚙️ Configuration

### Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_project
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Stripe Configuration
Add your Stripe keys to `.env`:
```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

### Email Configuration (MailTrap)
Configure email settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@crm-project.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🚀 Usage

### Starting the Application
```bash
# Start the Laravel development server
php artisan serve

# Visit: http://localhost:8000
```

### Default Login Credentials
```
Email: admin@crm.com
Password: admin123
```

### Key Workflows

#### 1. Customer Management
1. Navigate to **Customers** → **Add Customer**
2. Fill in customer details and save
3. Manage customer status (Active/Inactive)
4. Edit or delete customers as needed

#### 2. Creating Proposals
1. Go to **Proposals** → **Create Proposal**
2. Select customer and add proposal details
3. Set amount, description, and valid until date
4. Choose status (Draft/Sent/Accepted/Rejected)

#### 3. Invoice Creation & Payment
1. Navigate to **Invoices** → **Create Invoice**
2. Link to customer and add invoice details
3. **Send Invoice** via email with payment link
4. Customer receives email with **Pay Now** button
5. Payment processed through Stripe
6. Transaction automatically recorded

#### 4. Transaction Monitoring
1. Visit **Transactions** tab
2. View all payment history
3. Filter by customer or status
4. Monitor revenue and payment trends

## 🧪 Testing

### Test Payment Flow
Use Stripe test card numbers:
```
Card: 4242 4242 4242 4242
Expiry: Any future date
CVC: Any 3 digits
ZIP: Any valid ZIP
```

### Artisan Commands
```bash
# Test payment flow
php artisan test:payment-flow

# Check payment status
php artisan check:payment-status

# Test connections
php artisan test:connections

# Simulate payment completion
php artisan simulate:payment TXN-12345
```

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── CustomerController.php
│   ├── ProposalController.php
│   ├── InvoiceController.php
│   ├── TransactionController.php
│   ├── PaymentController.php
│   └── DashboardController.php
├── Models/
│   ├── Customer.php
│   ├── Proposal.php
│   ├── Invoice.php
│   ├── Transaction.php
│   └── User.php
├── Mail/
│   ├── UserRegistered.php
│   └── InvoiceSent.php
└── Console/Commands/
    ├── TestPaymentFlow.php
    ├── CheckPaymentStatus.php
    └── TestConnections.php

resources/views/
├── dashboard.blade.php
├── customers/
├── proposals/
├── invoices/
├── transactions/
├── payments/
└── layouts/

database/
├── migrations/
└── seeders/
    └── SampleDataSeeder.php
```

## 🔧 Key Features Implementation

### Payment Processing Flow
1. **Invoice Creation** → Generates unique invoice number
2. **Email Delivery** → Sends professional invoice email
3. **Payment Page** → Stripe Elements integration
4. **Webhook Handling** → Real-time status updates
5. **Transaction Recording** → Complete payment history

### Security Features
- **CSRF Protection** on all forms
- **SQL Injection Prevention** with Eloquent ORM
- **XSS Protection** with Blade templating
- **Secure Payment Processing** via Stripe
- **Input Validation** on all user inputs

### Performance Optimizations
- **Database Indexing** on key fields
- **Eager Loading** for relationships
- **Caching** for frequently accessed data
- **Asset Compilation** with Vite

## 🐛 Troubleshooting

### Common Issues

**Payment not updating to completed:**
```bash
php artisan check:payment-status
```

**View compilation errors:**
```bash
php artisan view:clear
php artisan config:clear
```

**Database connection issues:**
```bash
php artisan migrate:status
```

**Email not sending:**
- Check MailTrap credentials in `.env`
- Verify MAIL_FROM_ADDRESS is set

## 📈 Future Enhancements

- [ ] **Reporting Dashboard** with charts
- [ ] **Invoice PDF Generation**
- [ ] **Recurring Invoices**
- [ ] **Multi-currency Support**
- [ ] **Customer Portal**
- [ ] **API Development**
- [ ] **Advanced Search & Filters**
- [ ] **Audit Logs**

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request


## 📞 Support

For support, email himsaradecosta@gmail.com or create an issue in the repository.

---

**Built with ❤️ using Laravel Framework**
