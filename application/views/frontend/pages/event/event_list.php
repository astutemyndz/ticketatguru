<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo $selected_date ."==". $today;
//  echo "<pre>"; print_r($events);
//  echo "<pre>"; print_r($showTimes);
?>


<section class="section-artist-content">
<div class="container">
	<div class="row">
		<div id="primary" class="col-sm-12 col-md-12">
		<?php if(count($events) > 0) { ?>
			<?php foreach($events as $event) { 
				$firstEventDate = reset($event['shows']); 
				$firstEventPrice = reset($event['Price']); 
				?>
			<div class="artist-event-item">
				<div class="row">
					<div class="artist-event-item-info col-sm-10">
						<h3><?php echo $event['event']['title'];?></h3>
						<ul class="row">
							<li class="col-sm-3">
								<span>Venue</span>
								Alun-alun kidul
								<span class="location">Yogyakarta, Indonesia</span>
							</li>
							<li class="col-sm-2">
								<span><?php echo date("l", strtotime($firstEventDate)); ?></span>
								<?php echo date("jS M Y", strtotime($firstEventDate)); ?>
							</li>
							<li class="col-sm-7">
								<span>Small Details</span>
								<?php echo substr($event['event']['description'],0,150)."....";?>
							</li>
						</ul>
					</div>
					<div class="artist-event-item-price col-sm-2">
						<span>Price From</span>
						<strong>£ <?php echo $firstEventPrice;?></strong>
						<a href="<?php echo base_url();?>event/details/<?php echo $event['event']['id'];?>">View Details</a>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php } else { ?>
				<div class="artist-event-item">
					<div class="row">
						<div class="artist-event-item-info col-sm-10">
							<h3>Comming Soon</h3>
						</div>
						<div class="artist-event-item-price col-sm-2">
							<a href="<?php echo base_url('contact-us');?>" class="get-ticket">Contact Us</a>
						</div>
					</div>
				</div>
			<?php } ?>
			
			<!-- <div class="artist-event-footer">
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
</div>
</section>