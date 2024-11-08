<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listado Empleados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="mt-2 text-xl text-center text text-gray-800 font-semibold">Listado Empleados</h2>
                <div class="col-12">
                    <!-- Tabla -->
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Identificacion</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Direccion</th>
                                <th scope="col">Telefono</th>
                                <th scope="col">Tipo contrato</th>
                                <th scope="col">fecha disponible</th>
                                <th scope="col">Acciones</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $secuencia = 1; @endphp
                            @foreach ($lempleado as $empleado)
                            <tr>
                                <th scope="row">{{ $empleado->identificacion }}</th>
                                <td>{{ $empleado->nombres }}</td>
                                <td>{{ $empleado->apellidos }}</td>
                                <td>{{ $empleado->correo }}</td>
                                <td>{{ $empleado->direccion }}</td>
                                <td>{{ $empleado->telefono }}</td>
                                <td>{{ $empleado->tipocontrato }}</td>
                                <td>{{ $empleado->datesemana }}</td>
                                <td>
                                    <a href="{{ route('editarempleado', $empleado->id) }}" class="btn btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                        </svg>
                                        Editar
                                    </a>
                                </td>

                                <td>
                                    <button onclick="confirmDelete('{{route('deletempleado.destroy', $empleado->id) }}')"class="btn btn-danger">
                                        <i class="bi bi-pencil"></i> Eliminar
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

<script>
    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Crear un formulario dinámicamente
                var form = document.createElement('form');
                form.action = deleteUrl;
                form.method = 'POST';

                // Agregar token CSRF
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}'; // Usar Blade para obtener el token CSRF

                // Agregar input de método
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);

                // Agregar el formulario al cuerpo y enviarlo
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Actualizado',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
            title: 'Error',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<style>
    /* Fondo gris opaco para div */
    div {
        background-color: rgba(169, 169, 169, 0.4); /* Gris opaco */
    }

    /* Asegurarse de que el fondo no afecte el contenido */
    .bg-white {
        background-color: #ffffff !important; /* Fondo blanco para el contenido */
    }

    .bg-gray-800 {
        background-color: #2d3748 !important; /* Fondo gris oscuro para el modo oscuro */
    }
</style>
