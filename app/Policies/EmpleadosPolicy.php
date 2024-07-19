<?php

namespace App\Policies;

use App\Models\Empleados;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmpleadosPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ( $user->hasPermissionTO('Ver')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Empleados $empleados): bool
    {
        if ( $user->hasPermissionTO('Ver')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTO('Crear')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Empleados $empleados): bool
    {
        if ($user->hasPermissionTO('Editar')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Empleados $empleados): bool
    {
        if ( $user->hasPermissionTO('Ver','Crear','Editar')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Empleados $empleados): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Empleados $empleados): bool
    {
        //
    }
}
