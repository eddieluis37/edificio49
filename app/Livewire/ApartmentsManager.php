<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Apartment;
use App\Models\Owner;

class ApartmentsManager extends Component
{
    public $apartments;
    public $baseBudget;

    // Apartment Form Fields
    public $apartment_id;
    public $code;
    public $floor;
    public $number;
    public $area;
    public $share_fraction;
    public $status = 'occupied';

    // Owner Form Fields
    public $has_owner = false;
    public $owner_id;
    public $owner_name;
    public $owner_document_type = 'CC';
    public $owner_document_number;
    public $owner_email;
    public $owner_phone;
    public $owner_active = true;

    public $isModalOpen = false;

    public function render()
    {
        $this->apartments = Apartment::with(['owner', 'garages'])->orderBy('code')->get();
        
        $setting = \App\Models\AdminFeeSetting::orderBy('year', 'desc')->orderBy('month', 'desc')->first();
        $this->baseBudget = $setting && $setting->base_budget > 0 ? (float) $setting->base_budget : 1120000;

        return view('livewire.apartments-manager')->layout('layouts.theme');
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
        $this->apartment_id = null;
        $this->code = '';
        $this->floor = '';
        $this->number = '';
        $this->area = '';
        $this->share_fraction = '';
        $this->status = 'occupied';

        $this->has_owner = false;
        $this->owner_id = null;
        $this->owner_name = '';
        $this->owner_document_type = 'CC';
        $this->owner_document_number = '';
        $this->owner_email = '';
        $this->owner_phone = '';
        $this->owner_active = true;
    }

    public function store()
    {
        $this->validate([
            'code' => 'required|max:255',
            'floor' => 'nullable|integer',
            'number' => 'nullable|integer',
            'area' => 'nullable|numeric|min:0',
            'share_fraction' => 'required|numeric|min:0',
            'status' => 'required|string',
            
            // Owner Validation
            'owner_name' => 'required_if:has_owner,true|max:255',
            'owner_document_number' => 'required_if:has_owner,true|max:50',
            'owner_email' => 'nullable|email|max:255',
        ]);

        $apartment = Apartment::find($this->apartment_id);

        if (!$apartment) {
            $apartment = Apartment::create([
                'code' => $this->code,
                'floor' => $this->floor ?: null,
                'number' => $this->number ?: null,
                'area' => $this->area ?: null,
                'share_fraction' => $this->share_fraction,
                'status' => $this->status,
            ]);
        } else {
            $apartment->update([
                'code' => $this->code,
                'floor' => $this->floor ?: null,
                'number' => $this->number ?: null,
                'area' => $this->area ?: null,
                'share_fraction' => $this->share_fraction,
                'status' => $this->status,
            ]);
        }

        // Handle Owner
        if ($this->has_owner) {
            Owner::updateOrCreate(
                ['id' => $this->owner_id],
                [
                    'apartment_id' => $apartment->id, // link to newly created or updated apartment
                    'name' => $this->owner_name,
                    'document_type' => $this->owner_document_type,
                    'document_number' => $this->owner_document_number,
                    'email' => $this->owner_email,
                    'phone' => $this->owner_phone,
                    'active' => $this->owner_active,
                ]
            );
        } else {
            // Delete old owner if we unchecked the box and it existed
            if ($this->owner_id) {
                Owner::where('id', $this->owner_id)->delete();
            }
        }

        session()->flash('message', $this->apartment_id ? 'Apartamento actualizado con éxito.' : 'Apartamento creado con éxito.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $apartment = Apartment::with('owner')->findOrFail($id);
        $this->apartment_id = $apartment->id;
        $this->code = $apartment->code;
        $this->floor = $apartment->floor;
        $this->number = $apartment->number;
        $this->area = $apartment->area;
        $this->share_fraction = $apartment->share_fraction;
        $this->status = $apartment->status;

        if ($apartment->owner) {
            $this->has_owner = true;
            $this->owner_id = $apartment->owner->id;
            $this->owner_name = $apartment->owner->name;
            $this->owner_document_type = $apartment->owner->document_type;
            $this->owner_document_number = $apartment->owner->document_number;
            $this->owner_email = $apartment->owner->email;
            $this->owner_phone = $apartment->owner->phone;
            $this->owner_active = $apartment->owner->active;
        } else {
            $this->has_owner = false;
        }

        $this->openModal();
    }

    public function delete($id)
    {
        $apt = Apartment::findOrFail($id);
        
        // Remove owner relationship manually if needed, 
        // depending on strict database keys it may throw SQL constraint error if owner points to this apt.
        if ($apt->owner) {
            $apt->owner->delete();
        }

        $apt->delete();
        session()->flash('message', 'Apartamento eliminado con éxito.');
    }
}
