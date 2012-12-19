<?php

/**
/* CONFIGURE LOCAL sesame OR omr
*/
$endpoint = "omr"; // (sesame,omr)

require_once("omr.php");	
$omr = new Omr($endpoint);


//Required Sparql library
require_once( "sparqllib_$endpoint.php" );

// Sesame server
// Query url, example: http://shannon.gsi.dit.upm.es:18080/openrdf-workbench/repositories/pmoncada/query

if($endpoint == "sesame")
	$server = $omr->server;
else
	$server = $omr->server;

$debug = false;

/* Global variables */
$mashupCount = 0;


if(isset($_GET['pending'])){
	if($_GET['pending'] == "true"){
		$pending = "OPTIONAL { ?a limon:revised ?revised  FILTER (!BOUND(?revised)) }.";
		$admin = adminAuth();
	}
	else if($_GET['pending'] == "rejected")
		$pending = "?a limon:revised \"reject\"";
}else{
	$pending = "?a limon:revised \"true\"";
}

//$pending = (isset($_GET['pending'])) ? "OPTIONAL { ?a limon:revised ?revised }. FILTER (!BOUND(?revised))"  : "?a limon:revised \"true\"";

 
$db = sparql_connect($server);
if( !$db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

/* Define namespaces here */

$db->ns( "foaf","http://xmlns.com/foaf/0.1/" );
$db->ns( "rdf","http://www.w3.org/1999/02/22-rdf-syntax-ns#" );
$db->ns( "limon","http://www.ict-omelette.eu/schema.rdf#" );
$db->ns( "ctag","http://commontag.org/ns#" );
$db->ns( "rosm","http://www.wsmo.org/ns/rosm/0.1#" );
$db->ns( "hrests","http://www.wsmo.org/ns/hrests#" );
$db->ns( "rdfs","http://www.w3.org/2000/01/rdf-schema#" );
$db->ns( "dc","http://purl.org/dc/elements/1.1/" );
$db->ns( "omr","http://www.ict-omelette.eu/omr/categories/" );



/**
/* Returns a list with a specified predicate and suitable filters
/* @author Pablo Moncada
/* @version 30/04/2012
*/

function get_limon_block($predicate,$filters){
	global $debug, $pending, $mashupCount;
	
	$filtrosql = get_sparql_filters($filters);
	$search = (isset($_GET['search'])) ? 'FILTER regex(?desc, "'.$_GET['search'].'", "i" )' : "";
	$wadl = (isset($_GET['wadl'])) ? '?a limon:describedBy ?b . ?b rosm:supportsOperation ?c .' : "";
	$searchUrl = (isset($_GET['search'])) ? "&search=".$_GET['search'] : "";
	
	
	$sparql = "SELECT DISTINCT ?o (count(distinct ?a) as ?total ) WHERE {
	 ?a $predicate ?o .
	 OPTIONAL { ?a dc:description ?desc }.
	 $filtrosql
	 $pending
	 $search
	 $wadl
	 }
	 
	 GROUP BY ?o
	 ORDER BY DESC(?total)
	 LIMIT 100";
	 

	if($debug)
		echo "<p>Query: <br/>".htmlentities($sparql)."</p>";
	
	echo '<div class="filterBlock">';	
	echo '<p class="filterPredicate"><a href="graph.php?query='.base64_encode(str_replace("\"","'",$sparql)).'" class="filterHelp iframe" title="'.getOntologyHelp($predicate).'" ><img src="images/pie.png" />'.$predicate.' </a></p>';
	
	$filter_var = get_http_filter_var($filters);
	
	$pending_get = (isset($_GET['pending'])) ? "&pending=".$_GET['pending'].""  : "?";
	 
	$result = execute_query($sparql);
	echo '<div class="limon_block">';
	echo '<a href="#" class="filterHelp" title="Es una prueba" >'.$limon.'</a>';
		echo '<div class="scroll">';
			echo "<ul>";	
			$noresults = true;
			foreach($result as $key => $field){
				$field = replace_ns($field);
				if($field['total'] != "0" && trim($field['o']) != ""){
					echo '<li><a class="filterHelp" title="'.$field[o].'"href="'.$PHP_SELF.'?'.$filter_var."filter[]=".$predicate.",".base64_encode($field[o]).$pending_get.$searchUrl.'">'.stripprefix($field[o]).'</a> <span class="count">'.$field[total].'</span></li> ';
					if($predicate == "rdf:type") $mashupCount = $mashupCount + $field['total'];
					$noresults = false;
				}
				if($noresults)
					echo "No results";
			}
			if($filtrosql == "" && $_GET['pending'] == "true" && $search == "")
				storeMashupCount($mashupCount);
			echo "</ul>";
		echo '</div><!-- end div scroll -->';
	echo '</div><!-- end div limon_block -->';
	echo '</div>';

}

