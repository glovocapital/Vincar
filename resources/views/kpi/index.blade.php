@extends('layouts.app')
@section('title','KPI index')
@section('content')
@include('flash::message')
<iframe width="1140" height="541.25" 
src="{{env('POWER_BI_URL')}} 
frameborder="0" allowFullScreen="true">
</iframe>
@stop