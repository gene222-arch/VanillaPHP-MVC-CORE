<?php 

namespace gpa\vanillaphpmvc;

class Response 
{


	public function sendStatusCode ( int $code ) 
	{
		http_response_code( $code );
	}


	public function redirect ( string $url ) 
	{
		header("Location: $url");
	}

}


