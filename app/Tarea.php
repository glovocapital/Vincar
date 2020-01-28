<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Tarea extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'tarea_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tarea_fecha_finalizacion', 'tarea_prioridad', 'tarea_hora_termino', 'vin_id', 'user_id', 'tipo_tarea_id', 'tipo_destino_id'
    ];

    public function oneResponsable(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    
    public function oneVin(){
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }
    
    public function oneTipoTarea(){
        $tipoTarea = DB::table('tipo_tareas')
            ->where('tipo_tarea_id', $this->tipo_tarea_id)
            ->first();

        return $tipoTarea->tipo_tarea_descripcion;
    }

    
    public function oneTipoDestino(){
        $tipoDestino = DB::table('tipo_destinos')
            ->where('tipo_destino_id', $this->tipo_destino_id)
            ->first();

        return $tipoDestino->tipo_destino_descripcion;
    }
    
    public function nombreResponsable(){
        return $this->oneResponsable->user_nombre.' '.$this->oneResponsable->user_apellido;
    }

    public function codigoVin(){
        return $this->oneVin->vin_codigo;
    }
}
