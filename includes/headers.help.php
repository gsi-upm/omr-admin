	<!-- Add mousewheel plugin (this is optional) -->
		<script type="text/javascript" src="js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

		<!-- Add fancyBox -->
		<link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css?v=2.1.2" type="text/css" media="screen" />
		<script type="text/javascript" src="js/fancybox/source/jquery.fancybox.pack.js?v=2.1.2"></script>

		<!-- Optionally add helpers - button, thumbnail and/or media -->
		<link rel="stylesheet" href="js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
		<script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
		<script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.4"></script>

		<link rel="stylesheet" href="js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
		<script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
		
		<!-- styles needed by jScrollPane -->
		<link type="text/css" href="css/jquery.jscrollpane.css" rel="stylesheet" media="all" />

		<!-- the mousewheel plugin - optional to provide mousewheel support -->
		<script type="text/javascript" src="js/jquery.mousewheel.js"></script>

		<!-- the jScrollPane script -->
		<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
		
		<script>
		
		$(document).ready(function() {
			$(".fancybox").fancybox();
			
			$(".iframe").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'autoScale'     	: false,
				'type'			: 'iframe',
				'width'			: 800,
				'height'		: 400,
				'scrolling'   		: 'no'
			});
			
			$('.scroll').jScrollPane();
			$('.results').jScrollPane();
		});
		</script>
