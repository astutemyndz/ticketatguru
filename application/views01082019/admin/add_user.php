<?php
$this->load->view('admin/layout/header');
$this->load->view('admin/layout/sidemenu');
?>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?=base_url('admin/dashboard')?>">Dashboard</a></li>
                <li><a><?=$mode?> User</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="col-sm-12 col-md-12">
            <?php if($this->session->flashdata('success_msg')){ ?>
            <div class="alert alert-success fade in">
                <a href="#" class="close" data-dismiss="alert">×</a>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                <?=$this->session->flashdata('success_msg')?>
            </div>
            <?php } ?>
            <?php if($this->session->flashdata('error_msg')){ ?>
            <div class="alert alert-danger fade in">
                <a href="#" class="close" data-dismiss="alert">×</a>
                <h4><i class="icon fa fa-close"></i> Error!</h4>
                <?=$this->session->flashdata('error_msg')?>
            </div>
            <?php } ?>
            <?php if($mode == 'Add'){ $to_url = 'admin/users/add-user'; }else{ $to_url = 'admin/users/edit-user/'.$users[0]->id; } ?>
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-12">
                            <!--<form action="<?=base_url('admin/users/add-user')?>" return_to="<?=base_url('admin/categories')?>" class="form-horizontal commonFormSubmitByAjax" method="post">-->
                            <form action="<?=base_url($to_url)?>" class="form-horizontal" method="post">
                                <input type="hidden" name="user_id" value="<?php if(isset($users)){ print $users[0]->id; }else{ print '';}?>" />
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Name *</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="user_name" class="form-control commonRequired" value="<?php if(isset($users)){ print $users[0]->name; }?>" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Email *</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="user_email" class="form-control commonRequired" value="<?php if(isset($users)){ print $users[0]->email; }?>" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">User Type *</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="user_type" class="form-control commonRequired" required>
                                            <option value="">Select User Type</option>
                                            <option value="1" <?php if(isset($users)){ if($users[0]->login_type == 1){ print 'selected'; }}?>>User</option>
                                            <option value="2" <?php if(isset($users)){ if($users[0]->login_type == 2){ print 'selected'; }}?>>Performer</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Gender *</label>
                                    <div class="col-sm-4">
                                        <select class="form-control commonRequired" name="user_gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?php if(isset($users)){ if($users[0]->gender == 'Male'){ print 'selected'; }}?>>Male</option>
                                            <option value="Female" <?php if(isset($users)){ if($users[0]->gender == 'Female'){ print 'selected'; }}?>>Female</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <?php if($mode == 'Edit'){ ?>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">User Status *</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="user_status" class="form-control commonRequired" required>
                                            <option value="">Select Status</option>
                                            <option value="0" <?php if(isset($users)){ if($users[0]->status == 0){ print 'selected'; }}?>>In-Active</option>
                                            <option value="1" <?php if(isset($users)){ if($users[0]->status == 1){ print 'selected'; }}?>>Active</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">User Password *</label>
                                    <div class="col-sm-4">
                                        <input type="password" name="user_pwd" class="form-control commonRequired" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="submit" class="btn btn-wide btn-primary form-submit-button"><?php if($mode == 'Add'){ ?>Save<?php }else{ ?>Save Changes<?php } ?> </button>
                                        <button type="submit" onclick="location.href='<?=base_url('admin/users/list/user')?>';" class="btn btn-danger">Cancel</button>
                                        <button type="submit" class="status-message btn btn-wide btn-o btn-danger" style="display:none;"></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/layout/footer'); ?>
