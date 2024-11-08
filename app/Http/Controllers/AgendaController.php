<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; // Para manipular fechas
use App\Models\Agenda; // Modelo de Agenda
use App\Models\Empleado; // Modelo de Empleado

class AgendaController extends Controller
{
    /**
     * Método para almacenar una nueva cita.
     */
    public function store(Request $request)
    {
        // Validando los inputs
        $validatedData = $request->validate([
            'nombres' => 'required|string|max:255',
            'correo' => 'required|string|email',
            'telefono' => 'required|string|max:15',
            'tiposervicio' => 'required|string|max:50',
            'fecha' => 'required|string|max:50',
            'empleado_id' => 'required|exists:empleados,id', // Validar que el empleado exista
        ]);

        // Parseando y formateando la fecha con Carbon
        $fecha = Carbon::parse($request->input('fecha'))->format('Y-m-d H:i:s');

        // Creando la cita
        Agenda::create([
            'nombres' => $validatedData['nombres'],
            'correo' => $validatedData['correo'],
            'telefono' => $validatedData['telefono'],
            'tiposervicio' => $validatedData['tiposervicio'],
            'fecha' => $fecha,
            'empleado_id' => $validatedData['empleado_id'],
        ]);

        // Mensaje de éxito
        session()->flash('success', 'Cita agendada exitosamente.');
        return redirect()->route('agenda.index');
    }

    /**
     * Método para mostrar la vista de agenda.
     */
    public function show()
    {
        $lempleado = Empleado::all(); // Obtiene todos los empleados
        return view('agenda.index', ['lempleado' => $lempleado]); // Retorna la vista
    }

    /**
     * Método para contar citas de un empleado en una fecha específica.
     */
    public function contarCitas(Request $request)
    {
        // Validación de datos
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
        ]);

        // Contar citas para el empleado en la fecha proporcionada
        $numeroDeCitas = Agenda::where('empleado_id', $request->empleado_id)
            ->whereDate('fecha', Carbon::parse($request->fecha)) // Verifica la fecha
            ->count();

        return response()->json(['citas' => $numeroDeCitas]); // Devuelve el número de citas
    }

    /**
     * Método para mostrar la lista de citas.
     */
    public function mostrarLista()
    {
        $citas = Agenda::all(); // Obtiene todas las citas
        return view('agendacitas.index', ['citas' => $citas]); // Retorna la vista con la lista de citas
    }

    /**
     * Método para mostrar el formulario de edición de una cita.
     */
    public function Mostraredit($id)
    {
        // Intenta encontrar la cita por su ID
        $lagenda = Agenda::findOrFail($id);
        $lempleado = Empleado::all(); // Obtiene todos los empleados
        
        // Retorna la vista con la variable $lagenda y $lempleado
        return view('editaragenda.index', [
            'lagenda' => $lagenda,
            'lempleado' => $lempleado
        ]);
    }

    /**
     * Método para actualizar una cita.
     */
    public function update(Request $request, $id) 
    {
        // Valida los datos enviados en el formulario
        $request->validate([
            'nombres' => 'required|string|max:255',
            'correo' => 'required|string|email',
            'telefono' => 'required|string|max:15',
            'tiposervicio' => 'required|string|max:50',
            'fecha' => 'required|string|max:50',
            'empleado_id' => 'required|exists:empleados,id', // Validar que el empleado exista
        ]);

        // Busca la cita por su ID
        $lagenda = Agenda::findOrFail($id);

        try {
            // Actualiza los datos de la cita
            $lagenda->update($request->all());

            // Mensaje de éxito
            session()->flash('success', 'Datos actualizados correctamente');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Ocurrió un error al actualizar los datos. Por favor, intenta de nuevo.');
        }

        // Retorna a la lista de citas
        return redirect()->route('agendacitas.index');
    }

    /**
     * Método para eliminar una cita.
     */
    public function destroy($id)
    {
        $cita = Agenda::findOrFail($id);
        $cita->delete(); // Elimina la cita

        session()->flash('success', 'Cita eliminada correctamente');
        return redirect()->route('agendacitas.index');
    }

    /**
     * Método para obtener todas las citas en formato JSON.
     */
    public function obtenerCitas()
    {
        // Obtener todas las citas y darles formato
        $citas = Agenda::all()->map(function ($cita) {
            return [
                'title' => $cita->nombres . ' - ' . $cita->tiposervicio, // Título de la cita
                'start' => $cita->fecha, // Fecha de la cita
                'empleado_id' => $cita->empleado_id, // ID del empleado
            ];
        });

        return response()->json($citas); // Retornar las citas como JSON
    }
}
