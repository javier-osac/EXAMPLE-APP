<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' />
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<x-app-layout class="layout">
    <x-slot name="header">
        <h2 clas="pt1"class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Schedule') }}
        </h2>
    </x-slot>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario para agendar cita --}}
    <form method="POST" action="{{ route('agenda.store') }}" class="container mt-4">
        @csrf
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Agendar Cita</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Campo para nombres --}}
                    <div class="col-md-6 mb-3">
                        <label for="nombres" class="font-weight-bold">Nombres</label>
                        <input type="text" id="nombres" class="form-control" placeholder="Ingrese nombres" name="nombres" required>
                    </div>
                    {{-- Campo para correo --}}
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="font-weight-bold">Correo</label>
                        <input type="email" id="correo" class="form-control" placeholder="Ingrese correo" name="correo" required>
                    </div>
                </div>
                <div class="row">
                    {{-- Campo para teléfono --}}
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="font-weight-bold">Teléfono</label>
                        <input type="tel" id="telefono" class="form-control" placeholder="Ingrese teléfono" name="telefono" required>
                    </div>
                    {{-- Selección de tipo de servicio --}}
                    <div class="col-md-6 mb-3">
                        <label for="tiposervicio" class="font-weight-bold">Tipo servicio</label>
                        <select id="tiposervicio" class="form-control" name="tiposervicio" required>
                            <option value="facial">Facial</option>
                            <option value="peluqueria">Peluqueria</option>
                            <option value="barberia">Barberia</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    {{-- Selección de empleado --}}
                    <div class="col-md-6 mb-3">
                        <label for="empleado_id" class="font-weight-bold">Empleado</label>
                        <select id="empleado_id" class="form-control" name="empleado_id" required>
                            <option selected disabled>Seleccione un Empleado</option>
                            @foreach ($lempleado as $empleado)
                                <option value="{{ $empleado->id }}">{{ $empleado->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Campo para fecha --}}
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="font-weight-bold">Fecha disponible</label>
                        <input type="datetime-local" id="fecha" class="form-control" name="fecha" required readonly>
                    </div>
                </div>
                {{-- Botón para agendar cita --}}
                <div class="text-center mb-3">
                    <button type="submit" class="btn btn-primary">
                        Agendar cita
                    </button>
                </div>
                {{-- Contenedor para mostrar el número de citas --}}
                <div id="resultado" class="mt-3"></div>
                {{-- Contenedor del calendario --}}
                <div class="row">
                    <div class="col-md-12">
                        <div id='calendar' class="border rounded shadow-sm" style="height: 200px; width: 45%; margin: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const today = new Date();
        const formattedToday = today.toISOString().slice(0, 10); // Formato de la fecha actual

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Vista inicial del calendario
            headerToolbar: {
                left: 'prev,next today', // Botones de navegación
                center: 'title', // Título del mes
                right: 'dayGridMonth,timeGridWeek,timeGridDay' // Opciones de vista
            },
            editable: true,
            selectable: true,
            validRange: {
                start: formattedToday // Fecha mínima seleccionable
            },
            dateClick: function(info) {
                const selectedDate = info.date;
                const formattedDate = selectedDate.toISOString().slice(0, 16); // Formato de la fecha seleccionada

                document.getElementById('fecha').value = formattedDate; // Actualiza el campo de fecha

                // Llama a contar citas al seleccionar una fecha
                contarCitas();
            },
            events: '/citas' // Cargar las citas desde el servidor
        });

        calendar.render(); // Renderiza el calendario

        // Agrega eventos para contar citas cuando se cambian el empleado o la fecha
        document.getElementById('empleado_id').addEventListener('change', contarCitas);
        document.getElementById('fecha').addEventListener('change', contarCitas);

        function contarCitas() {
            const empleadoId = document.getElementById('empleado_id').value;
            const fecha = document.getElementById('fecha').value;

            // Verifica que se hayan seleccionado empleado y fecha
            if (empleadoId && fecha) {
                fetch('/citas/count', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token de protección CSRF
                    },
                    body: JSON.stringify({ empleado_id: empleadoId, fecha: fecha }) // Envia datos al servidor
                })
                .then(response => response.json())
                .then(data => {
                    // Muestra el número de citas en el contenedor
                    document.getElementById('resultado').innerText = `El empleado tiene ${data.citas} citas en esta fecha.`;
                })
                .catch(error => console.error('Error:', error)); // Manejo de errores
            } else {
                document.getElementById('resultado').innerText = ''; // Limpia el resultado si no hay datos
            }
        }
    });
</script>

<style>
    /* Estilo adicional para el calendario */
    #calendar {
        border: 1px solid #ccc; /* Borde alrededor del calendario */
        border-radius: 8px; /* Esquinas redondeadas */
        overflow: hidden; /* Oculta contenido que sobresalga */
        background: white; /* Fondo blanco */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
    }

    /* Estilos para el toolbar del calendario */
    .fc-toolbar {
        background-color: #007bff; /* Color del fondo */
        color: white; /* Color del texto */
        padding: 10px; /* Espaciado interno */
        border-radius: 8px; /* Esquinas redondeadas */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Sombra */
    }

    /* Estilo para los botones del calendario */
    .fc-button {
        background-color: #28a745; /* Color verde */
        color: white; /* Texto blanco */
        font-size: 0.9em; 
        border: none; /* Sin borde */
        border-radius: 5px; /* Esquinas redondeadas */
        padding: 5px 10px; /* Espaciado interno */
    }

    /* Estilo para eventos seleccionados */
    .selected-event {
        background-color: #007bff; /* Color de fondo para eventos seleccionados */
        color: white; /* Color de texto */
        border: 1px solid #0056b3; /* Borde más oscuro */
    }

    /* Estilos adicionales para campos de entrada */
    input.form-control:focus,
    select.form-control:focus {
        border-color: #007bff; /* Color del borde en foco */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Sombra en foco */
    }

    /* Efecto de hover en el botón */
    button.btn-primary:hover {
        background-color: #0056b3; /* Color más oscuro al pasar el mouse */
        border-color: #004085; /* Borde más oscuro al pasar el mouse */
    }

    /* Estilo para los div con color gris opaco */
    div {
        background-color: rgba(169, 169, 169, 0.4); /* Gris opaco */
        padding: 10px;
        border-radius: 8px;
    }

    /* Asegurarse de que el fondo gris opaco no afecte el calendario */
    #calendar {
        background-color: white !important; /* Fondo blanco específico para el calendario */
    }
</style>

