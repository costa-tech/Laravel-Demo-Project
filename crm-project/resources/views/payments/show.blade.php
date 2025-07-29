<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment - Invoice {{ $invoice->invoice_number }}</title>
    <script src="https://js.stripe.com/v3/"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="text-center mb-6">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Payment
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Invoice {{ $invoice->invoice_number }}
                    </p>
                </div>

                <!-- Invoice Details -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Invoice Details</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Customer:</span>
                            <span class="text-sm font-medium">{{ $invoice->customer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Amount:</span>
                            <span class="text-sm font-medium">${{ number_format($invoice->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tax:</span>
                            <span class="text-sm font-medium">${{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-lg font-semibold">Total:</span>
                            <span class="text-lg font-bold text-green-600">${{ number_format($invoice->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Due Date:</span>
                            <span class="text-sm font-medium">{{ $invoice->due_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="payment-form">
                    <div id="payment-element">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                    <button id="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                        <div id="spinner" class="hidden spinner"></div>
                        <span id="button-text">Pay ${{ number_format($invoice->total_amount, 2) }}</span>
                    </button>
                    <div id="payment-message" class="hidden mt-4 text-center"></div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Secured by <span class="font-semibold">Stripe</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');

        const options = {
            clientSecret: '{{ $paymentIntent->client_secret }}',
            appearance: {
                theme: 'stripe',
            },
        };

        const elements = stripe.elements(options);
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            setLoading(true);

            const {error} = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '{{ route('payment.success', $invoice->id) }}',
                },
            });

            if (error) {
                if (error.type === "card_error" || error.type === "validation_error") {
                    showMessage(error.message);
                } else {
                    showMessage("An unexpected error occurred.");
                }
            }

            setLoading(false);
        });

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");
            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function () {
                messageContainer.classList.add("hidden");
                messageContainer.textContent = "";
            }, 4000);
        }

        function setLoading(isLoading) {
            if (isLoading) {
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }
    </script>

    <style>
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</body>
</html>
