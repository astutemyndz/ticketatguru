<!doctype html>
<html>
	<head>
		<title>Ticket at Guru</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->getCss() as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		foreach ($controller->getJs() as $js)
		{
			echo '<script src="'.(isset($js['remote']) && $js['remote'] ? NULL : PJ_INSTALL_URL).$js['path'].htmlspecialchars($js['file']).'"></script>';
		}
		?>
		<!--[if gte IE 9]>
  		<style type="text/css">.gradient {filter: none}</style>
		<![endif]-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
		<style>
		
		</style>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>
		<script src="<?php echo PJ_INSTALL_URL;?>core/third-party/panzoom/1.0.0/jquery.panzoom.js"></script>
		<script src="<?php echo PJ_INSTALL_URL;?>core/third-party/panzoom/1.0.0/jquery.mousewheel.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
		
	</head>
	<body>
		<div id="container">
    		<div id="header">
    			<div id="logo">
    				<a href="<?php echo PJ_INSTALL_URL;?>" target="_blank" rel="nofollow">Ticket at Guru</a>
					<span>v<?php echo PJ_SCRIPT_VERSION;?></span>
    			</div>
			</div>
			
			<div id="middle">
				<div id="leftmenu">
					<?php require PJ_VIEWS_PATH . 'pjLayouts/elements/leftmenu.php'; ?>
				</div>
				<div id="right">
					<div class="content-top"></div>
					<div class="content-middle" id="content">
					<?php require $content_tpl; ?>
					</div>
					<div class="content-bottom"></div>
				</div> <!-- content -->
				<div class="clear_both"></div>
			</div> <!-- middle -->
		
		</div> <!-- container -->
		<div id="footer-wrap">
			<div id="footer">
			   	<p>Copyright &copy; <?php echo date("Y"); ?> <a href="https://www.astutemyndz.com/" target="_blank">astutemyndz.com</a></p>
	        </div>
		</div>
		<script>
			// (function() {
			// 	var $section = $('.wrapper-image');
			// 	$section.find('.panzoom').panzoom({
			// 	$zoomIn: $section.find(".zoom-in"),
			// 	$zoomOut: $section.find(".zoom-out"),
			// 	$zoomRange: $section.find(".zoom-range"),
			// 	$reset: $section.find(".reset")
			// 	});
			// })();
/*
			var $pz2 = $("#mapHolder");
			var $buttons = $('#PLKZOOMBTNWRAPPER');
			$pz2.panzoom({
				$zoomIn: $buttons.find(".zoom-in"),
				$zoomOut: $buttons.find(".zoom-out"),
				$reset: $buttons.find(".reset"),
				cursor: "grab",
				minScale: 1
			});
			$pz2.parent().on('mousewheel.focal', function(e) {
				e.preventDefault();
				var delta = e.delta || e.originalEvent.wheelDelta;
				var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
				$pz2.panzoom('zoom', zoomOut, {
					increment: 0.1,
					focal: e
				});
			});
			*/
		</script>
	</body>
</html>