<!-- jQuery -->
<script src="{{ asset('_adminLTE/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('_adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Boostrap Table -->
<script src="{{ asset('_adminLTE/plugins/bootstrap-table/extensions/export/tableExport.min.js') }}"></script>
<script src="{{ asset('_adminLTE/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('_adminLTE/plugins/bootstrap-table/extensions/export/bootstrap-table-export.min.js') }}"></script>
<script src="{{ asset('_adminLTE/plugins/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js') }}"></script>
<script src="{{ asset('_adminLTE/plugins/bootstrap-table/extensions/filter-control/bootstrap-table-filter-control.min.js') }}"></script>
<script src="{{ asset('_adminLTE/plugins/bootstrap-table/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('_adminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('_adminLTE/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('_adminLTE/plugins/inputmask/jquery.inputmask.bundle.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('_adminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- date-picker -->
<script src="{{ asset('_adminLTE/plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- timepicker -->
<script src="{{ asset('_adminLTE/plugins/timepicker/jquery.timepicker.min.js') }}"></script>
<!-- dropzonejs -->
<script src="{{ asset('_adminLTE/plugins/dropzone/min/dropzone.min.js') }}"></script>
<!-- select2 -->
<script src="{{ asset('_adminLTE/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('_adminLTE/plugins/sweetalert2/sweetalert2@11.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('_adminLTE/plugins/toastr/toastr.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('_adminLTE/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('_adminLTE/dist/js/adminlte.js') }}"></script>
<!-- Master -->
<script src="{{ asset('assets/master/master.js') }}"></script>
<script src="{{ asset('assets/master/navigation_scroll.js') }}"></script>

{{-- <script src="{{ asset('sw.js') }}"></script> --}}
<script nonce="{{ csp_nonce() }}">
// if (!navigator.serviceWorker.controller) {
//     navigator.serviceWorker.register("sw.js").then(function (reg) {
//         console.log("Service worker has been registered for scope: " + reg.scope);
//     });
// }

$('.nav-item.dropdown').hover(function() {
    $(this).find('.nav-link').dropdown('toggle');
});
</script>

@livewireScripts

