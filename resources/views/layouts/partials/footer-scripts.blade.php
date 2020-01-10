<script src="{{ asset('base/js/app.js') }}"></script>
<script>
    $(function () {

        if ("#dataTableAusentismo") {
            $(".card-tools").hide();

            var datatablesButtons = $("#dataTableAusentismo").DataTable({
                responsive: true,
                lengthChange: !1,
                @if(Session::get('lang')=="es")
                language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                 },
                @endif
                buttons: ["copy", "print"],

            });

            datatablesButtons.buttons().container().appendTo("#datatables-buttons_wrapper .col-md-6:eq(0)");

        }

    });


</script>