<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{ 
    use HasFactory;

    protected $table = 'agendas'; // Cambiar a 'agendas' si estás siguiendo convenciones

    protected $fillable = [
        'nombres',
        'correo',
        'telefono',
        'tiposervicio',
        'fecha',
        'empleado_id', // No se necesita 'empleado' aquí
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}





