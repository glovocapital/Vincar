@extends('layouts.app')
@section('title','KPI index')
@section('content')
@include('flash::message')
<iframe width="1140" height="541.25" 
src="https://app.powerbi.com/reportEmbed?reportId=45dde9c4-635d-4aee-836c-8c8f537ad366&autoAuth=true&ctid=14502b1f-be69-40c6-b952-baf9e6900241&config=eyJjbHVzdGVyVXJsIjoiaHR0cHM6Ly93YWJpLXBhYXMtMS1zY3VzLXJlZGlyZWN0LmFuYWx5c2lzLndpbmRvd3MubmV0LyJ9" 
frameborder="0" allowFullScreen="true">
</iframe>
@stop