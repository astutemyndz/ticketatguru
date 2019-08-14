<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
		<section class="section-todays-schedule">
			<div class="container">
				<div class="row">
					<div class="section-header">
						<h2>Today's Schedule</h2>
						<span class="todays-date"><i class="fa fa-calendar" aria-hidden="true"></i> <strong><?php echo $newDate = date("jS M, Y", strtotime($today)); ?></strong></span>
					</div>
					
					<div class="section-content">
						<ul class="clearfix autoplay">
						<?php if(count($showTimes) > 0) { ?>
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
						<?php } else { ?>
							<li class="event1-2">
								<strong class="event-name">Show not available</strong>
								<a href="<?php echo base_url('contact-us');?>" class="get-ticket">Contact Us</a>
							</li>
						<?php } ?>	
						</ul>
						<!-- <strong class="event-list-label">Full Event <span>Schedules</span></strong> -->
					</div>
							
				</div>
			</div>
		</section>
		<section class="section-upcoming-events" id="events">
			<div class="container">
				<div class="row">
					<div class="section-header">
						<h2>Events</h2>
						<!-- <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut.</p> -->
						<a href="<?php echo base_url('event/list');?>">See all upcoming events</a>
					</div>
					
					<div class="section-content">
						<ul class="clearfix">
							<?php if(count($events) > 0) { ?>
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
							<?php } else { ?>
								<li>
									<a href="#">
										<img src="<?php echo base_url();?>/images/sample-event.jpg" alt="Ticket at guru">
									</a>
									<div class="info">
										<p>Comming Soon</p>
										<a href="<?php echo base_url('contact-us');?>" class="get-ticket">Contact Us</a>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>
					
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
		<section class="section-gallery">
			<div class="container">
				<div class="row">
					<h1>A STORY BEHIND A PICTURES</h1>
					<div class="gallery-list">
						<?php if(count($gallery)>0){ 
							foreach($gallery as $key=>$galleryVal){
							?>			
						<div class="gallery-img col-xs-6 col-sm-3">
							<a href="#" data-featherlight="#content-<?php echo $key;?>">
								<img src="<?php echo $galleryVal['gallery_image'];?>" alt="<?php echo $galleryVal['title'];?>">
							</a>
							<div id="content-<?php echo $key;?>" class="gallery-lightbox">
							<img src="<?php echo $galleryVal['gallery_image'];?>" alt="<?php echo $galleryVal['title'];?>">
								<div class="gallery-lightbox-content">
									<h3><?php echo $galleryVal['title'];?></h3>
									<?php echo $galleryVal['description'];?>
								</div>
							</div>
						</div>
						<?php } }else{ ?>
							<div class="gallery-img col-xs-6 col-sm-3">
								<a href="#" data-featherlight="#content-1">
									<img src="<?php echo base_url();?>images/gallery/01.jpg" alt="image">
								</a>
								<div id="content-1" class="gallery-lightbox">
									<img src="<?php echo base_url();?>images/gallery/01.jpg" alt="image">
									<div class="gallery-lightbox-content">
										<h3>Lorem ipsum dolor sit amet</h3>
										<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
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
						<?php echo $Cms_About['i18n'][1]['cms_description']?>
						
					</div>
					<div class="col-md-6">
						<?php 
						// echo "<pre>"; print_r($video);
						if(count($video)>0){ 
							
						?>
						<div class="pop-vid">
							<video controls poster="images/vid-cover.jpg">
								<source src="<?php echo $video['video_path']; ?>" type="<?php echo $video['mime_type']; ?>">
							</video>
						</div>
						<?php   } else{ ?>
							<div class="pop-vid">
								<video controls poster="images/vid-cover.jpg">
									<source src="images/globalgala.mp4" type="video/mp4">
								</video>
							</div>
						<?php }   ?>
					</div>
				</div>
			</div>
		</section>
		
		<section class="section-sponsors" id="partners">
			<div class="container">
				<div class="section-content">
					<h1>Our <span>Sponsors</span></h1>
					<ul class="row autoplay">
						<?php if(count($sponsorsData)>0){
							foreach($sponsorsData as $sponsorsDataKey=>$sponsorsDataVal){
						?>
						<li class="col-sm-3">
							<a href="<?php echo $sponsorsDataVal['sponsor_link']?>" target="_blank">
								<img src="<?php echo $sponsorsDataVal['sponsor_image']?>" alt="<?php echo $sponsorsDataVal['name']?>">
							</a>
						</li>
						<?php } }else{ ?>
							<li class="col-sm-3">
								<a href="#">No sponsors available</a>
							</li>
						<?php } ?>
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