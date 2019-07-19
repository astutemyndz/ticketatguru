<?php $this->load->view('admin/layout/header'); ?>
    <!-- side bar -->
    <?php $this->load->view('admin/layout/sidemenu'); ?>

    <!-- CONTENT -->
    <!-- ========================================================= -->
    <div class="content">
        <!-- content HEADER -->
        <!-- ========================================================= -->
        <div class="content-header">
            <div class="leftside-content-header">
                <ul class="breadcrumbs">
                    <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?=base_url('admin/dashboard')?>">Dashboard</a></li>
                    <li><a><?php if($post_attr['mode'] == 'add'){ echo 'Add'; }else{ echo 'Edit'; }?> Question</a></li>
                </ul>
            </div>
        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <div class="row animated fadeInUp">
            <div class="col-sm-12 col-md-12">
                <?php if($this->session->flashdata('error_msg')){ ?>
                <div class="alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert">×</a>
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                    <?=$this->session->flashdata('error_msg')?>
                </div>
                <?php } ?>
                <div class="panel">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="<?=base_url('admin/settings/questions')?>" class="form-horizontal form-validation" method="post">
                                    <input type="hidden" name="mode" value="<?php if(isset($post_attr) && $post_attr['mode']){ echo $post_attr['mode']; } ?>">
                                    <input type="hidden" name="id" value="<?php if(isset($post_attr) && $post_attr['id']){ echo $post_attr['id']; } ?>">
                                    <div class="form-group">
                                        <label for="select2-example-basic" class="col-sm-3 control-label">Question Category *</label>
                                        <div class="col-sm-6">
                                            <select name="faq_category_id" id="select2-example-basic" class="form-control requiredCheck" style="width: 100%">
                                                <option value="">Select a category</option>
                                                <?php if(isset($data_faq_category) && !empty($data_faq_category)){
                                                    foreach($data_faq_category as $faq_cat){
                                                ?>
                                                <option <?php if(isset($edit_data) && $edit_data['cat_id'] == $faq_cat['id']){ echo 'selected'; } ?> value="<?=$faq_cat['id']?>"><?=$faq_cat['name']?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email2" class="col-sm-3 control-label">Question *</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="faq_question" value="<?php if(isset($edit_data) && $edit_data['question']){ echo stripslashes($edit_data['question']); } ?>" class="form-control requiredCheck">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-3 control-label">Answer *</label>
                                        <div class="col-sm-8">
                                            <textarea id="editor1" class="form-control" name="faq_answer" rows="4"><?php if(isset($edit_data) && $edit_data['answer']){ echo stripslashes($edit_data['answer']); } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-8">
                                            <button type="submit" class="btn btn-primary form-submit-button"><?php if($post_attr['mode'] == 'add'){ ?>Save<?php }else{ ?>Save Changes<?php } ?></button>
                                            <button type="button" onclick="location.href='<?=base_url('admin/settings/questions')?>';" class="btn btn-danger">Cancel</button>
                                            <button type="button" class="status-message btn btn-wide btn-o btn-danger" style="display:none;"></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->

        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    </div>

<?php $this->load->view('admin/layout/footer'); ?>
<script src="<?=base_url()?>backend/vendor/select2/js/select2.min.js"></script>
<script src="<?=base_url()?>backend/ckeditor/ckeditor.js"></script>
<script>
$(function () {
    //Set bootstrap theme
    $.fn.select2.defaults.set( "theme", "bootstrap" );

    //Select2 basic example
    $("#select2-example-basic").select2({
        placeholder: "Select a category",
        allowClear: true
    });
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1')
})
</script>