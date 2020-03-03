<script src="{{ asset('base/js/app.js') }}"></script>
<script>
    $(function () {

        if ($(".card-tools").length > 0)
            $(".card-tools").hide();

        if ($('[id*="dataTable"]').length > 0) {

            var datatablesButtons = $('[id*="dataTable"]').DataTable({
                responsive: true,
                lengthChange: !1,
                pageLength: 100,
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