/**
/* Returns a list with specified filters
/* @author Pablo Moncada
/* @version 30/04/2012
*/

function get_results($filters,$order,$dir){
	global $debug, $pending, $admin;

	$filtrosql = get_sparql_filters($filters);
	$filter_var = get_http_filter_var($filters);
	
	if($_GET['pending'] == "true") $validate = true;
	
	$search = (isset($_GET['search'])) ? 'FILTER regex(?desc, "'.$_GET['search'].'", "i" )' : "";
	$wadl = (isset($_GET['wadl'])) ? '?a limon:describedBy ?b . ?b rosm:supportsOperation ?c .' : "";
	
	$sparql = "SELECT DISTINCT ?a ?label ?desc ?uri ?rating WHERE {
	 $filtrosql
	 ?a rdfs:label ?label .
	 ?a dc:description ?desc .
	 OPTIONAL { ?a limon:rating ?rating }.
	 $pending
	 $search
	 $wadl
	 }	 ";
	 
	if(trim($order) != ""){
		if($dir == "DESC")
			$sparql .= " ORDER BY DESC(?$order)";
		else
			$sparql .= " ORDER BY ?$order";
	}
	$sparql .= " LIMIT 100";
	
	if($debug)
		echo "<p>Query: <br/>".htmlentities($sparql)."</p>";
	 
	$result = execute_query($sparql);

	echo $limon;
		echo '<div class="results"><form name="validation" method="post" action="index.php?pending=true" >';
			echo '<div class="resultheader">';
				echo ($validate && $admin) ? '<input name="validateDescriptions" id="validateDescriptions" type="submit" value="Validate checked" /><input name="rejectDescriptions" id="rejectDescriptions" type="submit" value="Reject checked" />' : '';
				echo '<div class="resultcount">Showing '.count($result).' out of '.getMashupCount().' mashups</div>';
			echo '</div>';
			echo "<ul>"; 
			$i = 0;
			foreach($result as $field){
				echo "<li class=\"ulresults\">";
				
				echo '<div class="resultrating">';
					echo '<span class="stars">'.(($field['rating'])*5).'</span>';
				echo '</div>';
				
				echo '<div class="resultinfo">';
					echo ($validate && $admin) ? '<input type="checkbox" name="uri'.$i.'" value="'.$field[a].'">' : "";
					echo '<a href="'.$PHP_SELF.'?uri='.$field[a].'">'."<span class=\"label\">".$field[label]."</span></a><br/> ".shortenDescription(htmlentities(utf8_encode($field[desc])),$field[a]);
				echo '</div>';
				
				echo "</li>";
				$i++;
			}
			echo "</ul>";
		echo '</div><!-- end div scroll -->';
		echo ($validate && $admin) ? '</form>' : '';
	echo '<!-- end div limon_block -->';

}


function validateSeveralDescriptions(){
	if(isset($_POST['validateDescriptions'])){
		foreach($_POST as $uri){
			if(trim($uri) != "" && $uri != "Validate checked" && $uri != "Reject checked")
				markDescription($uri);			
		}
	
	}elseif(isset($_POST['rejectDescriptions'])){
		foreach($_POST as $uri){
			if(trim($uri) != "" && $uri != "Validate checked" && $uri != "Reject checked")
				markDescription($uri);			
		}
	
	}
}


/**
/* Returns a list of all predicates of a subject
/* @author Pablo Moncada
/* @params Subject
/* @version 30/04/2012
*/

//TODO: Cambiar esta parte a omr2.php, no en functions
function get_predicates($a){
	global $debug, $db;
	
	$sparql = "SELECT DISTINCT ?p ?o  WHERE {
	 $filtrosql
	 <$a> ?p ?o .
	  
	 } LIMIT 100";
	if($debug)
		echo "<p>Query: <br/>".htmlentities($sparql)."</p>";
		
	$result = $db->query( $sparql ); 
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }	 
	$fields = $result->field_array( $result );
	
	while( $row = $result->fetch_array( $result ) )
	{
		$row = array_unique($row);
		$properties[replace_ns($row[p])][] = $row[o];

		foreach( $fields as $field )
		{
			//echo "<li>".replace_ns($row[p])." -> ".$row[o]."</li>";
			/*if(!in_object($row[o],$properties))
				$properties[replace_ns($row[p])][] = $row[o];
			*/
			//echo "$row[p]: $row[o]</br>";
		}
	}
	
	
	echo '<div class="predicates"><br/>';
	foreach($properties as $key => $property){
		array_unique($property);
		echo "<b>$key</b><ul id=\"double\">";
		foreach($property as $sub)
			echo "<li>$sub</li>";
		echo "</ul>";
	}
	if($wadl = generateWadl($a)){
		echo "<b>Wadl</b><ul id=\"double\"><li>".htmlentities($wadl)."</li></ul>";
	}
	echo '</div>';
		 
	
}

/**
/* This functions marks as "revised: true" a description
*/

function markDescription($uri,$revised="true"){


	global $debug, $db, $endpoint, $omr;
	
	$sparql = "SELECT DISTINCT ?p ?o  WHERE {
	 $filtrosql
	 <$uri> ?p ?o .
	  
	 } LIMIT 100";
	if($debug)
		echo "<p>Query: <br/>".htmlentities($sparql)."</p>";
		
	$result = $db->query( $sparql ); 
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }	 
	$fields = $result->field_array( $result );
	
	while( $row = $result->fetch_array( $result ) )
	{
		$row = array_unique($row);
		$properties[replace_ns($row[p])][] = $row[o];

	}
	
	$type = $properties['rdf:type'][0];
	
	$rdf = '<?xml version="1.0" encoding="UTF-8"?>
	<rdf:RDF
		xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">';
	$rdf .= '<rdf:Description rdf:about="'.$uri.'">';
	$rdf .= '<rdf:type rdf:resource="'.$type.'"/>';
	$rdf .= '<revised xmlns="http://www.ict-omelette.eu/schema.rdf#">'.$revised.'</revised>';
	$rdf .= '</rdf:Description>';
	$rdf .= '</rdf:RDF>';
	
	

	
	$omr->insert($rdf);
	
	//echo "Code: ".$omr->error;

}


	


/**
/* Executes SPARQL query
/* @author Pablo Moncada
/* @version 30/04/2012
/* @params $query: "Sparql query format"
*/

function execute_query($query){
	global $db, $sparql;
	$result = $db->query( $query ); 
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

	$fields = $result->field_array( $result );	
	while( $row = $result->fetch_array( $result ) )
	{
	/*	foreach( $fields as $key => $field )
		{
			$return[$key][] = $row[$field];
		}
	*/
	
	#TODO ->Cambiar la forma de almacenar en los arrays, que es una cutrada ahora mismo<-
	// Ahora hace por ejemplo, $return[0][o],$returl[0][total]  $return[1][o],$return[1][total]
	foreach( $row as $key => $field )
		{			
			$return[$i][$key] = $field;
				if($key == "total" || $key == "desc") $i++;
		}	
	}
	return $return;

}


/**
/* Replaces longname with short names
/* @author Pablo Moncada
/* @version 30/04/2012
/* @params $resource
*/

function replace_ns($resource){
	global $db;
	
	foreach($db->ns as $short => $long){
		$resource = str_replace($long,$short.":",$resource);
	}
	return $resource;
}

/**
/* Gets filter in http format
/* @author Pablo Moncada
/* @version 30/04/2012
/* @params $filters[]: "Array with all the filters in the proper format"
*/

function get_http_filter_var($filters){
	if(count($filters) > 0){
		foreach($filters as $filter){
			$filter_var .= "filter[]=$filter&";
		}
		
	}
	return $filter_var;
}

/**
/* Gets filters in sparql format
/* @author Pablo Moncada
/* @version 30/04/2012
/* @params $filters[]: "Array with all the filters in the proper format"
*/
function get_sparql_filters($filters){
	if(count($filters) > 0){
			foreach ($filters as $filter){
				$filter = str_replace("And","&amp;",$filter);
				$explode = explode(",",$filter);
				
				$value = str_replace("And","&amp;",base64_decode($explode[1]));
				
				if(eregi(":",$value) && !eregi("http://",$value))
					$filtrosql .= "?a $explode[0] $value .";
				elseif(eregi("http://",$value))
					$filtrosql .= "?a $explode[0] <$value> .";				
				else
					$filtrosql .= "?a $explode[0] \"$value\" .";		
			}
	}
	return $filtrosql;
}



/**
/* It removes de rdfs:label,label duple from the filters
/* @author Pablo Moncada
/* @version 30/04/2012
/* @params $filters[]: "Array with all the filters in the proper format"
*/
function remove_label_filter_var($filters){
	foreach($filters as $key => $filter){
		if(eregi("rdfs:label",$filter))
			unset($filters[$key]);
	}
	return $filters;
}

function in_object($val, $obj){

    if($val == ""){
        trigger_error("in_object expects parameter 1 must not empty", E_USER_WARNING);
        return false;
    }
    if(!is_object($obj)){
        $obj = (object)$obj;
    }

    foreach($obj as $key => $value){
        if(!is_object($value) && !is_array($value)){
            if($value == $val){
                return true;
            }
        }else{
            return in_object($val, $value);
        }
    }
    return false;
}

function put_rdf_to_omr($rdf){


}

function get_unrevised() {
	$query = 'SELECT DISTINCT ?a ?label ?desc
	WHERE {
	 ?a rdfs:label ?label . 
	 ?a dc:description ?desc . 
	 OPTIONAL { ?a limon:revised ?revised }.
	 FILTER (!BOUND(?revised)) 
	}

	}';
	
}

function getOntologyHelp($p){
	$help["rdf:type"] = "Type of the component, like Service or Widget";
	$help["limon:sslSupport"] = "The properties authentication and sslSupport allow of specifying how security over the
component's data transport is implemented";
	$help["limon:protocol"] = "";
	$help["limon:dataFormat"] = "";
	$help["limon:authentication"] = "The properties authentication and sslSupport allow of specifying how security over the component's data transport is implemented";
	$help["limon:api"] = "The property api links to the specification of the component's programming interface";
	$help["ctag:tagged"] = "The properties tag and category allow of linking to tags and categories, respectively, that represent the functionality of the component";
	$help["limon:example"] = "The property example allows of referencing examples of the use of the component's API";
	$help["limon:clientInstallRequired"] = "The property clientInstallRequired indicates whether or not the web component requires an additional component installed clientside to work";
	$help["limon:endpoint"] = "endpoint allows linking to the particular Uniform Resource Locator (URL) where the component runs. Also, the properties of dataFormat and protocol allow of specifying how the data, if any, is exchanged with the component";
	$help["limon:describedBy"] = "";
	$help["limon:apiPage"] = "The properties of apiForum and apiBlog address the issue of company trustworthiness by providing means to reference support facilities (i.e., forums and blogs) that the vendor provides to component users. Also, the property provider allows of identifying the vendor of the component, for any company trust issues involved.";
	$help["limon:apiForum"] = "The properties of apiForum and apiBlog address the issue of company trustworthiness by providing means to reference support facilities (i.e., forums and blogs) that the vendor provides to component users. Also, the property provider allows of identifying the vendor of the component, for any company trust issues involved.";
	$help["limon:provider"] = "The property provider serves to identify the vendor of the component for any business issues involved";
	$help["limon:rating"] = "The rating property serves as an indicator of popularity. It represents the rating made by users in repositories reecting their degree of satisfaction with a particular component";
	$help["limon:commercialLicense"] = "The property commercialLicense links to a commercial license for the use of
the component, if any";
	$help["limon:usageFees"] = "The property usageFees is a cost aspect property that links to any cost incurred when using the component";
	$help["limon:developerKeyRequired"] = "The property developerKeyRequired indicates whether or not the component requires creating a developer account prior to its use";
	$help["limon:termsAndConditions"] = "Regarding legal issues, the property termsAndConditions allows linking to a document that informs about the conditions for the use of the component";
	$help["dc:source"] = "";
	$help["dc:description"] = "";
	$help["limon:categorizedBy"] = "";
	$help["limon:uses"] = "The property uses is employed to link to reused components. For example, it can be used to indicate which services or data feeds a mashup reuses";

	if($help["$p"] != "")
		return $help["$p"];
	else
		return "No help available";
}

function adminAuth(){
	$realm = 'Press cancel just for viewing';

	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		header('WWW-Authenticate: Basic realm="'.$realm.'"');
		header('HTTP/1.0 401 Unauthorized');
		return false;
	} else {
		return isUserValid($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);

	}


}

function isUserValid($user,$password){
	if($user == "admin" && $password == "OmrAdmin"){
		return true;
	}else{
		header('WWW-Authenticate: Basic realm="'.$realm.'"');
		header('HTTP/1.0 401 Unauthorized');
	}
}

function shortenDescription($description,$uri){
	if(strlen($description) > 255){
		$description = substr($description,0,255);
		$description .= "... ";
		$description .= '<a href="index.php?uri='.$uri.'">Read more</a>';
	}
	
	
	return $description;
}

function storeMashupCount($count){
	return file_put_contents("cache/mashupCount.cache",$count);
}

function getMashupCount(){
	return file_get_contents("cache/mashupCount.cache");
}



/**
/* Generates a WADL given a resource with rosm
/* Only available for Yahoo pipes
/*
*/
function generateWadl($resource){
	global $db, $sparql;

	$sparql = 'SELECT DISTINCT ?parameter ?getUrl WHERE{
	 <'.$resource.'> limon:describedBy ?a .
	 ?a rosm:supportsOperation ?b .
	 ?b hrests:hasAddress ?getUrl .
	 ?b rosm:requestURIParameter ?c .
	 ?c rdfs:label ?d .
	 ?d rdfs:label ?parameter	 
	}';
	
	
	$result = $db->query( $sparql ); 
	$fields = $result->field_array( $result );
	$return = array();
	while( $row = $result->fetch_array( $result ) ){
		foreach( $row as $key => $field ){			
				$return[$key][] = $field;			
		}
	}
	
	$base = dirname($return['getUrl'][0]);
	$path = basename($return['getUrl'][0]);
	$params = $return['parameter'];
	
	if(count($params) == 0)
		return false;
	
	
	
	$wadl = '<?xml version="1.0" encoding="utf-8"?>
	<application
	    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	    xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	    xsi:schemaLocation="http://research.sun.com/wadl/2006/10 wadl.xsd"
	    xmlns="http://research.sun.com/wadl/2006/10">
	  <resources base="'.$base.'/">
	    <resource path="'.urlencode($path).'">
	      <method name="GET">
		<request>';
		foreach($params as $param)
		 $wadl .= "\n\t\t".'<param name="'.$param.'" type="xsd:string" style="query" />';
	$wadl .= 	"\n\t</request>
	      </method>
	    </resource>
	  </resources>
	</application>";
		
	
	return $wadl;

}


/**
/* Cleans up prefix
/*
*/
function stripprefix($resource){	
	
	if(eregi("/",$resource)){
		$resource = substr($resource,0,-1);
		return substr(strrchr($resource, "/"), 1);
	}elseif(eregi(":",$resource)){
		return substr(strrchr($resource, ":"), 1);
	}else
		return $resource;

}



?>
