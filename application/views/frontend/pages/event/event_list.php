<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo $selected_date ."==". $today;
//  echo "<pre>"; print_r($events);
?>


<section class="section-artist-content">
<div class="container">
	<div class="row">
		<div id="primary" class="col-sm-12 col-md-12">
		<?php if(count($events) > 0) {?>
			<?php foreach($events as $event) { $lastEventDate = end($event['shows']); ?>
			<div class="artist-event-item">
				<div class="row">
					<div class="artist-event-item-info col-sm-9">
						<h3><?php echo $event['event']['title'];?></h3>
						<ul class="row">
							<li class="col-sm-5">
								<span>Venue</span>
								Alun-alun kidul
								<span class="location">Yogyakarta, Indonesia</span>
							</li>
							<li class="col-sm-4">
								<span><?php echo date("l", strtotime($lastEventDate)); ?></span>
								<?php echo date("jS M Y", strtotime($lastEventDate)); ?>
							</li>
							<li class="col-sm-3">
								<span>Time</span>
								07:00 PM
							</li>
						</ul>
					</div>
					<div class="artist-event-item-price col-sm-3">
						<span>Price From</span>
						<strong>$83</strong>
						<a href="<?php echo base_url();?>event/details/<?php echo $event['event']['id'];?>">View Details</a>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php } else { ?>
				<span>Comming Soon</span>
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