<?php


require("functions.omelette.php");

$resource = $_GET['resource'];

if($wadl = generateWadl($resource)){
	header("Content-type: text/xml");
	echo $wadl;
}else{
	"No WADL available for this resource";
}



?>
