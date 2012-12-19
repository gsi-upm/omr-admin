<?php
require_once("functions.omelette.php");
if(isset($_GET['filter']))
	$filter = array_unique ($_GET['filter']);
if(isset($_POST['validateDescriptions']) OR isset($_POST['rejectDescriptions'])){

			
		if($_POST['rejectDescriptions'] != "")
			$validation = "reject";
		else
			$validation = "true";

		foreach($_POST as $uri){
		
			if(trim($uri) != "" && $uri != "Validate checked" && $uri != "Reject checked"){					
				markDescription($uri,$validation);
			}			
		}	
}
	
	
$order = strip_tags($_GET['order']);
$dir = strip_tags($_GET['dir']);

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>OMR Admin</title>
		<meta name="description" content="">
		<meta name="author" content="">
		
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
		<link href="css/style.css" rel="stylesheet">
		<link href="css/tabs.css" rel="stylesheet">
		<link href="css/tipTip.css" rel="stylesheet">
		
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
		<script src="js/jquery.tipTip.minified.js"></script>
		<script src="js/tooltip.js"></script>
		<script src="js/rating.js"></script>
		<script src="js/tabs.js"></script>
		

		
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript" id="sgvzlr_script" src="http://sgvizler.googlecode.com/svn/release/0.5/sgvizler.js"></script>
		<script type="text/javascript">

		   sgvizler.option.query = {

		   };

	
		   sgvizler.option.namespace['wd'] = 'http://sws.ifi.uio.no/d2rq/resource/';
		   sgvizler.option.namespace['w']  = 'http://sws.ifi.uio.no/ont/world.owl#';

		   sgvizler.option.chart = { 

 
		   };

		   //// Leave this as is. Ready, steady, GO!
		   $(document).ready(sgvizler.go());
		</script>
		
		<?php include("includes/headers.help.php"); ?>
		
		<?php if(isset($_GET['help'])): ?>		
		<link rel="stylesheet" type="text/css" href="css/help.css" media="screen" />
		
		<?php endif; ?>
		
	
		
	
		
		<link rel="Shortcut Icon" href="images/icon.ico" />		

	</head>
	<body>
		<div id="wrapper">
			<div class="header">
				<div class="buscador">
						<form class="searchform" method="get" action="index.php">
							<?php echo (isset($_GET['pending'])) ? '<input type="hidden" name="pending" value="true" />' : ""; ?>
							<input class="searchfield" name="search" id="search" type="text" value="Search..." onfocus="if (this.value == 'Search...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search...';}">
							<input class="searchbutton" type="button" value="Go">
						</form>
				</div>
				<div class="headertext">
					<h1><img width="20" src="http://lab.gsi.dit.upm.es/~pmoncada/omelette/img/omelette_logo_twitter_normal.png" />melette Mashup Registry Administrator</h1> 
					<p>Omelette Mashup Registry contains mashup components that can be used in the construction of mashups for the <span class="pink"><a href=" http://www.ict-omelette.eu">Omelette platform</a></span></p>
					<ol id="toc">
						<li><a href="index.php" class=" <?php echo (isset($_GET['pending']) OR isset($_GET['help'])) ? "inactive" : "active"; ?> " ><span><img src="images/available.png" /> Show approved</span></a></li>
						<li><a href="index.php?pending=true" class=" <?php echo ($_GET['pending'] == "true") ? "active" : "inactive"; ?> " ><span><img src="images/pending.png" /> Show pending</span></a></li>
						<li><a href="index.php?pending=rejected" class=" <?php echo ($_GET['pending'] == "rejected") ? "active" : "inactive"; ?> " ><span><img src="images/rejected.png" /> Show rejected</span></a></li>
						<li><a href="index.php?help=show" class=" <?php echo ($_GET['help'] == "show") ? "active" : "inactive"; ?> " ><span><img src="images/help.png" /> Help</span></a></li>
					</ol>
				</div>				
			</div>
			
			<?php if(isset($_GET['help'])): ?>
					<div class="help">
						<?php include("help.php"); ?>
					</div>
			<?php endif; ?>
			<?php if( !isset($_GET['uri'])  && !isset($_GET['help']) ): ?>
			<div class="sidebar">				

				<?php get_limon_block("rdf:type",$filter); ?>
				<?php get_limon_block("limon:categorizedBy",$filter); ?>
				<?php get_limon_block("limon:developerAccountRequired",$filter); ?>
				<?php get_limon_block("limon:api",$filter); ?>
				<?php get_limon_block("limon:apiForum",$filter); ?>
				<?php get_limon_block("limon:usageFees",$filter); ?>
				<?php get_limon_block("limon:authentication",$filter); ?>
				<?php get_limon_block("limon:dataFormat",$filter); ?>
				<?php get_limon_block("limon:sslSupport",$filter); ?>
				<?php get_limon_block("limon:usageLimits",$filter); ?>
				<?php get_limon_block("limon:describedBy",$filter); ?>
				<?php get_limon_block("limon:protocol",$filter); ?>
	
			</div>
			<?php else: ?>
			<?php endif; ?>
			<?php if( !isset($_GET['uri']) && !isset($_GET['help'])): ?>
			<div class="content">
				<div class="order">
				
					<?php if(isset($_GET['pending'])) $pendinglink = "&pending=".$_GET['pending']; ?>
					Order by <a href="<?php echo $PHP_SELF."?".get_http_filter_var($filter);?>&order=label<?php echo $pendinglink; ?>">Label</a> - <a href="<?php echo $PHP_SELF."?".get_http_filter_var($filter);?>&order=rating<?php echo $pendinglink; ?>">Ranking</a>
					<a href="<?php echo $PHP_SELF."?".get_http_filter_var($filter);?>&order=<?php echo $order; ?>&dir=ASC<?php echo $pendinglink; ?>">[Asc]</a>
					<a href="<?php echo $PHP_SELF."?".get_http_filter_var($filter);?>&order=<?php echo $order; ?>&dir=DESC<?php echo $pendinglink; ?>">[Desc]</a>
				</div>			
					<?php get_results($filter,$order,$dir); ?>
				
				<?php elseif( isset($_GET['uri']) ): ?>
					<?php get_predicates($_GET['uri']); ?>				
			
				<?php endif; ?>
			</div>
			
			<div class="footer">
				<div class="projectText">
					<p>Project Number: 257635<br />
					Project duration: 30 months<br />
					ICT-2009.1.2 "Internet of Services, Software and Virtualisation"</p>
				</div>
				<div class="copyright">
					<p class="copyright-notice">© 2009-2012 Omelette Project. All Rights Reserved.</p>
				</div>
				<div class="projectLogos">
					<img width="200px" src="images/gsi-logo.png" /> <img src="images/logos_footer.gif" />
				</div>
			</div>
		</div>
	</body>
</html>
