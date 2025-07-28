<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'customers' => Customer::count(),
            'proposals' => Proposal::count(),
            'invoices' => Invoice::count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
        ];

        return view('dashboard', compact('stats'));
    }
}