<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' />
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('editar cita') }}
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

    {{-- Formulario para editar cita --}}
    <form method="POST" action="{{ route('updateagenda.update', $lagenda->id) }}" class="container mt-4">
        @method('PUT') {{-- Indica que el método es PUT --}}
        @csrf {{-- Protección contra CSRF --}}
         
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Actualizar Cita</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Campo para nombres --}}
                    <div class="col-md-6 mb-3">
                        <label for="nombres" class="font-weight-bold">Nombres</label>
                        <input type="text" id="nombres" class="form-control" placeholder="Ingrese nombres" name="nombres" required value="{{ $lagenda->nombres }}">
                    </div>
                    {{-- Campo para correo --}}
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="font-weight-bold">Correo</label>
                        <input type="email" id="correo" class="form-control" placeholder="Ingrese correo" name="correo" required value="{{ $lagenda->correo }}">
                    </div>
                </div>
                <div class="row">
                    {{-- Campo para teléfono --}}
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="font-weight-bold">Teléfono</label>
                        <input type="tel" id="telefono" class="form-control" placeholder="Ingrese teléfono" name="telefono" required value="{{ $lagenda->telefono }}">
                    </div>
                    {{-- Selección de tipo de servicio --}}
                    <div class="col-md-6 mb-3">
                        <label for="tiposervicio" class="font-weight-bold">Tipo servicio</label>
                        <select id="tiposervicio" class="form-control" name="tiposervicio" required>
                            <option value="facial" {{ $lagenda->tiposervicio == 'facial' ? 'selected' : '' }}>Facial</option>
                            <option value="peluqueria" {{ $lagenda->tiposervicio == 'peluqueria' ? 'selected' : '' }}>Peluqueria</option>
                            <option value="barberia" {{ $lagenda->tiposervicio == 'barberia' ? 'selected' : '' }}>Barberia</option>
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
                                <option value="{{ $empleado->id }}" {{ $empleado->id == $lagenda->empleado_id ? 'selected' : '' }}>{{ $empleado->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Campo para fecha --}}
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="font-weight-bold">Fecha disponible</label>
                        <input type="datetime-local" id="fecha" class="form-control" name="fecha" required value="{{ \Carbon\Carbon::parse($lagenda->fecha)->format('Y-m-d\TH:i') }}">
                    </div>
                </div>
                {{-- Botón para actualizar cita --}}
                <div class="text-center mb-3">
                    <button type="submit" class="mt-2 bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                        Actualizar Datos
                    </button>
                </div>
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
        const formattedToday = today.toISOString().slice(0, 10); // Formato: YYYY-MM-DD

        // Inicialización del calendario
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: true,
            selectable: true,
            validRange: {
                start: formattedToday // Fecha de inicio como hoy
            },
            dateClick: function(info) {
                const selectedDate = info.date;
                const formattedDate = selectedDate.toISOString().slice(0, 16); // Formato: YYYY-MM-DDTHH:MM

                // Actualiza el campo de entrada de fecha
                document.getElementById('fecha').value = formattedDate;

                // Agrega un evento al calendario
                calendar.addEvent({
                    title: `Seleccionado: ${formattedDate}`,
                    start: info.date,
                    end: info.date,
                    classNames: ['selected-event']
                });

                calendar.unselect(); // Desmarcar la selección
            }
        });

        calendar.render(); // Renderiza el calendario

        // Evento para abrir el calendario al hacer clic en el campo de fecha
        document.getElementById('fecha').addEventListener('click', function() {
            calendar.render();
            calendar.gotoDate(new Date());
        });
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
</style>
