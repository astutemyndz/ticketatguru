<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="partner">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="accordion-sec">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php 
								// echo "<pre>"; print_r($sponsor_year);
								if(count($sponsor_year)>0){ 
									foreach($sponsor_year as $sponsor_year_key=>$sponsor_year_val){
									?>
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingOne">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
												<i class="fa fa-minus more-less" aria-hidden="true"></i>
												<?php echo $sponsor_year_key; ?> | Main Sponsors
											</a>
										</h4>
									</div>
									<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
										<div class="panel-body">
											  <ul>
												  <?php  if(count($sponsor_year_val)>0){ 
													  foreach($sponsor_year_val as $key=>$val){
													  ?>
													<li><a href="<?php echo $val['sponsor_link'];?>" target="_blank"><img src="<?php echo $val['sponsor_image'];?>" alt="<?php echo $val['name'];?>"></a></li>
												  <?php } } else{ ?>
													<li><a href="javascript:void(0);">No Sponsors found</a></li>
												  <?php } ?>
											</ul>
										</div>
									</div>
								</div>
								<?php } } else{ ?>
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="headingOne">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
													<i class="fa fa-minus more-less" aria-hidden="true"></i>
													<?php echo date('Y'); ?> | Main Sponsors
												</a>
											</h4>
										</div>
										<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
											<div class="panel-body">
												<ul>
													<li><a href="javascript:void(0);">No Sponsors found</a></li>
												</ul>
											</div>
										</div>
									</div>
								<?php } ?>
							</div><!-- panel-group -->
						</div>
					</div>
				</div>
			</div>
		</section>
