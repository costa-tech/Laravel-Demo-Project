<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Proposal;
use App\Notifications\InvoiceSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $proposals = Proposal::where('status', 'accepted')->get();
        return view('invoices.create', compact('customers', 'proposals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'proposal_id' => 'nullable|exists:proposals,id',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled'
        ]);

        $data = $request->all();
        $data['invoice_number'] = Invoice::generateInvoiceNumber();
        $data['total_amount'] = $data['amount'] + $data['tax_amount'];

        $invoice = Invoice::create($data);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::where('status', 'active')->get();
        $proposals = Proposal::where('status', 'accepted')->get();
        return view('invoices.edit', compact('invoice', 'customers', 'proposals'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'proposal_id' => 'nullable|exists:proposals,id',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled'
        ]);

        $data = $request->all();
        $data['total_amount'] = $data['amount'] + $data['tax_amount'];

        $invoice->update($data);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    public function send(Invoice $invoice)
    {
        // Send email notification to customer
        Notification::route('mail', $invoice->customer->email)
            ->notify(new InvoiceSent($invoice));

        // Update invoice status
        $invoice->update(['status' => 'sent']);

        return back()->with('success', 'Invoice sent successfully!');
    }
}
