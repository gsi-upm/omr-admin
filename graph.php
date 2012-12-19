<html>
	<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" id="sgvzlr_script" src="http://sgvizler.googlecode.com/svn/release/0.5/sgvizler.js"></script>
<script type="text/javascript">

   sgvizler.option.query = {

   };
   sgvizler.option.namespace['foaf']  = 'http://foaf.org';
   sgvizler.option.namespace['limon']  = 'http://www.ict-omelette.eu/schema.rdf#';
   sgvizler.option.namespace['ctag']  = 'http://commontag.org/ns#';
   sgvizler.option.namespace['rosm']  = 'http://www.wsmo.org/ns/rosm/0.1#';   
   sgvizler.option.namespace['hrests']  = 'http://www.wsmo.org/ns/hrests#';   
   sgvizler.option.namespace['omr']  = 'http://www.ict-omelette.eu/omr/categories/';   
   sgvizler.option.namespace['dc']  = 'http://purl.org/dc/elements/1.1/';   


   sgvizler.option.chart = { 

   };


   $(document).ready(sgvizler.go());
</script>
	</head>
	<body>
	       <div id="sgvzl_example2"
      data-sgvizler-endpoint="http://shannon.gsi.dit.upm.es/openrdf-workbench/repositories/pmoncada/query"
      data-sgvizler-query="<?php echo base64_decode($_GET['query']); ?>"
      data-sgvizler-chart="gPieChart"
      data-sgvizler-endpoint_output="xml" 
      style="width:800px; height:400px;"></div
	</body>
</html>
