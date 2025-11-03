<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;

class ManageUser extends Component
{
    public $users;
    public $roles;

    public function getRoleColor($roleId)
    {
        if ($roleId == UserRole::ADMIN) {
            return 'bg-blue-100 text-blue-800';
        } elseif ($roleId == UserRole::USER) {
            return 'bg-green-100 text-green-800';
        } else {
            return 'bg-gray-100 text-gray-800';
        }
    }

    public function updatedRoles($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->roles[$index]['id'] ?? null;

        UserRole::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function updatedUsers($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->users[$index]['id'] ?? null;

        User::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function render()
    {
        $this->users = User::orderBy('id')
            ->get()->keyBy('id')->toArray();
        $this->roles = UserRole::orderBy('id')
            ->get()->keyBy('id')->toArray();

        return view('livewire.admin.manage-user');
    }
}
