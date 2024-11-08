<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Mostrar la lista de usuarios
    public function show()
    {
        $users = User::all(); // Obtiene todos los usuarios de la base de datos
        return view('roles.index', ['users' => $users]); // Retorna la vista con la lista de usuarios
    }

    // Actualizar el rol de un usuario
    public function update(Request $request, $id)
    {
        // Encuentra al usuario por su ID
        $user = User::findOrFail($id);

        // Valida el rol recibido del formulario
        $role = $request->input('role');

        // Verifica si el rol es uno válido
        if (!in_array($role, ['admin', 'user'])) {
            return redirect()->route('roles.index')->with('error', 'Rol no válido.');
        }

        // Asigna el rol al usuario (y elimina otros roles previos)
        $user->syncRoles($role);

        // Redirige con mensaje de éxito
        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }
}
