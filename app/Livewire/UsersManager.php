<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersManager extends Component
{
    use WithPagination, WithFileUploads;

    // Propiedades de búsqueda y filtros
    public $search = '';
    public $filter_role = 'all';
    public $filter_status = 'all';

    // Propiedades del formulario
    public $slideOverOpen = false;
    public $isEditMode = false;
    public $selected_id = null;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'cashier';
    public $active = true;
    public $photo;

    // Roles disponibles
    public array $roles = [
        'admin' => '👑 Administrador',
        'cashier' => '💰 Cajero',
        'viewer' => '👁️ Visor',
    ];

    /**
     * Resetear paginación al buscar o filtrar
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * Limpiar búsqueda
     */
    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Limpiar todos los filtros
     */
    public function clearFilters()
    {
        $this->search = '';
        $this->filter_role = 'all';
        $this->filter_status = 'all';
        $this->resetPage();
    }

    /**
     * Estadísticas computadas
     */
    #[Computed]
    public function stats()
    {
        return [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'cashiers' => User::where('role', 'cashier')->count(),
            'viewers' => User::where('role', 'viewer')->count(),
            'active_users' => User::where('active', true)->count(),
            'inactive_users' => User::where('active', false)->count(),
        ];
    }

    /**
     * Abrir formulario para crear
     */
    public function create()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->slideOverOpen = true;
    }

    /**
     * Abrir formulario para editar
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->active = $user->active;
        $this->password = '';
        $this->password_confirmation = '';

        $this->isEditMode = true;
        $this->slideOverOpen = true;
    }

    /**
     * Guardar o actualizar
     */
    public function store()
    {
        // Validación dinámica
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->selected_id)],
            'role' => ['required', 'in:admin,cashier,viewer'],
            'active' => ['boolean'],
        ];

        // Password solo requerido en creación
        if (!$this->isEditMode) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['photo'] = ['required', 'image', 'max:2048'];
        } else {
            // En edición, password es opcional
            if ($this->password) {
                $rules['password'] = ['string', 'min:8', 'confirmed'];
            }
            $rules['photo'] = ['nullable', 'image', 'max:2048'];
        }

        $validated = $this->validate($rules);

        try {
            if ($this->isEditMode) {
                // Actualizar usuario existente
                $user = User::findOrFail($this->selected_id);

                $data = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'role' => $this->role,
                    'active' => $this->active,
                ];

                // Solo actualizar password si se proporcionó uno nuevo
                if ($this->password) {
                    $data['password'] = Hash::make($this->password);
                }

                // Manejar foto de perfil
                if ($this->photo) {
                    // Eliminar foto anterior si existe
                    if ($user->profile_photo) {
                        Storage::disk('public')->delete($user->profile_photo);
                    }
                    $data['profile_photo'] = $this->photo->store('users', 'public');
                }

                $user->update($data);

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Usuario actualizado exitosamente'
                ]);
            } else {
                // Crear nuevo usuario
                $data = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role' => $this->role,
                    'active' => $this->active,
                ];

                // Guardar foto de perfil
                if ($this->photo) {
                    $data['profile_photo'] = $this->photo->store('users', 'public');
                }

                User::create($data);

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Usuario creado exitosamente'
                ]);
            }

            $this->closeSlideOver();
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al guardar el usuario: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar usuario
     */
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);

            // No permitir eliminar al usuario autenticado
            if ($user->id === auth()->id()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No puedes eliminar tu propio usuario'
                ]);
                return;
            }

            // Eliminar foto de perfil si existe
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Usuario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al eliminar el usuario'
            ]);
        }
    }

    /**
     * Toggle estado activo/inactivo
     */
    public function toggleActive($id)
    {
        try {
            $user = User::findOrFail($id);

            // No permitir desactivar al usuario autenticado
            if ($user->id === auth()->id() && $user->active) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No puedes desactivar tu propio usuario'
                ]);
                return;
            }

            $user->update(['active' => !$user->active]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $user->active ? 'Usuario activado' : 'Usuario desactivado'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al cambiar el estado'
            ]);
        }
    }

    /**
     * Cerrar slide-over
     */
    public function closeSlideOver()
    {
        $this->slideOverOpen = false;
        $this->resetForm();
    }

    /**
     * Resetear formulario
     */
    private function resetForm()
    {
        $this->reset([
            'selected_id',
            'name',
            'email',
            'password',
            'password_confirmation',
            'role',
            'active',
            'photo',
        ]);

        $this->role = 'cashier';
        $this->active = true;
        $this->resetValidation();
    }

    /**
     * Renderizar componente
     */
    public function render()
    {
        // Query base
        $query = User::query();

        // Aplicar búsqueda
        if ($this->search) {
            $query->search($this->search);
        }

        // Aplicar filtro de rol
        if ($this->filter_role !== 'all') {
            $query->role($this->filter_role);
        }

        // Aplicar filtro de estado
        if ($this->filter_status === 'active') {
            $query->active();
        } elseif ($this->filter_status === 'inactive') {
            $query->inactive();
        }

        // Ordenar y paginar
        $users = $query->latest()->paginate(12);

        return view('livewire.users.users-manager', [
            'users' => $users,
        ]);
    }
}
