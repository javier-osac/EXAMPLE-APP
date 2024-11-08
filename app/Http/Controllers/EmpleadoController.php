<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  view('empleado.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   // validando los inputs
        $validatedData = $request->validate([
            'identificacion' => 'required|string|max:255|unique:empleados',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'tipocontrato' => 'required|string|max:50',
            'datesemana'=> 'required|date|after:today'
        ]);
        //return del modelo departamento insert';
        Empleado::create([
            'identificacion'=>request('identificacion'), //del campo,del name del input
            'nombres'=>request('nombres'),
            'apellidos'=>request('apellidos'),
            'correo'=>request('correo'),
            'direccion'=>request('direccion'),
            'telefono'=>request('telefono'),
            'tipocontrato'=>request('tipocontrato'),
            'datesemana'=> request('datesemana'),

        ]);
        //mensaje
        session()->flash('success', 'Empleado guardado correctamente');

        //retornar a la ruta
         return to_route('empleado.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $lempleado)
    {
        $lempleado = Empleado::all();
        return  view('listaempleado.index',['lempleado' => $lempleado]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Intenta encontrar el empleado por su ID
        $empleado = Empleado::findOrFail($id);
        
        // Retorna la vista con la variable $empleado
        return view('editarempleado.index', ['empleado' => $empleado]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) 
    {
        // Valida los datos enviados en el formulario
        $request->validate([
            'identificacion' => 'required',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'tipocontrato' => 'required|string',
            'datesemana' => 'required|date|after:today',
        ]);

        // Busca el empleado por su ID
        $empleado = Empleado::findOrFail($id);

        try {
            // Actualiza los datos del empleado con los valores enviados en el formulario
            $empleado->update($request->all());

            // Mensaje de éxito
            session()->flash('success', 'Datos actualizados correctamente');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Ocurrió un error al actualizar los datos. Por favor, intenta de nuevo.');
        }

        // Retorna a la vista de lista de empleados
        return to_route('listaempleado.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();
        return to_route('listaempleado.index');

    }
    
    
    
}
