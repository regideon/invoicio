<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Livewire\Clients\ClientList;
use App\Livewire\Dashboard;
use App\Livewire\Products\ProductList;

use App\Livewire\Invoices\InvoiceList;
use App\Livewire\Invoices\InvoiceCreate;
use App\Livewire\Invoices\InvoiceEdit;
use App\Livewire\Invoices\InvoiceShow;

use App\Http\Controllers\InvoicePdfController;
use App\Livewire\Payments\PaymentList;

use App\Livewire\Clients\ClientShow;
use App\Livewire\AI\FinancialReport;
use App\Livewire\AI\DraftInvoice;

/**
 * AI Features
 * 
 * AI Feature 1: AI Invoice Summary & Insights (app/Livewire/AI/InvoiceInsights.php)
 What will it do?
 When you open an invoice, there will be an "AI Insights" button. Click it and Claude will tell you things like:

 "This invoice for Acme Corp is worth $2,500. It's currently overdue by 5 days. Based on their history, they usually pay within 14 days. You should follow up immediately."
 * 
 * AI Feature 2: AI Client Payment Behavior Analysis (app/Livewire/AI/ClientPaymentAnalysis.php)
 * 
 * AI Feature 3: AI Financial Report Generator (app/Livewire/AI/FinancialReport.php)
 Claude will read ALL your financial data — invoices, payments, clients — and write a complete business financial report like:
 "In the last 30 days, you generated $12,500 in revenue. Your top client is Shell Philippines. You have 3 overdue invoices totaling $4,200. Your collection rate is 78%..."
 * 
 * AI Feature 4: AI Draft Invoice from text description (app/Livewire/AI/DraftInvoice.php)
 You type something like:
 "Web design for Shell Philippines, 10 hours at $150 per hour, with 12% tax, due in 30 days"
And Claude will automatically fill in the invoice form for you! No clicking around — just describe it in plain English.
 */
Route::get('/clients/{client}', ClientShow::class)->name('clients.show');
Route::get('/reports', FinancialReport::class)->name('reports.index');
Route::get('/invoices/ai-draft', DraftInvoice::class)->name('invoices.ai-draft');



Route::get('/', fn() => redirect('/login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->prefix('zpanel')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/clients', ClientList::class)->name('clients.index');
    Route::get('/products', ProductList::class)->name('products.index');

    // Invoices
    Route::get('/invoices', InvoiceList::class)->name('invoices.index');
    Route::get('/invoices/create', InvoiceCreate::class)->name('invoices.create');
    Route::get('/invoices/{invoice}', InvoiceShow::class)->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', InvoiceEdit::class)->name('invoices.edit');

    // PDF
    Route::get('/invoices/{invoice}/pdf/download', [InvoicePdfController::class, 'download'])->name('invoices.pdf.download');
    Route::get('/invoices/{invoice}/pdf/view', [InvoicePdfController::class, 'stream'])->name('invoices.pdf.stream');

    Route::get('/payments', PaymentList::class)->name('payments.index');
});


Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');



