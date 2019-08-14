<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
		
        <div class="check-out">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="progress-bar-circle">
							<div class="progress-line"></div>
							<ul class="nav nav-pills">
								<li class="active"><a data-toggle="pill" href="#home">Details</a></li>
								<li><a data-toggle="pill" href="#menu1">Ticket Summary</a></li>
								<li><a data-toggle="pill" href="#menu2">Payment Details</a></li>
							</ul>
							  
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="from-sec">
										<form>
											<input type="text" class="grid-2" placeholder="First name">
											<input type="text" class="grid-2 ml-20" placeholder="Last name">
											<input type="text" class="grid-2" placeholder="Email">
											<input type="text" class="grid-2 ml-20" placeholder="Contact">
											<select class="grid-2">
												<option selected>Country</option>
												<option>Afghanistan</option>
												<option>Albania</option>
												<option>Algeria</option>
												<option>American Samoa</option>
												<option>Andorra</option>
												<option>Angola</option>
												<option>Anguilla</option>
												<option>Antarctica</option>
											</select>
											<input type="text" class="grid-2 ml-20" placeholder="City">
											<input type="text" class="grid-2" placeholder="Address">
											<input type="text" class="grid-2 ml-20" placeholder="Postalcode">
											
											<div class="clearfix"></div>
											<label class="check">
												<input type="checkbox">
													<span class="checkmark"></span>
													Save Address
													<!--<a href="#">Forgot Password?</a>-->
												</label>
											<input type="submit" class="sign-btn" value="Confirm">
										</form>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
								  <h3>Menu 1</h3>
								  <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
								</div>
								<div id="menu2" class="tab-pane fade">
								  <h3>Menu 2</h3>
								  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>

		