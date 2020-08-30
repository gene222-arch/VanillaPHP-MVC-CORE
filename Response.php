<?php 

namespace App\Core\Routing;

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


