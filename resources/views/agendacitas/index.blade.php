<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="mt-2 text-xl text-center text-gray-800 font-semibold">Lista de citas</h2>
                <div class="col-12">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Nombres</th>
                                <th scope="col">Empleado</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Tipo Servicio</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($citas as $cita)
                                <tr>
                                    <th scope="row">{{ $cita->nombres }}</th>
                                    <td>{{ $cita->empleado->nombres ?? 'N/A' }}</td>
                                    <td>{{ $cita->correo }}</td>
                                    <td>{{ $cita->telefono }}</td>
                                    <td>{{ $cita->tiposervicio }}</td>
                                    <td>{{ $cita->fecha }}</td>
                                    <td>
                                        <a href="{{ route('editaragenda.index', $cita->id) }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                            </svg>
                                            Editar
                                        </a>

                                        <button onclick="confirmDelete('{{ route('agenda.destroy', $cita->id) }}')" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Eliminar
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',  <!-- Color actualizado -->
            cancelButtonColor: '#d33',  <!-- Color actualizado -->
            confirmButtonText: 'Sí, eliminarlo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.action = deleteUrl;
                form.method = 'POST';

                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    @if (session('success'))
        Swal.fire({
            title: 'Actualizado',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'  <!-- Color de éxito -->
        });
    @endif

    @if (session('error'))
        Swal.fire({
            title: 'Error',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'  <!-- Color de error -->
        });
    @endif
</script>

<!-- Custom Styles -->
<style>
    /* Estilos para la tabla */
    .table th {
        background-color: #f3f4f6; /* Fondo gris claro para encabezados */
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9fafb; /* Color claro para filas impares */
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff; /* Blanco para filas pares */
    }

    /* Botón de editar */
    .btn-primary {
        background-color: #3085d6; /* Azul para el botón de editar */
        border-color: #3085d6;
    }

    .btn-primary:hover {
        background-color: #1d75b9;
        border-color: #1d75b9;
    }

    /* Botón de eliminar */
    .btn-danger {
        background-color: #d33; /* Rojo para el botón de eliminar */
        border-color: #d33;
    }

    .btn-danger:hover {
        background-color: #a11a1a;
        border-color: #a11a1a;
    }

    /* Agregar margen entre botones */
    .btn {
        margin-right: 10px;
    }

    /* Estilo de fondo gris claro */
    div {
        background-color: #e5e5e5;
    }
    x-app-layout {
        background-color: #e5e5e5;
    }
</style>
