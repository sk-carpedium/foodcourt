<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination as Pagination;

class UserManagement extends Component
{
    use Pagination;
    // PUBLIC PROPERTIES AND METHODS HERE
    public $userId, $name, $email, $password;
    public $selectedRoles = [];
    public $isEditing = false;
    public $showModal = false;


    //validation rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => $this->isEditing ? 'nullable|string|min:8' :  'required|string|min:8',
            'selectedRoles' => 'array',
        ];
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->showModal = true;
    }
    public function edit($id)
    {
        $this->authorize('edit users');
        $user = \App\Models\User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $this->authorize('edit users');
            $user = \App\Models\User::findOrFail($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;
            if (!empty($this->password)) {
                $user->password = bcrypt($this->password);
            }
            $user->save();
        } else {
            $this->authorize('create users');
            $user = \App\Models\User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
        }

        // Sync roles
        $user->syncRoles($this->selectedRoles);

        $this->showModal = false;
        $this->resetInputFields();
        
        session()->flash('message', $this->isEditing ? 'User updated successfully!' : 'User created successfully!');
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->selectedRoles = [];
    }

    public function delete($id)
    {
        $this->authorize('delete users');
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }



    public function render()
    {
        $this->authorize('view users');
        return view('livewire.user-management', [
            'users' => \App\Models\User::with('roles')->paginate(10),
            'roles' => \Spatie\Permission\Models\Role::all(),
        ]);
    }

}
