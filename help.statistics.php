   
      
       <div id="sgvzl_example2"
      data-sgvizler-endpoint="http://apps.gsi.dit.upm.es/openrdf-workbench/repositories/pmoncada/query"
      data-sgvizler-query="
      	PREFIX foaf:<http://foaf.org>
	PREFIX limon:<http://www.ict-omelette.eu/schema.rdf#>
	PREFIX ctag:<http://commontag.org/ns#>
	PREFIX rosm:<http://www.wsmo.org/ns/rosm/0.1#>
	PREFIX hrests:<http://www.wsmo.org/ns/hrests#>
	PREFIX omr:<http://www.ict-omelette.eu/omr/categories/>
	PREFIX dc:<http://purl.org/dc/elements/1.1/>
     	SELECT DISTINCT ?o (count(distinct ?a) as ?total ) WHERE { ?a rdf:type ?o . OPTIONAL { ?a dc:description ?desc .} OPTIONAL { ?a limon:revised ?revised }. FILTER (!BOUND(?revised)) } GROUP BY ?o ORDER BY DESC(?total) LIMIT 100"
      data-sgvizler-chart="gPieChart"
      data-sgvizler-endpoint_output="xml" 
      style="width:800px; height:400px;"></div
      
      
