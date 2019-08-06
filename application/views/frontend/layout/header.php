<?php 
$cartItemsCount = ($this->cart->contents()) ? count($this->cart->contents()) : 0;
?>
<?php
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
$active_url = $controller.'/'.$method;
?>
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
								<li class="active"><a href="<?php echo base_url();?>">Home</a>
								</li>
								<li><a href="<?php echo base_url('event/list');?>" target="_self">Events</a>
								</li>
								<li><a href="">Tickets Cart</a>
								</li>
								<li><a href="">Gallery</a>
								</li>
								<li><a href="">Partners</a>
								</li>
								<li><a href="">Contact</a>
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
if($controller == 'EventController' && $method =='index'){
?>
<div class="hero-content" id="home">
	<div class="banner">
		<div id="myCarousel" class="carousel slide" data-ride="carousel" >
			<div class="carousel-inner">
			  <div class="item active">
				<img src="<?php echo base_url() ?>/images/cover-1.jpg" alt="Slide1">
				<div class="black-layer"></div>
				  <div class="carousel-caption">
					  <h2>Welcome to Ticket at Guru</h2>
					  <div class="countdown flex flex-wrap justify-content-between" data-date="2019/10/06">
						<div class="countdown-holder">
							<div class="dday">20</div>
							<label>Days</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dhour">20</div>
							<label>Hours</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dmin">20</div>
							<label>Minutes</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dsec">20</div>
							<label>Seconds</label>
						</div><!-- .countdown-holder -->
					</div>
					<div class="banner-btn">
						<a href="#" class="btn">Buy Tickets</a>
					</div>
            	  </div>
			  </div>

			  <div class="item">
				<img src="<?php echo base_url() ?>/images/cover-2.jpg" alt="Slide1">
				<div class="black-layer"></div>
				  <div class="carousel-caption">
					  <h2>Welcome to Ticket at Guru</h2>
					  <div class="countdown flex flex-wrap justify-content-between" data-date="2019/11/06">
						<div class="countdown-holder">
							<div class="dday">20</div>
							<label>Days</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dhour">20</div>
							<label>Hours</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dmin">20</div>
							<label>Minutes</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dsec">20</div>
							<label>Seconds</label>
						</div><!-- .countdown-holder -->
					</div>
					<div class="banner-btn">
						<a href="#" class="btn">Buy Tickets</a>
					</div>
            	  </div>
			  </div>

			  <div class="item">
				<img src="<?php echo base_url() ?>/images/cover-3.jpg" alt="Slide1">
				  <div class="carousel-caption">
					  <h2>Welcome to Ticket at Guru</h2>
					  <div class="countdown flex flex-wrap justify-content-between" data-date="2019/09/06">
						<div class="countdown-holder">
							<div class="dday">20</div>
							<label>Days</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dhour">20</div>
							<label>Hours</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dmin">20</div>
							<label>Minutes</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dsec">20</div>
							<label>Seconds</label>
						</div><!-- .countdown-holder -->
					</div>
					<div class="banner-btn">
						<a href="#" class="btn">Buy Tickets</a>
					</div>
            	  </div>
			  </div>
			</div>
		  </div>
	</div>
</div>
<?php }else{ ?>
<section class="section-page-header">
	<section class="page-banner">
		<img src="<?php echo base_url() ?>/images/banner.jpg" alt="">
		<div class="black-layer"></div>
		<div class="caption">
			<h3>Tickets for Camp Nou Experience</h3>
		</div>
	</section>
</section>	
<?php } ?>		
		
		
