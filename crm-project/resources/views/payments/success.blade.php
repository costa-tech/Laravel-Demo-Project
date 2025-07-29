<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful - Invoice {{ $invoice->invoice_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Payment Successful!
                </h2>
                
                <p class="text-gray-600 mb-6">
                    Thank you for your payment. Your invoice has been marked as paid.
                </p>

                <!-- Payment Details -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Details</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Invoice:</span>
                            <span class="text-sm font-medium">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Amount Paid:</span>
                            <span class="text-sm font-medium">${{ number_format($invoice->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Customer:</span>
                            <span class="text-sm font-medium">{{ $invoice->customer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            <span class="text-sm font-medium text-green-600">{{ ucfirst($invoice->status) }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-4">
                        A confirmation email has been sent to {{ $invoice->customer->email }}
                    </p>
                    
                    <div class="space-y-2">
                        <p class="text-xs text-gray-400">
                            If you have any questions, please contact our support team.
                        </p>
                        <p class="text-xs text-gray-400">
                            Transaction completed securely via Stripe.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
