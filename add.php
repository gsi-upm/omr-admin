<?php
require ("functions.omelette.php");

var_dump($_POST);

if(isset($_POST['validateDescriptions'])){
		foreach($_POST as $uri){
			if(trim($uri) != "" && $uri != "Validate checked"){
				echo "<p><b>Added: $uri</b></p>";
				validateDescription($uri);
			
			}
				
		}
		echo "<p>Descriptions validated, <a href=\"index.php\">go back</a></p>";
	
	}

?>