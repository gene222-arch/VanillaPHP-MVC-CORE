<?php 

namespace gpa\vanillaphpmvc;

class Request {


	public function getPath() : string
	{

		$path = $_SERVER['REQUEST_URI'] ?? '/';
		$position = strpos($path, '?');

		if ( $position === false )
		{

			return $path;

		}

		return substr($path, 0, $position);//Returns uri except from '?' symbol

	}


	public function getRequestMethod() : string
	{

		return strtolower($_SERVER['REQUEST_METHOD']);

	}


	public function isGet() : bool 
	{

		return $this->getRequestMethod() === 'get';

	}

	public function isPost() : bool 
	{

		return $this->getRequestMethod() === 'post';

	}

	/**
  	 * @return $body === an array of data got from a request(post/get)
	 */
	public function getBody() : array 
	{

		$body = [];

		if ( $this->getRequestMethod() === 'get')
		{

			foreach ($_GET as $key => $value) {
				
				$body[ $key ] = filter_input(

									INPUT_GET, 
									$key,
									FILTER_SANITIZE_SPECIAL_CHARS	// convert html/script codes to plain text
								 ); 	
			}
		}

		if ( $this->getRequestMethod() === 'post')
		{

			foreach ($_POST as $key => $value) {
				
				$body[ $key ] = filter_input(

									INPUT_POST, 
									$key,//value
									FILTER_SANITIZE_SPECIAL_CHARS
									
								 ); 	
			}
		}

		return $body;		

	}


}	




