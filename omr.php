<?php


class Omr{

	var $sparql_url = "https://vsr-web.informatik.tu-chemnitz.de/omr-write/components/sparql";
	var $post_url;
	var $user = "omr-client-upm";
	var $password = "omr.client.upm.2012";
	var $endpoint;
	var $server;
	
	
	/* For sesame only*/
	var $repository = "pmoncada";
	
	
	var $errno;
	var $error;
	
	function insert($rdf){
		return $this->curl_post($this->post_url, $rdf);	
	
	}
	
	function query($sparql){
		return $this->curl_post($this->sparql_url, $sparql);
	}
		
	function Omr($endpoint) {
		$this->endpoint = $endpoint;
		if($endpoint == "omr"){
			$this->post_url = "https://vsr-web.informatik.tu-chemnitz.de/omr-write/components/";
			$this->server = "https://vsr-web.informatik.tu-chemnitz.de/omr-write/components/sparql";
		}
		else if($endpoint == "sesame"){
			$this->post_url = "http://shannon.gsi.dit.upm.es:18080/openrdf-workbench/repositories/".$this->repository."/add";
			$this->server = "http://shannon.gsi.dit.upm.es:18080/openrdf-workbench/repositories/".$this->repository."/query";
		}
		
	}	
	
	function curl_post($url,$data){


		$ch = curl_init($url);
		
	
		
		// Usuario y pass
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		if($this->endpoint == "omr")
			curl_setopt($ch, CURLOPT_USERPWD, $this->user.":".$this->password);
		
		
		//No verificar SSL		
		if($this->endpoint == "omr")
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		// Nuevo para POST
		curl_setopt ($ch, CURLOPT_POST, 1);
		if($this->endpoint == "omr")
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
		else if($this->endpoint == "sesame"){
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				'baseURI' => "",
				'context' => "",
				"Content-Type" => "application/rdf+xml",
				'source' => "contents",
				'content' => $data
			  )); 		
		}
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);	
		
		if($this->endpoint == "omr")
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
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
