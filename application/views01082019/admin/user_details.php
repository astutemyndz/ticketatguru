<?php
$this->load->view('admin/layout/header');
$this->load->view('admin/layout/sidemenu');
?>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?=base_url('admin/dashboard')?>">Dashboard</a></li>
                <li><a> User Details </a></li>
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
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Name</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['name']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Email</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['email']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Phone</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['phone_no']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Gender</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['gender']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Date of Birth</label>
                                    <div class="col-sm-4">
                                        <?=date_format(date_create($user[0]['dob']), 'd-m-Y')?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Address</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['address_one']. ' '.$user[0]['address_two'].', '.$user[0]['city'].', Pin: '.$user[0]['pincode'].', '.$user[0]['country']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">I am a</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['i_am_a']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">US Citizen</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['us_citizen']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Signature</label>
                                    <div class="col-sm-4">
                                        <?=$user[0]['signature_name']?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Date</label>
                                    <div class="col-sm-4">
                                        <?=date_format(date_create($user[0]['verify_date']), 'd-m-Y')?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Photo ID</label>
                                    <div class="col-sm-4">
                                        <a href="<?=base_url('assets/verify_image/'.$user[0]['verify_pic'])?>" target="_blank">
                                            <img src="<?=base_url('assets/verify_image/'.$user[0]['verify_pic'])?>" style="height:150px; width:250px;">
                                        </a>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Image With Photo ID</label>
                                    <div class="col-sm-4">
                                        <a href="<?=base_url('assets/verify_image/'.$user[0]['verify_pic_id'])?>" target="_blank">
                                            <img src="<?=base_url('assets/verify_image/'.$user[0]['verify_pic_id'])?>" style="height:150px; width:250px;">
                                        </a>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="button" onclick="location.href='<?=base_url('admin/verification/performer')?>';" class="btn btn-danger">Back</button>
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
