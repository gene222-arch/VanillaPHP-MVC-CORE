<?php 

namespace gpa\vanillaphpmvc;

use gpa\vanillaphpmvc\Exception\NotFoundException;


class Router {


	public Request $request;
	public Response $response;
	public array $routes = [];


	public function __construct( Request $request, Response $response )
	{

		$this->request  = $request;
		$this->response = $response;

	}


	public function getMethod( $path, $callback ) : void 
	{

		$this->routes[ 'get' ][ $path ] = $callback;	//Sets the array path

	}


	public function postMethod( $path, $callback ) : void 
	{

		$this->routes[ 'post' ][ $path ] = $callback;	//Sets the array path

	}	


//Find the path 
	public function resolve() 
	{

		$path = $this->request->getPath();
		$method = $this->request->getRequestMethod();

		$callback = $this->routes[ $method ][ $path ] ?? false;


		if ($callback === false) {

			throw new NotFoundException();
		}

		if (is_string($callback)) {

			return Application::$app->view->renderView($callback);
		}
/**
 * gpa\vanillaphpmvc\Controller
 */
		if ( is_array($callback) ) {

			$controller = new $callback[0]();

			// string 'AuthController' = new AuthController();
			$callback[0] = $controller;	

			// Application::$app->controller = new AuthController()
			Application::$app->controller = $controller;	

			// new AuthController->'profile'
			$controller->action = $callback[1]; // registerMiddleware instantiated	
	
			foreach ($controller->getMiddleWares() as $middleware) {

				$middleware->execute();	//	=== AuthMiddleware::execute()
			}

		}	

		return call_user_func( $callback, $this->request, $this->response ); 

	}


}







/**

Class Request focuses on;

(1): Getting the PATH
(2): Getting Request Method; either get/post


Class Router focuses on; Redirects you to a View or Page

(1): Returning the path 
(2): Checking if the path exists
(3): Showing the content of the path

_________________________________________________________________________________

_________________________________________________________________________________

How this works?

(1): First, We INSERT an existing PATH with the getMethod

____function getMethod()

	$routes['get']['/homepage'] = home
	$routes['get']['/contactpage'] = contact

	$routes = [
		
		'get' => [
			
			'/homepage' => home.php,
			'/contactpage' => contact.php
	
		]

		'post' => [

		
		]
	
	]

(2): Second, We get the current URI and find if it exists within our routes


____function resolve()
	
	$path --> URI 
	$method --> GET/POST

	$callback = $this->routes['Get']['/contactpage'] ?? false;
		
----> Traversing through the INSERTED ROUTES	

RUN:	
		$routes = [
			
			'get' => [
				
				'/homepage' => home,
				'/contactpage' => contact
		
			]

			
		];


If found:
	-----> $callback = contact

If not found
	-----> $callback = false 

STATUS: (Found)

		
	IF callback is false
		--> Path not found
 	IF callback is found
 		--> show View of callback/ contact.php
 	ELSE  
 		--> return the callback



____________________________________________________________________________________________________________________________________________________________________


Process: 

(1): get/postMethod()
(2): resolve() 
	(2.1) renderView()
		(2.1.1) readOnlyView()
		(2.1.2) layOutContent()


		
*/


