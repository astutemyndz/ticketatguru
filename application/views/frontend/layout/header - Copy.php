<?php 
$cartItemsCount = ($this->cart->contents()) ? count($this->cart->contents()) : 0;

?>
<!--<header class="site-header">
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
								<?php /*<li>
									<a href="" class="btn" data-toggle="modal" data-target="#modalLoginForm">Sign In</a>
								</li>
							
								<li>
									<a href="<?php echo base_url();?>logout" class="btn">Sign Out</a>
								</li>
							
								<li>
									<a href="" class="btn" data-toggle="modal" data-target="#modalRegisterForm">Sign up</a>
								</li> 
								*/?>
								<?php if(!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === TRUE) { ?>
								<li>
									<a class="cd-main-nav__item cd-main-nav__item--signup btn" href="<?php echo base_url();?>auth/logout" >Sign out</a>
								</li>
								<?php } else { ?>
									<li>
										<a class="cd-main-nav__item cd-main-nav__item--signin btn" href="#0" data-signin="login">Sign in</a>
									</li>
								<?php } ?>
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
							<a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/images/ticketGuruLogo.png" alt="Logo"></a>
						</h1>
					</div>

					<div class="col-2 col-lg-8">
						<nav class="site-navigation">
							<div class="hamburger-menu d-lg-none">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</div>

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
</header>-->

<!--23-07-19-->
<div class="top-header top-header-bg">
	<div class="container">
		<div class="row">
			<div class="top-left">
				<div class="contact" id="contact">
					<p class="call">
						<i class="fa fa-phone" aria-hidden="true"></i>
						<span>+62274 889767</span>
						<i class="fa fa-caret-down" aria-hidden="true"></i>
					</p>
					<ul id="selectNo" style="display: none;">
						<li>
							<img src="<?php echo base_url();?>/images/egypt.png" alt="Egypt">
							<a href="tel:+62274889767">+62274 889767</a>
						</li>
						<li>
							<img src="<?php echo base_url();?>/images/united-kingdom.png" alt="UK">
							<a href="tel:+62274889767">+62274 889767</a>
						</li>
					</ul>
				</div>
				<div class="email">	<i class="fa fa-envelope" aria-hidden="true"></i>
					<a href="mailto:hello@myticket.com">hello@myticket.com</a>
				</div>
			</div>
			<div class="top-right js-main-nav">
				<div class="lang">
					<div class="showLang" id="selectLang">
						<!-- <img src="<?php echo base_url() ?>/images/united-kingdom.png" alt=""> -->
						<span>English</span>
						<i class="fa fa-caret-down" aria-hidden="true"></i>
					</div>
					<ul id="chooseLang" class="chooseLang" style="display: none;">
						<li>
							<a href="#">
								<!-- <img src="<?php echo base_url() ?>/images/united-kingdom.png" alt=""> -->
								English
							</a>
						</li>
						<li>
							<a href="#">
								<!-- <img src="<?php echo base_url() ?>/images/egypt.png" alt=""> -->
								Egytp
							</a>
						</li>
					</ul>
				</div>
				<ul class="js-signin-modal">
					<?php if(!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === TRUE) { ?>
						<li>
							<a id="logoutLink" class="" href="javascript:void(0);" >Sign out</a>
						</li>
						<?php } else { ?>
						<li>
							<a id="loginLink" class="" href="javascript:void(0);" data-signin="login">Sign in</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<header class="site-header" id="myHeader">
	<div class="main-header main-header-bg">
		<div class="container">
			<div class="row">
				<div class="site-branding col-md-3">
					<h1 class="site-title">
									<a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/images/ticketGuruLogo.png" alt="Logo"></a>
								</h1>
				</div>
				<div class="col-md-9">
					<nav id="site-navigation" class="navbar">
						<!-- toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<div class="mobile-cart">
								<a href="#">
									<?php echo $cartItemsCount;?>
								</a>
							</div>
							<button type="button" class="navbar-toggle offcanvas-toggle pull-right" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas">	<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="navbar-offcanvas navbar-offcanvas-touch navbar-offcanvas-right" id="js-bootstrap-offcanvas">
							<button type="button" class="offcanvas-toggle closecanvas" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas"> <i class="fa fa-times fa-2x" aria-hidden="true"></i>
							</button>
							<ul class="nav navbar-nav navbar-right" id="navBar">
								<li class="active"><a href="#home">Home</a>
								</li>
								<li><a href="#events" target="_self">Events</a>
								</li>
								<li><a href="#">Tickets Cart</a>
								</li>
								<li><a href="#gallery">Gallery</a>
								</li>
								<li><a href="#partners">Partners</a>
								</li>
								<li><a href="#contact">Contact</a>
								</li>
								<li class="cart" id="plk-cart-pini-wrapper">
									<a href="<?php echo base_url();?>cart">
										<?php echo ($this->cart->contents()) ? count($this->cart->contents()) : 0 ?></a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
		</div>
	</div>		
</header>
			
		
		
<?php
/*
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
*/?>

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
</div> 

<div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

