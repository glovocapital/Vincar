<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<meta name="author" content="Vinca CL">
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

</style>
<script  src="{{ asset('base/js/settings.js') }}"></script>

<link href="{{ asset('base/css/classic.css') }}" type="text/css" rel="stylesheet">
