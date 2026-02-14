<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Breadcrumb extends Component
{
    public $items = [];

    public function mount($items = [])
    {
        $this->items = $items;
    }

    public function render()
    {
        return view('livewire.components.breadcrumb');
    }
}