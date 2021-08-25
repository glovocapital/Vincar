<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<meta name="author" content="Vincar CL">
<link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}">

<title>Cloud Warehouse & Transport</title>


<style>
    body {
        opacity: 0;
    }
    .borderless td, .borderless th {
        border: none !important;
        padding: 0px !important;
    }
    .card {
        box-shadow: 0px 2px 4px -1px rgba(0, 0, 0, 0.2), 0px 4px 5px 0px rgba(0, 0, 0, 0.14), 0px 1px 10px 0px rgba(0, 0, 0, 0.12) !important;
        transition: box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .card-header{
        border-bottom:  solid lightgray 2px !important;

    }
    .card-title{
        font-size: 24px !important;
        margin-bottom: 0px !important;
    }
    label{
        font-weight: bold !important;
    }

    .fa-edit{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content: url({{ asset('base/img/svg/edit_1.svg') }})  !important;
        margin-right: 5px;
    }
    .fa-edit::before{
        content: none !important;
    }

    .fa-info-circle{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content: url({{ asset('base/img/svg/info_icon.svg') }}) !important;
        margin-right: 5px;
    }
    .fa-info-circle::before{
        content: none !important;
    }

    .fa-trash-alt{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content: url({{ asset('base/img/svg/deleted_1.svg') }}) !important;
        margin-right: 5px;
    }
    .fa-trash-alt::before{
        content: none !important;
    }

    .fa-flag-checkered{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content: url({{ asset('base/img/svg/historial_1.svg') }})  !important;
        margin-right: 5px;
    }
    .fa-flag-checkered::before{
        content: none !important;
    }

    .fa-barcode{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content: url({{ asset('base/img/svg/guia_1.svg') }})  !important;
        margin-right: 5px;
    }
    .fa-barcode2{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content: url({{ asset('base/img/svg/guia_2.svg') }})  !important;
        margin-right: 5px;
    }
    .fa-barcode::before{
        content: none !important;
    }

    .fa-address-book{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content:  url({{ asset('base/img/svg/inspec_1.svg') }})  !important;
        margin-right: 5px;
    }
    .fa-address-book::before{
        content: none !important;
    }

    .fa-lightbulb{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content:  url({{ asset('base/img/svg/inspec_1.svg') }}) !important;
        margin-right: 5px;
    }
    .fa-lightbulb::before{
        content: none !important;
    }

    .fa-history{
        font-family:none !important;
        width: 30px;
        height: 30px;
        content:  url({{ asset('base/img/svg/historial_.svg') }}) !important;
        margin-right: 5px;
    }
    .fa-lightbulb::before{
        content: none !important;
    }
    /* Agregado por Kamal
      la funcionalidad es que cada objeto que tenga el class blink parpadee
    */
    @-webkit-keyframes blinker {
      from {opacity: 1.0;}
      to {opacity: 0.0;}
    }
    .blink{
    	text-decoration: blink;
    	-webkit-animation-name: blinker;
    	-webkit-animation-duration: 0.6s;
    	-webkit-animation-iteration-count:infinite;
    	-webkit-animation-timing-function:ease-in-out;
    	-webkit-animation-direction: alternate;
    }
    .lineas td {
            border-top: 1px solid #dddddd;
            border-bottom: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
          }






    /*
    .fa-edit{
        color: #fad430;
        font-size: 16px !important;
        margin-right: 5px;
    }

    .fa-trash-alt{
        color: #ca4949;
        font-size: 16px !important;
    }
    */

    /* Important part */
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        height: 450px;
        overflow-y: auto;
    }

</style>
<script  src="{{ asset('base/js/settings.js') }}"></script>

<link href="{{ asset('base/css/classic.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('css/buttons.dataTables.min.css') }}" type="text/css" rel="stylesheet">
@yield('custom_styles')
