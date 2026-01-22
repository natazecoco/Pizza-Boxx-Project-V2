<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user = $this->record;

        // Tentukan pintu berdasarkan role-nya
        $guard = ($user->role === 'customer') ? 'web' : 'employee';

        // Cari role-nya secara spesifik di pintu tersebut, baru pasangkan
        $role = \Spatie\Permission\Models\Role::where('name', $user->role)
            ->where('guard_name', $guard)
            ->first();

        if ($role) {
            $user->syncRoles([$role]);
        }
    }
}