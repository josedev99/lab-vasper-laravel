<!-- Vendor CSS Files -->
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/vendor/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables-responsive/css/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables-buttons/css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">

<!-- Template Main CSS File -->
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/vendor/selectize/selectize.default.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/flatpicker/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/flatpicker/plugin/monthSelect.css') }}">
<link href="{{ asset('assets/css/input.css') }}?v={{rand()}}" rel="stylesheet">
<link href="{{ asset('assets/css/loader.css') }}?v={{rand()}}" rel="stylesheet">
{{-- Icheck css --}}
<link rel="stylesheet" href="{{ asset('assets/vendor/icheck/icheck-bootstrap.min.css') }}">
{{-- Loading button css --}}
<link rel="stylesheet" href="{{ asset('assets/vendor/loading_button/loading_button.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style_result_examen.css') }}">
<style>
    /* DataTables control button */
    .page-link{
        font-size: 13px !important;
    }
    .dataTables_info{
        font-size: 15px !important;
    }
    /*Flatpicker*/
    .flatpickr-current-month{
        display: flex;
        flex-direction: row-reverse;
    }
</style>

@stack('css')