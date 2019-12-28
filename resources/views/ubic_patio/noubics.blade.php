@extends('layouts.app')
@section('title','No existen ubicaciones')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h1 class="card-title">No hay ubicaciones asignadas aún al Bloque</h1>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <a href="{{ route('bloque.index', Crypt::encrypt($patio_id)) }}" class = 'btn btn-success'>Regresar a bloques</a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop