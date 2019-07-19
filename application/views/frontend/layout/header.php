<?php

/*
<header class="site-header">
	<div class="top-header top-header-bg">
				<div class="container">
					<div class="row">
						<div class="top-left">
							<ul>
								<li>
									<a href="#">
										<i class="fa fa-phone"></i>
										+62274 889767
									</a>
								</li>
								<li>
									<a href="mailto:hello@myticket.com"> 
										<i class="fa fa-envelope-o"></i>
										hello@myticket.com
									</a>
								</li>
								<li>
									<a href="javascript:void(0);" data-toggle="modal" data-target="#myModal"> Change Location </a>
								</li>
							</ul>
						</div>
						<div class="top-right">
							<ul>
								<li>
									<a href="" class="btn" data-toggle="modal" data-target="#modalLoginForm">Sign In</a>
								</li>
							
								<li>
									<a href="<?php echo base_url();?>logout" class="btn">Sign Out</a>
								</li>
							
								<li>
									<a href="" class="btn" data-toggle="modal" data-target="#modalRegisterForm">Sign up</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<div class="header-bar">
			<div class="container-fluid">
				<div class="row align-items-center">
					<div class="col-10 col-lg-4">
						<h1 class="site-branding flex">
							<a href="#" title="globalgala" rel="home">
								<img src="<?php echo base_url();?>images/logo.png" alt="logo" width="100"> 
								Ticket at Guru
							</a>
						</h1>
					</div>

					<div class="col-2 col-lg-8">
						<nav class="site-navigation">
							<div class="hamburger-menu d-lg-none">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</div><!-- .hamburger-menu -->

							<ul>
								<li><a href="#">HOME</a></li>
								<li><a href="#">Events</a></li>
								<li><a href="#">Tickets Cart</a></li>
								<li><a href="#">Gallery</a></li>
								<li><a href="#">CONTACT</a></li>
								
								<li><a href="#"><i class="fas fa-search"></i></a></li>
								<li class="cart"><a href="#">0</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
</header>
*/
?>

<header class="cd-main-header">
		<div class="cd-main-header__logo"><a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/images/ticketGuruLogo.png" alt="Logo"></a></div>

		<nav class="cd-main-nav js-main-nav">
			<ul class="cd-main-nav__list js-signin-modal-trigger">
				<!-- inser more links here -->
				
				<!-- <li><a class="cd-main-nav__item cd-main-nav__item--signup" href="#0" data-signin="signup">Sign up</a></li> -->
				<?php if(!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === TRUE) { ?>
				<li><a class="cd-main-nav__item cd-main-nav__item--signup" href="<?php echo base_url();?>auth/logout" >Sign out</a></li>
				<?php } else { ?>
					<li><a class="cd-main-nav__item cd-main-nav__item--signin" href="#0" data-signin="login">Sign in</a></li>
				<?php } ?>
			</ul>
		</nav>
	</header>

<!-- <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  	
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" class="form-control validate" name="identity" id="identity">
          <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" class="form-control validate" name="password" id="password">
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Your password</label>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-default" id="loginButton">Login</button>
      </div>
    </div>

  </div>
</div> -->

<!-- <div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  	<form method="post"  id="registerForm">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="text" id="first_name" name="first_name" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-name">First Name</label>
		</div>
		<div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="text" id="last_name" name="last_name" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-name">Lst Name</label>
        </div>
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="text" id="identity" name="identity" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-email">Username</label>
		</div>
		<div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="text" id="email" name="email" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-email">Email</label>
		</div>
		

		<div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="confirm_email" name="confirm_email" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-email">Confirm Email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="password" name="password" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-pass">Your password</label>
		</div>
		<div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="password_confirm" name="password_confirm" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-pass">Confirm password</label>
		</div>

		<div id="message" style="display:none" class="alert  alert-dismissible fade show" role="alert">
			<strong id="responseText"></strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type="button" id="registerButton" class="btn btn-deep-orange">Sign up</button>
      </div>
    </div>
	</form>
  </div>
</div> -->

