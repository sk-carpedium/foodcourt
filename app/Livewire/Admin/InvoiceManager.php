<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Restaurant;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $restaurantFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedInvoice = null;

    protected $queryString = ['search', 'statusFilter', 'restaurantFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingRestaurantFilter()
    {
        $this->resetPage();
    }

    public function viewInvoice($orderId)
    {
        $this->selectedInvoice = Order::with(['orderItems.menuItem', 'restaurant', 'user'])
            ->findOrFail($orderId);
    }

    public function closeInvoice()
    {
        $this->selectedInvoice = null;
    }

    public function render()
    {
        $query = Order::with(['restaurant', 'user'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('payment_status', $this->statusFilter);
            })
            ->when($this->restaurantFilter, function ($q) {
                $q->where('restaurant_id', $this->restaurantFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest();

        $invoices = $query->paginate(15);
        $restaurants = Restaurant::where('is_active', true)->get();

        // Calculate totals
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingAmount = Order::where('payment_status', 'pending')->sum('total_amount');
        $totalInvoices = Order::count();

        return view('livewire.admin.invoice-manager', [
            'invoices' => $invoices,
            'restaurants' => $restaurants,
            'totalRevenue' => $totalRevenue,
            'pendingAmount' => $pendingAmount,
            'totalInvoices' => $totalInvoices,
        ]);
    }
}
