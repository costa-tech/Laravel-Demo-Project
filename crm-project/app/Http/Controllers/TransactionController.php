<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'invoice']);

        // Filter by customer if specified
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by status if specified
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(15);
        $customers = Customer::where('status', 'active')->get();

        return view('transactions.index', compact('transactions', 'customers'));
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }
}
