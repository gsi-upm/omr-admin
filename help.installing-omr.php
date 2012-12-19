	<h2>Installing & configuration</h2>
			<h3>Requisites</h3>
			<p>The applicaton is written in PHP and runs at any capable web server, as Apache. It is also needed cURL for PHP, it is used to make the sparql queries over REST.</p>
			<pre><ul><li>Apache2</li><li>PHP5 with cURL support</li></ul></pre>
			
			<h3>Deploying</h3>
			<p>Just extract the package and edit the functions.omelelette.php file.<br/>You can chose between using OMR with REST interface implemented, or raw Sesame openrdf-workbench. Edit the $endpoint variable, and select "omr" or "sesame"</p>
			<p><i>functions.omelelette.php</i></p>
			<pre>/**
/* CONFIGURE LOCAL sesame OR omr
*/
$endpoint = "sesame"; // (sesame,omr)
$sesame_server = "http://shannon.gsi.dit.upm.es:18080/openrdf-workbench/repositories/omr/query";
$omr_server = "https://vsr-web.informatik.tu-chemnitz.de/omr-write/components/sparql";</pre>

			<h3>Configuring visualization</h3>
			<p>It is easy to chose wich filtering criteria is shown, by default, at <i>index.php</i>, it is like</p>
			<pre><?php echo htmlentities('<div class="sidebar">				
	<div class="subsidebar_left">
		<?php get_limon_block("rdf:type",$filter); ?>
		<?php get_limon_block("limon:categorizedBy",$filter); ?>
		<?php get_limon_block("limon:developerAccountRequired",$filter); ?>
		<?php get_limon_block("limon:api",$filter); ?>
		<?php get_limon_block("limon:apiForum",$filter); ?>
		<?php get_limon_block("limon:usageFees",$filter); ?>
	</div>
	<div class="subsidebar_right">
		<?php get_limon_block("limon:authentication",$filter); ?>
		<?php get_limon_block("limon:dataFormat",$filter); ?>
		<?php get_limon_block("limon:sslSupport",$filter); ?>
		<?php get_limon_block("limon:usageLimits",$filter); ?>
		<?php get_limon_block("limon:describedBy",$filter); ?>
		<?php get_limon_block("limon:protocol",$filter); ?>
	</div>			
</div>'); ?></pre>
<p>If you want to add new filter, just add on the left or on the right subsidebar:</p>
<pre>
<?php echo htmlentities('<?php get_limon_block("newfilter",$filter); ?>'); ?>
</pre>
<p>Atributes are defined in the ontology.</p>
		