<?php

namespace App\Livewire\Cashier;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class InvoiceViewer extends Component
{
    use WithPagination;

    public $dateFrom = '';
    public $dateTo = '';
    public $selectedInvoice = null;
    public int $lastKnownCount = 0;

    public function mount()
    {
        $this->authorize('view invoices');
        $this->dateFrom = now()->toDateString();
        $this->dateTo = now()->toDateString();
        $this->lastKnownCount = Order::whereDate('created_at', today())->count();
    }

    #[On('order-updated')]
    #[On('order-created')]
    #[On('payment-status-changed')]
    public function refreshInvoices()
    {
        // Same-page events
    }

    public function checkForUpdates()
    {
        $current = Order::whereDate('created_at', today())->count();
        if ($current !== $this->lastKnownCount) {
            $this->lastKnownCount = $current;
        }
    }

    public function viewInvoice($orderId)
    {
        $this->selectedInvoice = Order::with(['orderItems.menuItem', 'restaurant'])
            ->findOrFail($orderId);
    }

    public function closeInvoice()
    {
        $this->selectedInvoice = null;
    }

    public function resetFilters()
    {
        $this->dateFrom = now()->toDateString();
        $this->dateTo = now()->toDateString();
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::with(['restaurant'])
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest();

        $invoices = $query->paginate(15);

        $todayPaid = Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_amount');
        $todayPending = Order::where('payment_status', 'pending')->whereDate('created_at', today())->sum('total_amount');
        $todayCount = Order::whereDate('created_at', today())->count();

        return view('livewire.cashier.invoice-viewer', [
            'invoices' => $invoices,
            'todayPaid' => $todayPaid,
            'todayPending' => $todayPending,
            'todayCount' => $todayCount,
        ]);
    }
}
