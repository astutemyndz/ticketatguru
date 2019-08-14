$(document).ready(function(){
	$('.imgal-img').click(function(){
		let imageSrc = $(this).attr("src");
		let imageAlt = $(this).attr("alt");

		//$('.imgal-container').hide();

		$('.gallery-sec').append(
			'<div class="imgal-modal">'+
			'<span id="imgal-modal-close"">X</span>'+
			'<i class="fa fa-chevron-left" id="prev" aria-hidden="true"></i>'+
			'<img src="' + imageSrc + '" alt="' + imageAlt + '" class="imgal-modal-img"></img>'+
			'<i class="fa fa-chevron-right next" id="next" aria-hidden="true"></i>'+
			'</div'
		).show('slow');
		
		$(".imgal-modal img:gt(0)").hide();
		$("#next").click(function(){
			$(".imgal-modal img:first").fadeOut().next().fadeIn().end().appendTo(".imgal-modal")
		})
		 
		$("#prev").click(function(){
			$(".imgal-modal img:last").prepentTo(".imgal-modal").fadeIn().next().fadeOut()
		})
		 
		//$("#next").click(function(){
		  //alert("Test");
		//});
		
		$('#imgal-modal-close').click(function(){
			//$('.imgal-container').show();
			$('.imgal-modal').hide('fast', function(){
				$(this).remove();
			});
		});
	});
});
/****************** Prev Next JS******************/
// $(document).ready(function(){
	//$(".imgal-modal img:gt(0)").hide();
	
	// $("#next").click(function(){
		//$(".imgal-modal img:first").fadeOut().next().fadeIn().end().appendTo(".imgal-modal")
		// alert("Test.");
	// });
// });


$(document).ready(function(){
	
 });