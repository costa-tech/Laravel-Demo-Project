<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Customer;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        $proposals = Proposal::with('customer')->latest()->paginate(10);
        return view('proposals.index', compact('proposals'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        return view('proposals.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'valid_until' => 'required|date|after:today',
            'status' => 'required|in:draft,sent,accepted,rejected'
        ]);

        Proposal::create($request->all());

        return redirect()->route('proposals.index')
            ->with('success', 'Proposal created successfully!');
    }

    public function show(Proposal $proposal)
    {
        return view('proposals.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        $customers = Customer::where('status', 'active')->get();
        return view('proposals.edit', compact('proposal', 'customers'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'valid_until' => 'required|date',
            'status' => 'required|in:draft,sent,accepted,rejected'
        ]);

        try {
            $proposal->update($request->all());

            return redirect()->route('proposals.index')
                ->with('success', 'Proposal updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update proposal: ' . $e->getMessage());
        }
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return redirect()->route('proposals.index')
            ->with('success', 'Proposal deleted successfully!');
    }
}
