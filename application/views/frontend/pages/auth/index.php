<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<div class="login-sec">
			
			
			<div class="container">
				<div class="login-inner col-md-6 col-md-offset-3">
				<ul class="nav nav-tabs">
					<li class="active left-radius"><a data-toggle="tab" href="#login">Login</a></li>
					<li class="right-radius"><a data-toggle="tab" href="#register">Registration</a></li>
				</ul>
				<div class="tab-content">
					<div id="login" class="tab-pane fade in active">
						<div class="from-sec">
							<form id="loginForm" method="post" action="<?php echo base_url();?>auth/login/post">
								<input type="email" placeholder="Username" name="identity" id="identity" required>
								<input type="password" placeholder="Password" name="password" id="password" required>
								<label class="check">
									<input type="checkbox" id="remember" name="remember">
									<span class="checkmark"></span>
									Keep me logged in
									<a href="#">Forgot Password?</a>
                                </label>
                                <div class="flash"></div>
								<input type="submit" class="sign-btn" value="Sign In">
							</form>
						</div>
					</div>
					<div  id="register" class="tab-pane fade in">
                       
                            <div  class="from-sec">
                                <form action="<?php echo base_url();?>auth/register/post" method="post" id="registerForm" >
                                    <input type="text" placeholder="First Name" name="first_name" id="first_name" required>
                                    <input type="text" placeholder="Last Name" name="last_name" id="last_name" required>
                                    <input type="text" placeholder="Username" name="registerIdentity" id="registerIdentity" required>
                                    <input type="email" placeholder="Email" name="email" id="email" required>
                                    <input type="email" placeholder="Confirm Email" name="confirm_email" id="confirm_email" required>
                                    <input type="password" placeholder="Password" name="password" id="main_password" required>
                                    <input type="password" placeholder="Confirm Password" name="password_confirm" id="password_confirm" required>
                                    <label class="check">
                                        <input type="checkbox" id="agree" name="agree" required>
                                        <span class="checkmark"></span>I agree with the Terms and Conditions</a>
                                    </label>
                                    <div class="flash"></div>
                                    <input type="submit" class="sign-btn" value="Sign In">
                                </form>
                            </div>
                     
					</div>
				</div>
				</div>
			</div>
			<!--<div class="after">
				<img src="images/triangle.png">
			</div>-->
		</div>