<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="contact-sec">
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<div class="">
					<img src="<?php echo base_url();?>/images/contact-2.png" alt="">
				</div>
			</div>
			<div class="col-md-7">
				<div class="cont-form">
					<form>
						<div class="col-md-6">
							<div class="input-container">
								<i class="fa fa-user icon" aria-hidden="true"></i>
								<input type="text" placeholder="Enter your name">
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-container">
								<i class="fa fa-envelope icon" aria-hidden="true"></i>
								<input type="email" placeholder="Enter your email">
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-container">
								<i class="fa fa-phone icon" aria-hidden="true"></i>
								<input type="text" placeholder="Enter your contact no">
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-container">
								<i class="fa fa-bookmark icon" aria-hidden="true"></i>
								<input type="text" placeholder="Enter subject">
							</div>
						</div>
						<div class="col-md-12">
							<div class="input-container">
								<i class="fa fa-pencil icon" aria-hidden="true"></i>
								<textarea placeholder="Message"></textarea>
							</div>
						</div>
						<div class="col-md-12">
							<input class="cont-form-sub" type="submit" value="Submit">
						</div>
					</form>
				</div>
			</div>
			<div class="clearfix"></div>
			<section class="">
			<?php echo $contact_us['description'];?>
			</section>

		</div>
	</div>
</section>
