<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientList extends Component
{
    use WithPagination;

    // Table search
    public string $search = '';

    // Modal state
    public bool $showModal = false;
    public bool $isEditing = false;

    // Form fields
    public int $clientId = 0;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $company = '';
    public string $address = '';

    // Reset pagination when searching
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Open create modal
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    // Open edit modal
    public function openEditModal(int $id): void
    {
        $client = Client::findOrFail($id);
        $this->clientId = $client->id;
        $this->name     = $client->name;
        $this->email    = $client->email ?? '';
        $this->phone    = $client->phone ?? '';
        $this->company  = $client->company ?? '';
        $this->address  = $client->address ?? '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    // Save (create or update)
    public function save(): void
    {
        $data = $this->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        if ($this->isEditing) {
            Client::findOrFail($this->clientId)->update($data);
            session()->flash('success', 'Client updated successfully!');
        } else {
            auth()->user()->clients()->create($data);
            session()->flash('success', 'Client created successfully!');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    // Delete
    public function delete(int $id): void
    {
        Client::findOrFail($id)->delete();
        session()->flash('success', 'Client deleted successfully!');
    }

    // Reset form fields
    private function resetForm(): void
    {
        $this->clientId = 0;
        $this->name     = '';
        $this->email    = '';
        $this->phone    = '';
        $this->company  = '';
        $this->address  = '';
        $this->resetValidation();
    }

    public function render()
    {
        $clients = auth()->user()->clients()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('company', 'like', "%{$this->search}%")
            )
            ->latest()
            ->paginate(10);

        // return view('livewire.clients.client-list', compact('clients'));
        // return view('livewire.clients.client-list', compact('clients'))
        //     ->layout('layouts.dashboard'); // 👈 Add this
        return view('livewire.clients.client-list', compact('clients'))
            ->layout('layouts.dashboard', ['title' => 'Clients']);
    }
}