<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Garage;
use App\Models\Apartment;
use App\Models\Owner;

class GaragesManager extends Component
{
    public $garages;
    public $apartments;
    public $owners;
    
    // Form fields
    public $garage_id;
    public $code;
    public $apartment_id;
    public $owner_id;
    public $share_fraction;
    public $status = 'occupied';
    
    public $isModalOpen = 0;

    public function render()
    {
        $this->garages = Garage::with(['apartment', 'owner'])->orderBy('code')->get();
        return view('livewire.garages-manager')->layout('layouts.theme');
    }

    public function mount()
    {
        $this->apartments = Apartment::orderBy('code')->get();
        $this->owners = Owner::orderBy('name')->get();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->garage_id = null;
        $this->code = '';
        $this->apartment_id = null;
        $this->owner_id = null;
        $this->share_fraction = '';
        $this->status = 'occupied';
    }

    public function store()
    {
        $this->validate([
            'code' => 'required|max:255',
            'apartment_id' => 'nullable|exists:apartments,id',
            'owner_id' => 'nullable|exists:owners,id',
            'share_fraction' => 'required|numeric|min:0',
            'status' => 'required|string'
        ]);

        Garage::updateOrCreate(['id' => $this->garage_id], [
            'code' => $this->code,
            'apartment_id' => $this->apartment_id ?: null,
            'owner_id' => $this->owner_id ?: null,
            'share_fraction' => $this->share_fraction,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->garage_id ? 'Garage Actualizado con Éxito.' : 'Garage Creado con Éxito.');
        
        $this->closeModal();
    }

    public function edit($id)
    {
        $garage = Garage::findOrFail($id);
        $this->garage_id = $garage->id;
        $this->code = $garage->code;
        $this->apartment_id = $garage->apartment_id;
        $this->owner_id = $garage->owner_id;
        $this->share_fraction = $garage->share_fraction;
        $this->status = $garage->status;

        $this->openModal();
    }

    public function delete($id)
    {
        Garage::findOrFail($id)->delete();
        session()->flash('message', 'Garage Eliminado con Éxito.');
    }
}
