<?php 
//App::dd($this->session->all_userdata());
// echo PJ_SALT;
// exit;
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
			<div class="col-md-6 col-sm-6 col-xs-6">
				<div class="top-left">
					<div class="contact" id="contact">
						<p class="call">
							<i class="fa fa-phone hidden-xs" aria-hidden="true"></i>
							<span class="hidden-xs">+62274 889767</span>
							<i class="fa fa-caret-down hidden-xs" aria-hidden="true"></i>
							<div class="m-call hidden-sm hidden-md- hidden-lg">
								<i class="fa fa-phone" aria-hidden="true"></i>
							</div>
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
					<div class="email hidden-xs">	
						<i class="fa fa-envelope" aria-hidden="true"></i>
						<a href="mailto:info@ticketatguru.com">info@ticketatguru.com</a>
					</div>
					<div class="m-email hidden-sm hidden-md- hidden-lg">
						<a href="mailto:info@ticketatguru.com"><i class="fa fa-envelope" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<div class="top-right js-main-nav">
					<div class="lang">
						<div class="showLang" id="selectLang">
							<img src="<?php echo base_url() ?>/images/united-kingdom.png" alt="">
							<span>English</span>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
						</div>
						<ul id="chooseLang" class="chooseLang" style="display: none;">
							<li>
								<a href="#">
									<img src="<?php echo base_url() ?>/images/united-kingdom.png" alt=""> 
									English
								</a>
							</li>
							<li>
								<a href="#">
									<img src="<?php echo base_url() ?>/images/egypt.png" alt="">
									Egytp
								</a>
							</li>
						</ul>
					</div>
					<ul class="js-signin-modal">
						<?php if(!empty($this->session->userdata('loggedIn')) && $this->session->userdata('loggedIn') === TRUE) { 
								if($this->session->userdata('isCustomer')) {
									?>
									<li>
										<a id="logoutLink" class="" href="javascript:void(0);" >
											<span class="hidden-xs">Sign out</span>
											<span class="m-log hidden-sm hidden-md- hidden-lg">
												<i class="fa fa-sign-out" aria-hidden="true"></i>
											</span>
										</a>
									</li>
							<?php } ?>
							<?php } else { ?>
							<li>
								<a id="loginLink" class="" href="javascript:void(0);" data-signin="login">
									<span class="hidden-xs">Sign in</span>
									<span class="m-log hidden-sm hidden-md- hidden-lg">
										<i class="fa fa-sign-in" aria-hidden="true"></i>
									</span>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
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
							<li <?php if($controller=='EventController' && $method=='index'){ ?>class="active"<?php }?> ><a href="<?php echo base_url();?>">Home</a></li>
							<li <?php if($controller=='ContactController' && $method=='about_us'){ ?>class="active"<?php }?>><a href="<?php echo base_url('about-us');?>">About Us</a></li>
							<li <?php if($controller=='EventController' && $method=='eventList'){ ?>class="active"<?php }?>><a href="<?php echo base_url('event/list');?>">Events</a></li>
							<li <?php if($controller=='EventController' && $method=='galleryList'){ ?>class="active"<?php }?>><a href="<?php echo base_url('gallery');?>">Gallery</a></li>
							<li <?php if($controller=='EventController' && $method=='partnersList'){ ?>class="active"<?php }?>><a href="<?php echo base_url('partners');?>">Partners</a></li>
							<li <?php if($controller=='ContactController' && $method=='index'){ ?>class="active"<?php }?>><a href="<?php echo base_url('contact-us');?>">Contact</a></li>
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
			<?php $i = 0; 
			$eventCount = count($events);
			if(count($events) > 0) {  ?>
			<?php foreach($events as $event) { $i++;
				$firstEventDate = reset($event['shows']); 
				$firstEventPrice = reset($event['Price']);
				if($i == 1){ $cls = 'active'; }else{ $cls = ''; }
				?>
				
			  <div class="item <?php echo $cls;?>">
				<img src="<?php echo base_url() ?>/images/cover-1.jpg" alt="Slide1">
				<div class="black-layer"></div>
				  <div class="carousel-caption">
					  <h2>Welcome to Ticket at Guru</h2>
					  <h3><?php echo $event['event']['title'];?></h3>
					  <!-- 2019/10/06 -->
					  <div id="countdown_<?php echo $i;?>" class="countdown flex flex-wrap justify-content-between" data-id="<?php echo $i;?>" data-date="<?php echo date('Y/m/d',strtotime($firstEventDate));?>">
					  <?php //echo date("Y/m/d", strtotime($firstEventDate)); ?>
					  <p id="demo"></p>
						<div class="countdown-holder">
							<div class="dday" id="dday_<?php echo $i;?>">20</div>
							<label>Days</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dhour" id="dhour_<?php echo $i;?>">20</div>
							<label>Hours</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dmin" id="dmin_<?php echo $i;?>">20</div>
							<label>Minutes</label>
						</div><!-- .countdown-holder -->

						<div class="countdown-holder">
							<div class="dsec" id="dsec_<?php echo $i;?>">20</div>
							<label>Seconds</label>
						</div><!-- .countdown-holder -->
					</div>
					<div class="banner-btn">
						<a href="<?php echo base_url();?>event/details/<?php echo $event['event']['id'];?>" class="btn">Buy Tickets</a>
					</div>
            	  </div>
			  </div>
			<?php } ?>
			<?php } else { ?>
				<div class="item active">
				<img src="<?php echo base_url() ?>/images/cover-1.jpg" alt="Slide1">
				<div class="black-layer"></div>
				  <div class="carousel-caption">
						<h2>Welcome to Ticket at Guru</h2>
						<p>Ticket At Guru is a festival of concerts by Six Stars Events. Ticket At Guru is an established name in the music industry with over 15 years of experience in delivering concerts and high-end gala functions. We have traditionally hosted all our world-class galas at the Grosvenor House 5-star hotel at Park Lane in Central London.</p>
						<div class="banner-btn">
							<a href="<?php echo base_url('contact-us');?>" class="btn">Contact Us</a>
						</div>
				  </div>
			  </div>
			<?php } ?>
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
			<h3><?php //echo ($page_heading) ? $page_heading : 'Welcome to Ticket at Guru'; ?></h3>
		</div>
	</section>
</section>	
<?php } ?>		
		