<div class="cd-signin-modal js-signin-modal"> <!-- this is the entire modal form, including the background -->
		<div class="cd-signin-modal__container"> <!-- this is the container wrapper -->
			<ul class="cd-signin-modal__switcher js-signin-modal-switcher js-signin-modal-trigger">
				<li><a href="#0" data-signin="login" data-type="login">Sign in</a></li>
				<li><a href="#0" data-signin="signup" data-type="signup">New account</a></li>
			</ul>

			<div class="cd-signin-modal__block js-signin-modal-block" data-type="login"> <!-- log in form -->
				<form class="cd-signin-modal__form" id="loginForm" action="<?php echo base_url();?>auth/login" method="post">
					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--email cd-signin-modal__label--image-replace" for="signin-email">E-mail</label>
						<input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="identity" name="identity" type="email" placeholder="E-mail">
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--password cd-signin-modal__label--image-replace" for="signin-password">Password</label>
						<input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="password" name="password" type="password"  placeholder="Password">
						<!-- <a href="#0" class="cd-signin-modal__hide-password js-hide-password">Hide</a> -->
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<input type="checkbox" id="remember" name="remember" checked class="cd-signin-modal__input ">
						<label for="remember-me">Remember me</label>
					</p>
					<p class="cd-signin-modal__fieldset">
						<span id="message" class="alert"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<input class="cd-signin-modal__input cd-signin-modal__input--full-width" type="submit" value="Login">
					</p>
				</form>
				
				<p class="cd-signin-modal__bottom-message js-signin-modal-trigger"><a href="#0" data-signin="reset">Forgot your password?</a></p>
			</div> <!-- cd-signin-modal__block -->
<!-- sign up form -->
			<div class="cd-signin-modal__block js-signin-modal-block" data-type="signup"> <!-- sign up form -->
				<form class="cd-signin-modal__form" action="<?php echo base_url();?>auth/register" method="post" id="registerForm">
					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--username cd-signin-modal__label--image-replace" for="signup-username">First Name</label>
						<input name="first_name" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="first_name" type="text" placeholder="First Name">
						<span class="cd-signin-modal__error"></span>
					</p>
					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--username cd-signin-modal__label--image-replace" for="signup-username">Last Name</label>
						<input name="last_name" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="last_name" type="text" placeholder="Last Name">
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--username cd-signin-modal__label--image-replace" for="signup-username">Username</label>
						<input name="identity" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="username" type="text" placeholder="Username">
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--email cd-signin-modal__label--image-replace" for="signup-email">E-mail</label>
						<input name="email" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="signupEmail" type="email" placeholder="E-mail">
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--email cd-signin-modal__label--image-replace" for="signup-email">E-mail</label>
						<input name="confirm_email" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="signupEmailConfirm" type="email" placeholder="Confirm E-mail">
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--password cd-signin-modal__label--image-replace" for="signup-password">Password</label>
						<input name="password" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="signupPassword" type="password"  placeholder="Password">
						<a href="#0" class="cd-signin-modal__hide-password js-hide-password">Hide</a>
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--password cd-signin-modal__label--image-replace" for="signup-password">Password</label>
						<input name="password_confirm" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="signupPasswordConfirm" type="password"  placeholder="Confirm Password">
						<a href="#0" class="cd-signin-modal__hide-password js-hide-password">Hide</a>
						<span class="cd-signin-modal__error"></span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<input type="checkbox" id="accept-terms" class="cd-signin-modal__input ">
						<label for="accept-terms">I agree to the <a href="#0">Terms</a></label>
					</p>

					<p class="cd-signin-modal__fieldset">
						<input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding" type="submit" id="registerButton" value="Create account">
					</p>
				</form>
			</div> <!-- cd-signin-modal__block -->
<!-- sign up form -->


			<div class="cd-signin-modal__block js-signin-modal-block" data-type="reset"> <!-- reset password form -->
				<p class="cd-signin-modal__message">Lost your password? Please enter your email address. You will receive a link to create a new password.</p>

				<form class="cd-signin-modal__form" >
					<p class="cd-signin-modal__fieldset">
						<label class="cd-signin-modal__label cd-signin-modal__label--email cd-signin-modal__label--image-replace" for="reset-email">E-mail</label>
						<input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" type="email" placeholder="E-mail">
						<span class="cd-signin-modal__error">Error message here!</span>
					</p>

					<p class="cd-signin-modal__fieldset">
						<input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding" type="submit" value="Reset password">
					</p>
				</form>

				<p class="cd-signin-modal__bottom-message js-signin-modal-trigger"><a href="#0" data-signin="login">Back to log-in</a></p>
			</div> <!-- cd-signin-modal__block -->
			<a href="#0" class="cd-signin-modal__close js-close">Close</a>
		</div> <!-- cd-signin-modal__container -->
	</div> <!-- cd-signin-modal -->

