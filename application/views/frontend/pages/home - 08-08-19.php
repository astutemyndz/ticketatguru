<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- .container -->
	<?php 
	/*
		<section class="section-todays-schedule">
			<div class="container">
				<div class="row">
					<div class="section-header">
            
						<h2>Event's Schedule </h2>
						<span class="todays-date"><i class="fa fa-calendar" aria-hidden="true"></i> <strong>12</strong> August 2019 </span>
					</div>
					<div class="section-content">
						<ul class="clearfix">
							<?php 
								foreach($arr as $key => $value) {
							?>
							<li class="event-1">
								<span class="event-time"><?php echo $newDate = date("jS M, Y", strtotime($event_val->date_time)); ?> <strong><?php echo $newDate = date("h:i:s a", strtotime($event_val->date_time)); ?></strong></span>
								<strong class="event-name"><?php echo $event_val->content;?></strong>
								<a href="<?php echo base_url();?>event/details/<?php echo $event_val->event_id;?>" class="get-ticket">Get Ticket</a>
							</li>
						<?php } ?>
						</ul>
						<strong class="event-list-label">Full Event <span>Schedules</span></strong>
					</div>
				</div>
			</div>
		</section>
		*/?>
		<section class="section-todays-schedule">
			<div class="container">
				<div class="row">
					<div class="section-header">
						<h2>Today's Schedule</h2>
						<span class="todays-date"><i class="fa fa-calendar" aria-hidden="true"></i> <strong><?php echo $newDate = date("jS M, Y", strtotime($today)); ?></strong></span>
					</div>
					<?php if(count($showTimes) > 0) {?>
					<div class="section-content">
						<ul class="clearfix autoplay">
						<?php 
							foreach($showTimes as $showTime) {
						?>
							<li class="event-1">
								<span class="event-time"><?php echo $showTime['showTime'];?></span>
								<strong class="event-name"><?php echo $showTime['event']['title'];?></strong>
								<!-- <a href="<?php echo base_url();?>event/details/<?php echo $showTime['event']['id'];?>" class="get-ticket">Get Ticket</a> -->
								<a href="javascript:void(0);" class="get-ticket pjCbDaysNav getTicket" data-date="<?php echo $hashDate;?>" data-id="<?php echo $showTime['event']['id'];?>" data-key="<?php echo $showTime['event']['id'];?>" data-time="<?php echo $showTime['dataTime'];?>">Get Ticket</a>
							</li>
						<?php } ?>	
							
						</ul>
						<!-- <strong class="event-list-label">Full Event <span>Schedules</span></strong> -->
					</div>
							<?php } else {?>
								<span>Show not available</span>
							<?php } ?>
				</div>
			</div>
		</section>
		<section class="section-upcoming-events" id="events">
			<div class="container">
				<div class="row">
					<div class="section-header">
						<h2>Events</h2>
						<!-- <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut.</p> -->
						<a href="#">See all upcoming events</a>
					</div>
					<?php if(count($events) > 0) {?>
					<div class="section-content">
						<ul class="clearfix">
							<?php foreach($events as $event) { $firstEventDate = reset($event['shows']); ?>
							<li>
								<div class="date">
									<a href="#">
										<span class="day"><?php echo date("jS", strtotime($firstEventDate)); ?></span>
										<span class="month"><?php echo date("M", strtotime($firstEventDate)); ?></span>
										<span class="year"><?php echo date("Y", strtotime($firstEventDate)); ?></span>
									</a>
								</div>
								<a href="#">
									<img src="<?php echo $event['event']['event_img'];?>" alt="<?php echo $event['event']['title'];?>">
								</a>
								<div class="info">
									<p><?php echo $event['event']['title'];?></p>
									<a href="<?php echo base_url();?>event/details/<?php echo $event['event']['id'];?>" class="get-ticket">View Event</a>
								</div>
							</li>
							<?php } ?>
							
						</ul>
					</div>
					<?php } else { ?>
						<span>Comming Soon</span>
					<?php } ?>
				</div>
			</div>
		</section>
		<?php 
		/*
		<section class="section-upcoming-events">
			<div class="container">
				<div class="row">
					<div class="section-header">
						<h2>Upcoming Events</h2>
						<p>List of our up coming and current events for information and to book your seats(</p>
						<a href="#">See all upcoming events</a>
					</div>
					<div class="section-content">
						<ul class="clearfix">
							<?php 
							// echo "<pre>"; print_r($event_lists);
							if(count($event_lists)>0) 
								foreach($event_lists as $event_key=>$event_val){
									// echo $event_val->content;
							?>
							<li>
								<div class="date">
									<a href="#">
										<span class="day"><?php echo $newDate = date("d", strtotime($event_val->date_time)); ?></span>
										<span class="month"><?php echo $newDate = date("F", strtotime($event_val->date_time)); ?></span>
										<span class="year"><?php echo $newDate = date("Y", strtotime($event_val->date_time)); ?></span>
									</a>
								</div>
								<a href="#">
									<img src="<?php echo $event_val->event_img;?>" alt="<?php echo $event_val->content;?>">
								</a>
								<div class="info">
									<p><?php echo $event_val->content;?> <span>London</span></p>
									<a href="<?php echo base_url();?>event/details/<?php echo $event_val->event_id;?>" class="get-ticket">Get Ticket</a>
								</div>
							</li>
							<?php } ?>
							
						</ul>
					</div>
				</div>
			</div>
		</section>
		*/?>								
		<section class="section-gallery" id="gallery">
			<div class="container">
				<div class="row">
					<h1>GALLERY PICTURES</h1>
					<div class="gallery-list">
						<div class="col-md-6">
							<div class="gallery-block gallery-img col-md-12">
								<a href="#" data-featherlight="#content-1">
									<img src="images/gallery/01.jpg" alt="image">
								</a>
								<div id="content-1" class="gallery-lightbox">
									<img src="images/gallery/01.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-2">
									<img src="images/gallery/02.jpg" alt="image">
								</a>
								<div id="content-2" class="gallery-lightbox">
									<img src="images/gallery/02.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-3">
									<img src="images/gallery/03.jpg" alt="image">
								</a>
								<div id="content-3" class="gallery-lightbox">
									<img src="images/gallery/03.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-4">
									<img src="images/gallery/04.jpg" alt="image">
								</a>
								<div id="content-4" class="gallery-lightbox">
									<img src="images/gallery/04.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-5">
									<img src="images/gallery/05.jpg" alt="image">
								</a>
								<div id="content-5" class="gallery-lightbox">
									<img src="images/gallery/05.jpg" alt="image">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-6">
									<img src="images/gallery/06.jpg" alt="image">
								</a>
								<div id="content-6" class="gallery-lightbox">
									<img src="images/gallery/06.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-7">
									<img src="images/gallery/07.jpg" alt="image">
								</a>
								<div id="content-7" class="gallery-lightbox">
									<img src="images/gallery/07.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-8">
									<img src="images/gallery/08.jpg" alt="image">
								</a>
								<div id="content-8" class="gallery-lightbox">
									<img src="images/gallery/08.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-6">
								<a href="#" data-featherlight="#content-9">
									<img src="images/gallery/09.jpg" alt="image">
								</a>
								<div id="content-9" class="gallery-lightbox">
									<img src="images/gallery/09.jpg" alt="image">
								</div>
							</div>
							<div class="gallery-block gallery-img col-md-12">
								<a href="#" data-featherlight="#content-10">
									<img src="images/gallery/10.jpg" alt="image">
								</a>
								<div id="content-10" class="gallery-lightbox">
									<img src="images/gallery/10.jpg" alt="image">
								</div>
							</div>
						</div>

					</div>
					<!-- <div class="gallery-pagination">
						<ul class="pagination">
							<li>
								<a href="#" aria-label="Previous">
									<span aria-hidden="true"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Previous</span>
								</a>
							</li>
							<li><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li class="active"><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li>
								<a href="#" aria-label="Next">
									<span aria-hidden="true">Next <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
								</a>
							</li>
						</ul>
					</div> -->
				</div>
			</div>
		</section>
		
		<!--<section class="section-video-parallax">
			<div class="">
				<div class="section-content">
					<h2>LIVE THERE</h2>
					<p>Book events from anywhere in 191+ countries and get awesome experience Lorem ipsum dolor sit amet, consectetuer adipiscing elit,</p>
					<a href="#"><img src="images/play-btn.png" alt="image"></a>
				</div>
				<div class="pop-vid">
					<video controls poster="images/vid-cover.jpg">
						<source src="images/globalgala.mp4" type="video/mp4">
					</video>
				</div>
			</div>
		</section>-->
		<section class="section-about">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h1>About <span>ticket at guru</span></h1>
						<p>Global Gala is a festival of concerts by Six Stars Events. Global Gala is an established name in the music industry with over 15 years of experience in delivering concerts and high-end gala functions. We have traditionally hosted all our world-class galas at the Grosvenor House 5-star hotel at Park Lane in Central London.</p>

						<p>Our galas always attract the crème de la crème of the Arabian music fraternity who are always more than happy to perform and join our prestigious hall of fame. In addition, celebrities and assorted dignitaries are invariably in attendance at our events. The musicians continually perform live music on stage with their full bands and this gives our guests that authentic experience that we have become synonymous with. On top of great music, we also offer a tantalizing dining experiences that will leave all your gastronomical senses completely satisfied.</p>

						<p>Since we have been organizing events for two almost two decades, we wanted to begin something special this year, we are taking Six Stars Events a notch higher.</p>
					</div>
					<div class="col-md-6">
						<div class="pop-vid">
							<video controls poster="images/vid-cover.jpg">
								<source src="images/globalgala.mp4" type="video/mp4">
							</video>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<section class="section-sponsors" id="partners">
			<div class="container">
				<div class="section-content">
					<h1>Our <span>Sponsors</span></h1>
					<ul class="row autoplay">
						<li class="col-sm-3">
							<a href="#">
								<img src="images/sponers/01.jpg" alt="image">
							</a>
						</li>
						<li class="col-sm-3">
							<a href="#">
								<img src="images/sponers/02.jpg" alt="image">
							</a>
						</li>
						<li class="col-sm-3">
							<a href="#">
								<img src="images/sponers/03.jpg" alt="image">
							</a>
						</li>
						<li class="col-sm-3">
							<a href="#">
								<img src="images/sponers/04.png" alt="image">
							</a>
						</li>
						<li class="col-sm-3">
							<a href="#">
								<img src="images/sponers/02.jpg" alt="image">
							</a>
						</li>
					</ul>
				</div>
			</div>
		</section>
		
		<section class="section-newsletter">
			<div class="container">
				<div class="section-content">
					<h2>Stay Up to date With Your Favorite Events!</h2>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh <br> euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
					<div class="newsletter-form clearfix">
						<input type="email" placeholder="Your Email Address">
						<input type="submit" value="Subscribe">
					</div>
				</div>
			</div>
		</section>
<!--<script>
$('.autoplay').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 2000,
});
</script>-->