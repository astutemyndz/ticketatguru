<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="contact-sec">
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<?php
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
			<div class="col-md-7">
				<div class="cont-form">
					<h3><?php echo $about_us['title'];?></h3>
					<?php echo $about_us['description'];?>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</section>
