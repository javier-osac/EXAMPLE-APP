<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Pest\Plugins\Profile;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\RoleController;

Route::get('/chirps1', function () {
    return 'este es una pagina';
});

Route::get('/chirps', function () {
    return view('chirps.index');
})->name('chirps.index');




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/empleado', function () {
        return view('chirps.index');
    })->name('chirps.index');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/empleado',[EmpleadoController::class,'index'])->name('empleado.index');
Route::post('/empleado', [EmpleadoController::class,'store'])->name('empleado.store');

Route::get('/listaempleado',[EmpleadoController::class,'show'])->name('listaempleado.index');

// Ruta para editar empleado
Route::get('/editarempleado/{id}', [EmpleadoController::class, 'edit'])->name('editarempleado');

// Ruta para actualizar los datos
Route::put('/updatempleado/{id}', [EmpleadoController::class, 'update'])->name('updatempleado.update');


Route::delete('/deletempleado/{id}', [EmpleadoController::class,'destroy'])->name('deletempleado.destroy');


Route::get('/agenda', function () {
    return view('agenda.index');
    })->name('agenda.index');

     // Página de agendar
  // Página de agendar
Route::get('/agenda', [AgendaController::class, 'show'])->name('agenda.index');

// Ruta para almacenar la agenda
Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');

// Ruta para mostrar la lista de citas
Route::get('/agendacitas', [AgendaController::class, 'mostrarLista'])->name('agendacitas.index');

// Ruta para editar una cita
Route::get('/editaragenda/{id}', [AgendaController::class, 'Mostraredit'])->name('editaragenda.index');

// Ruta para actualizar una cita
Route::put('/updateagenda/{id}', [AgendaController::class, 'update'])->name('updateagenda.update');

// Ruta para eliminar una cita
Route::delete('/agenda/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');

// Ruta para contar citas
Route::post('/citas/count', [AgendaController::class, 'contarCitas']);

// Ruta para obtener todas las citas
Route::get('/citas', [AgendaController::class, 'obtenerCitas']); // Nueva ruta para obtener citas
// Ruta para asignar roles

Route::get('/roles', [RoleController::class, 'show'])->name('roles.index');
// ruta para actualizar role
Route::put('/updaterole/{id}', [RoleController::class, 'update'])->name('roles.update');

 });

require __DIR__.'/auth.php';
