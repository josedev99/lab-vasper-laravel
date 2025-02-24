<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>

<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables-buttons/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables-buttons/js/buttons.html5.min.js') }}"></script>

<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
<!---CUSTOM STYLE INPUT ---->
<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>
@routes
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script>
    window.axios = axios;
</script>
<script src="{{ asset('assets/vendor/selectize/selectize.min.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpicker/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpicker/es.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpicker/plugin/monthSelect.js') }}"></script>
{{-- Mis js --}}
<script src="{{ asset('assets/js/DataTable.js') }}"></script>
<script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
{{-- Fullcalendar --}}
<script src="{{ asset('assets/vendor/fullcalendar/index.global.min.js') }}"></script>
<script src="{{ asset('assets/vendor/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendor/moment/moment-timezone.min.js') }}"></script>
{{-- HTML2CANVAS --}}
<script src="{{ asset('assets/vendor/html2canvas/html2canvas.min.js') }}"></script>
<script src="{{ asset('assets/js/templateResultExamen/tableResultExamen.js') }}"></script>
<script>
    const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
</script>

@stack('js')