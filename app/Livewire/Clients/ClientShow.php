<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class ClientShow extends Component
{
    public Client $client;

    public function mount(Client $client): void
    {
        abort_if($client->user_id !== auth()->id(), 403);
        $this->client = $client->load(['invoices.payments']);
    }

    public function render()
    {
        return view('livewire.clients.client-show')
            ->layout('layouts.dashboard', ['title' => $this->client->name]);
    }
}