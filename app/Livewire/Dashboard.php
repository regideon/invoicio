<?php

namespace App\Livewire;

use App\Models\Invoice;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $userId = auth()->id();

        $totalRevenue = Invoice::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('total');

        $paidAmount = Invoice::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('total');

        $pendingAmount = Invoice::where('user_id', $userId)
            ->where('status', 'sent')
            ->sum('total');

        $overdueAmount = Invoice::where('user_id', $userId)
            ->where('status', 'overdue')
            ->sum('total');

        $recentInvoices = Invoice::where('user_id', $userId)
            ->with('client')
            ->latest()
            ->take(5)
            ->get();

        $totalInvoices   = Invoice::where('user_id', $userId)->count();
        $paidInvoices    = Invoice::where('user_id', $userId)->where('status', 'paid')->count();
        $pendingInvoices = Invoice::where('user_id', $userId)->where('status', 'sent')->count();
        $overdueInvoices = Invoice::where('user_id', $userId)->where('status', 'overdue')->count();

        return view('livewire.dashboard', compact(
            'totalRevenue',
            'paidAmount',
            'pendingAmount',
            'overdueAmount',
            'recentInvoices',
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'overdueInvoices',
        ))->layout('layouts.dashboard', ['title' => 'Dashboard']);
    }
}