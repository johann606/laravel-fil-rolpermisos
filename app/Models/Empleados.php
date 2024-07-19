<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo_identificacion',
        'numero_identificacion',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'telefono',
        'correo_electronico',
        'cargo',
        'fecha_ingreso',
        'fecha_retiro',
        'salario_basico',
        'tipo_contrato',
        'codigo_trabajador',
        'tipo_cotizante',
        'subtipo_cotizante',
        'pensionado',
        'direccion',
        'ciudad',
        'departamento',
    ];

}
