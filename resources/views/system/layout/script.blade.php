<script>
    var base_api_url = '{{ env('SB_API_URL', 'http://shopbay.service') }}';
    var app_url = '{{ env('APP_URL', 'http://localhost') }}';
    var sbOptions = {!! json_encode($GLOBALS['shopbay_options'])  !!};

</script>

<script src="/system/js/scripts/jquery.min.js"></script>
<script src="/system/js/scripts/chosen-add-option.js"></script>
<script src="/system/js/scripts/bootstrap.min.js"></script>
<script src="/system/js/scripts/fastclick.js"></script>
<script src="/system/js/scripts/adminlte.min.js"></script>
<script src="/system/js/scripts/jquery.slimscroll.min.js"></script>
<script src="/system/js/scripts/angular.min.js"></script>
<script src="/system/js/scripts/angular-sanitize.min.js"></script>
<script src="/system/js/scripts/angular-animate.min.js"></script>
<script src="/system/js/scripts/angular-chosen.min.js"></script>
<script src="/system/js/scripts/ng-file-upload-shim.min.js"></script>
<script src="/system/js/scripts/ng-file-upload.min.js"></script>
<script src="/system/js/scripts/dynamic-number.min.js"></script>
<script src="/system/js/scripts/angular-material.min.js"></script>
<script src="/system/js/scripts/angular-aria.min.js"></script>
<script src="/system/js/scripts/angular-messages.min.js"></script>
<script src="/system/js/scripts/system.js"></script>
<script src="/system/js/scripts/storage.js"></script>
<script src="/system/js/controllers/base-controller.js?v=<?=env('APP_VERSION')?>"></script>
<script src="/system/js/scripts/sweetalert2.min.js"></script>
<script src="/system/js/scripts/toastr.min.js"></script>
<script src="/system/js/scripts/jquery-ui.min.js"></script>
<script src="/system/js/scripts/sortable.min.js"></script>


<script>
    $(document).on('collapsed.pushMenu', function (){
        $('.sidebar-toggle').css('background-color', 'transparent');
    })
    $(document).on('expanded.pushMenu', function (){
        $('.sidebar-toggle').css('background-color', '');
    })
</script>

@yield('script')
