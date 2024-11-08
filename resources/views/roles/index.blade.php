<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asignar Roles a Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-xl font-semibold mb-4">Lista de Usuarios</h1>

                    <!-- Mostrar mensaje de éxito si existe -->
                    @if(session('success'))
                        <div class="bg-gray-500 text-white p-4 mb-4 rounded-md shadow-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Rol</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-4 py-2">{{ $user->id }}</td>
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">
                                        <form action="{{ route('roles.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" onchange="this.form.submit()" class="bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>Usuario</option>
                                                <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2">
                                        <button type="submit" class="mt-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            Actualizar Datos
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Custom Styles -->
<style>
    /* Estilo para la tabla */
    .table th {
        background-color: #f3f4f6; /* Fondo gris claro para los encabezados de la tabla */
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9fafb; /* Color claro para las filas impares */
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff; /* Blanco para las filas pares */
    }

    /* Estilo para los botones */
    .btn-primary {
        background-color: #3085d6; /* Color azul para los botones */
        border-color: #3085d6;
    }

    .btn-primary:hover {
        background-color: #1d75b9;
        border-color: #1d75b9;
    }

    .btn-danger {
        background-color: #d33; /* Color rojo para los botones de eliminar */
        border-color: #d33;
    }

    .btn-danger:hover {
        background-color: #a11a1a;
        border-color: #a11a1a;
    }

    /* Botón de actualizar */
    .btn-update {
        background-color: #007bff;
        color: white;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
    }

    .btn-update:hover {
        background-color: #0056b3;
    }

    /* Estilos para los campos del formulario */
    select:focus, input:focus {
        border-color: #007bff; /* Color del borde al enfocar */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Sombra al enfocar */
    }

    /* Agregar margen alrededor de los botones */
    .btn {
        margin-right: 10px;
    }

    /* Fondo gris opaco */
    div {
        background-color: rgba(169, 169, 169, 0.4); /* Fondo gris opaco */
        padding: 10px;
        border-radius: 8px;
    }

    /* Asegurar que el fondo gris no afecte el contenido */
    .table, .btn, select {
        background-color: white !important;
    }
</style>
