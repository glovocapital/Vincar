@extends('layouts.app')
@section('title','No existen bloques')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">No hay bloques asignados aún al Patio</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <a href="{{ route('patio.index') }}" class = 'btn btn-success'>Regresar</a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop