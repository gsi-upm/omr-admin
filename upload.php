<?php

require("omr.php");

$omr = new Omr();

$rdf = file_get_contents("export.rdf");

/*
$rdf = '<?xml version="1.0" encoding="UTF-8"?>
<rdf:RDF
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"><rdf:Description rdf:about="http://www.programmableweb.com/api/co-ops">
	<description xmlns="http://purl.org/dc/elements/1.1/">As part of NOAA, CO-OPS (Center for Operational Oceanographic Products and Services) monitors, assesses, and distributes information relating to tides, currents, water levels, and other coastal oceanographic indicators. CO-OPS provides several SOAP APIs that return information on water levels, tides, currents, harmonics, station metadata, and meteorology for locations along the U.S. coast.</description>
	<endpoint xmlns="http://www.ict-omelette.eu/schema.rdf#" rdf:nodeID="node17812i99fx7"/>
	<clientInstallRequired xmlns="http://www.ict-omelette.eu/schema.rdf#">false</clientInstallRequired>
	<usageFees xmlns="http://www.ict-omelette.eu/schema.rdf#">None</usageFees>
	<authentication xmlns="http://www.ict-omelette.eu/schema.rdf#">None</authentication>
	<api xmlns="http://www.ict-omelette.eu/schema.rdf#" rdf:resource="http://opendap.co-ops.nos.noaa.gov/"/>
	<termsAndConditions xmlns="http://www.ict-omelette.eu/schema.rdf#" rdf:resource="http://tidesandcurrents.noaa.gov/privacy.html"/>
	<tagged xmlns="http://commontag.org/ns#" rdf:resource="http://www.ict-omelette.eu/omr/tags/ocean"/>
	<tagged xmlns="http://commontag.org/ns#" rdf:resource="http://www.ict-omelette.eu/omr/tags/science"/>
	<developerAccountRequired xmlns="http://www.ict-omelette.eu/schema.rdf#">false</developerAccountRequired>
	<label xmlns="http://www.w3.org/2000/01/rdf-schema#">CO-OPS API</label>
	<dataFormat xmlns="http://www.ict-omelette.eu/schema.rdf#">XML</dataFormat>
	<usageLimits xmlns="http://www.ict-omelette.eu/schema.rdf#">None given</usageLimits>
	<categorizedBy xmlns="http://www.ict-omelette.eu/schema.rdf#" rdf:resource="http://www.ict-omelette.eu/omr/categories/science"/>
	<developerKeyRequired xmlns="http://www.ict-omelette.eu/schema.rdf#">false</developerKeyRequired>
	<rdf:type rdf:resource="http://www.ict-omelette.eu/schema.rdf#Service"/>
	<source xmlns="http://purl.org/dc/elements/1.1/" rdf:resource="http://www.programmableweb.com/api/co-ops"/>
	<protocol xmlns="http://www.ict-omelette.eu/schema.rdf#">SOAP</protocol>
	<describedBy xmlns="http://www.ict-omelette.eu/schema.rdf#" rdf:resource="http://opendap.co-ops.nos.noaa.gov/axis/webservices/waterlevelrawsixmin/wsdl/WaterLevelRawSixMin.wsdl"/>
	<provider xmlns="http://www.ict-omelette.eu/schema.rdf#" rdf:resource="http://tidesandcurrents.noaa.gov/"/>
</rdf:Description></rdf:RDF>';

*/


echo $omr->insert($rdf);

echo "Code: ".$omr->errno;


/*
$sparql = 'PREFIX foaf:<http://foaf.org>
PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX limon:<http://www.ict-omelette.eu/schema.rdf#>
PREFIX ctag:<http://commontag.org/ns#>
PREFIX rosm:<http://www.wsmo.org/ns/rosm/0.1#>
PREFIX hrests:<http://www.wsmo.org/ns/hrests#>
PREFIX rdfs:<http://www.w3.org/2000/01/rdf-schema#>
PREFIX omr:<http://www.ict-omelette.eu/omr/categories/>
PREFIX dc:<http://purl.org/dc/elements/1.1/>

SELECT DISTINCT ?o (count( ?a) as ?total ) WHERE { ?a rdf:type ?o . ?a rdf:type limon:Service . OPTIONAL { ?a limon:revised ?revised }. FILTER (!BOUND(?revised)) } GROUP BY ?o LIMIT 100';
*/
echo $omr->query($sparql);

echo "Code: ".$omr->errno;



?>
