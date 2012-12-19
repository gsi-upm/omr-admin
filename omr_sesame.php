<?php


class Omr{

	var $post_url = "http://shannon.gsi.dit.upm.es:18080/openrdf-workbench/repositories/omr/add";
	
	
	var $errno;
	var $error;
	
	function insert($rdf){
		return $this->curl_post($this->post_url, $rdf);	
	
	}
	
	function query($sparql){
		return $this->curl_post($this->sparql_url, $sparql);
	}
		
	function Omr() {
		
	}	
	
	function curl_post($url,$data){


		$ch = curl_init($url);
		
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
		
		// Nuevo para POST
		curl_setopt ($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	'baseURI' => "",
	'context' => "",
    "Content-Type" => "application/rdf+xml",
    'source' => "contents",
    'content' => $data
  )); 
		
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);	
		
		
		$output = curl_exec($ch);      
		$info = curl_getinfo($ch);
		
		if(curl_errno($ch))
		{
			$this->errno = curl_errno( $ch );
			$this->error = 'Curl error: ' . curl_error($ch);
			return;
		}
		if( $output === '' )
		{
			$this->errno = $info['http_code'];
			$this->error = 'URL returned no data';
			return;
		}
		if( $info['http_code'] != 200) 
		{
			$this->errno = $info['http_code'];
			$this->error = 'Bad response, '.$info['http_code'].': '.$output;
			return;
		}
		curl_close($ch);
		
		return $output;
	
	
	}

}
