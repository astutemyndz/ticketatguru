<!-- RIGHT SIDEBAR -->
<!-- ========================================================= -->
<div class="right-sidebar">
    <div class="right-sidebar-toggle" data-toggle-class="right-sidebar-opened" data-target="html">
        <i class="fa fa-cog fa-spin" aria-hidden="true"></i>
    </div>
    <div id="right-nav" class="nano">
        <div class="nano-content">
            <div class="template-settings">
                <h4 class="text-center">Template Settings</h4>
                <ul class="toggle-settings pv-xlg">
                    <li>
                        <h6 class="text">Header fixed</h6>
                        <label class="switch">
                            <input id="header-fixed" type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </li>
                    <li>
                        <h6 class="text">Content header fixed</h6>
                        <label class="switch">
                            <input id="content-header-fixed" type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </li>
                    <li>
                        <h6 class="text">Left sidebar collapsed</h6>
                        <label class="switch">
                            <input id="left-sidebar-collapsed" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </li>
                    <li>
                        <h6 class="text">Left sidebar Top</h6>
                        <label class="switch">
                            <input id="left-sidebar-top" type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </li>
                    <li>
                        <h6 class="text">Left sidebar fixed</h6>
                        <label class="switch">
                            <input id="left-sidebar-fixed" type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </li>
                    <li>
                        <h6 class="text">Left sidebar over</h6>
                        <label class="switch">
                            <input id="left-sidebar-over" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </li>
                    <li>
                        <h6 class="text">Left sidebar nav left-lines</h6>
                        <label class="switch">
                            <input id="left-sidebar-left-lines" type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--scroll to top-->
<a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
</div>
</div>
<!--BASIC scripts-->
<!-- ========================================================= -->
<script src="<?=base_url()?>backend/vendor/jquery/jquery-1.12.3.min.js?t<?=time()?>"></script>
<script>
    var base_url = "<?= base_url() ?>";
</script>
<script src="<?php echo base_url(); ?>backend/javascripts/common-script.js?t<?=time()?>"></script>
<script src="<?=base_url()?>backend/vendor/bootstrap/js/bootstrap.min.js?t<?=time()?>"></script>
<script src="<?=base_url()?>backend/vendor/nano-scroller/nano-scroller.js?t<?=time()?>"></script>
<!--TEMPLATE scripts-->
<!-- ========================================================= -->
<script src="<?=base_url()?>backend/javascripts/template-script.min.js?t<?=time()?>"></script>
<script src="<?=base_url()?>backend/javascripts/template-init.min.js?t<?=time()?>"></script>
<script src="<?=base_url()?>backend/vendor/sweetalert/sweetalert.min.js"></script>
<!-- SECTION script and examples-->
<!-- ========================================================= -->
<!--Notification msj-->
<script src="<?=base_url()?>backend/vendor/toastr/toastr.min.js?t<?=time()?>"></script>
<!--Gallery with Magnific popup-->
<script src="<?=base_url()?>backend/vendor/magnific-popup/jquery.magnific-popup.min.js?t<?=time()?>"></script>
<!--Examples-->
<script src="<?=base_url()?>backend/javascripts/examples/dashboard.js?t<?=time()?>"></script>
<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<?php if ($this->session->flashdata('success_msg')) { ?>
<script>
    $('.alert-success').delay(3000).fadeOut(300);
</script>
<?php } ?>

<?php if ($this->session->flashdata('error_msg')) { ?>
<script>
    $('.alert-danger').delay(8000).fadeOut(300);
</script>
<?php } ?>
</body>


<!-- Mirrored from myiideveloper.com/helsinki/last-version/helsinki_green-dark/src/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2019 13:51:06 GMT -->

</html>