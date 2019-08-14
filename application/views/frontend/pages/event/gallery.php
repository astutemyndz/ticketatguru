<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php /*?>
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="gallery-sec">
					<div class="imgal-container">
					<?php if(count($gallery)>0){ 
					foreach($gallery as $galleryVal){
					?>
						<img src="<?php echo $galleryVal['gallery_image'];?>" alt="<?php echo $galleryVal['title'];?>">
					<?php } }else{ ?>
						<img src="<?php echo base_url();?>images/gallery/01.jpg" alt="image">
					<?php } ?>	
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php */?>
<section class="section-gallery">
			<div class="container">
				<div class="row">
					<h1>Our Gallery</h1>
					<div class="gallery-list row">
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
									<h3>Ticket At Guru</h3>
								</div>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</section>
