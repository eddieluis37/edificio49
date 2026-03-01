<?php

namespace App\Livewire;

use App\Models\Type;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class VehicleTypes extends Component
{
    use WithPagination, WithFileUploads;

    // ==========================================
    // PROPIEDADES PÚBLICAS
    // ==========================================

    #[Validate('required|min:3|max:100')]
    public string $name = '';

    #[Validate('nullable|image|max:2048')]
    public $photo;

    public ?int $selected_id = null;
    public string $search = '';
    public bool $slideOverOpen = false;
    public bool $isEditMode = false;

    // ==========================================
    // CICLO DE VIDA
    // ==========================================

    public function mount(): void
    {
        //$this->authorize('viewAny', Type::class);
    }

    #[Layout('layouts.theme')]
    public function render()
    {
        return view('livewire.vehicle-types', [
            'vehicleTypes' => $this->vehicleTypes,
            'stats' => $this->stats,
        ]);
    }

    // ==========================================
    // COMPUTED PROPERTIES
    // ==========================================

    #[Computed]
    public function vehicleTypes()
    {
        return Type::query()
            ->withCount('rates')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('order')
            ->paginate(12);
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'total_types' => Type::count(),
            'total_rates' => 0, // Type::withCount('rates')->get()->sum('rates_count'),
            'types_with_rates' => 0, //Type::has('rates')->count(),
            'types_without_rates' => 0, // Type::doesntHave('rates')->count(),
        ];
    }

    // ==========================================
    // ACCIONES CRUD
    // ==========================================

    public function create(): void
    {
        // $this->authorize('create', Type::class);

        $this->resetForm();
        $this->isEditMode = false;
        $this->slideOverOpen = true;
    }

    public function edit(int $id): void
    {
        $vehicleType = Type::findOrFail($id);

        //  $this->authorize('update', $vehicleType);

        $this->selected_id = $id;
        $this->name = $vehicleType->name;
        $this->isEditMode = true;
        $this->slideOverOpen = true;
    }

    public function store(): void
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $this->update();
            } else {
                $this->createNew();
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $this->isEditMode
                    ? 'Tipo de vehículo actualizado exitosamente'
                    : 'Tipo de vehículo creado exitosamente'
            ]);

            $this->closeSlideOver();
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al guardar: ' . $e->getMessage()
            ]);
        }
    }

    protected function createNew(): void
    {
        //$this->authorize('create', Type::class);

        // Validar nombre único
        if (Type::where('name', $this->name)->exists()) {
            throw new \Exception('Ya existe un tipo de vehículo con este nombre');
        }

        $vehicleType = Type::create([
            'name' => $this->name,
            'order' => Type::max('order') + 1,
        ]);

        if ($this->photo) {
            $this->handleImageUpload($vehicleType);
        }
    }

    protected function update(): void
    {
        $vehicleType = Type::findOrFail($this->selected_id);

        // $this->authorize('update', $vehicleType);

        // Validar nombre único (excepto el actual)
        if (Type::where('name', $this->name)
            ->where('id', '!=', $this->selected_id)
            ->exists()
        ) {
            throw new \Exception('Ya existe un tipo de vehículo con este nombre');
        }

        $vehicleType->update([
            'name' => $this->name,
        ]);

        if ($this->photo) {
            // Eliminar imagen anterior si existe
            if ($vehicleType->image) {
                Storage::disk('public')->delete('types/' . $vehicleType->image);
            }

            $this->handleImageUpload($vehicleType);
        }
    }

    #[On('delete')]
    public function delete(int $id): void
    {
        $vehicleType = Type::findOrFail($id);

        // $this->authorize('delete', $vehicleType);

        // Verificar si tiene tarifas asociadas
        if ($vehicleType->rates()->count() > 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'No se puede eliminar porque tiene tarifas asociadas'
            ]);
            return;
        }

        try {
            // Eliminar imagen si existe
            if ($vehicleType->image) {
                Storage::disk('public')->delete('types/' . $vehicleType->image);
            }

            $vehicleType->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Tipo de vehículo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }

    // ==========================================
    // ORDENAMIENTO
    // ==========================================

    #[On('updateOrder')]
    public function updateOrder(array $items): void
    {
        foreach ($items as $index => $item) {
            Type::where('id', $item['value'])
                ->update(['order' => $index + 1]);
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Orden actualizado exitosamente'
        ]);
    }

    // ==========================================
    // UTILIDADES
    // ==========================================

    protected function handleImageUpload(Type $vehicleType): void
    {
        $filename = uniqid() . '_' . time() . '.' . $this->photo->getClientOriginalExtension();

        // Guardar imagen original
        $path = $this->photo->storeAs('types', $filename, 'public');

        // Optimizar imagen con Intervention Image
        // $manager = new ImageManager(new Driver());
        // $image = $manager->read(storage_path('app/public/' . $path));
        // $image->scale(800, 600);
        // $image->save();

        $vehicleType->update(['image' => $filename]);
    }

    public function clearSearch(): void
    {
        $this->reset('search');
        $this->resetPage();
    }

    public function closeSlideOver(): void
    {
        $this->slideOverOpen = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset([
            'name',
            'photo',
            'selected_id',
            'isEditMode',
        ]);

        $this->resetValidation();
    }

    // ==========================================
    // PAGINACIÓN
    // ==========================================

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // public function paginationView(): string
    // {
    //     return 'vendor.livewire.tailwind';
    // }
}
