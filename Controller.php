<?php 

namespace gpa\vanillaphpmvc;

use gpa\vanillaphpmvc\MiddleWares\BaseMiddleware;

class Controller {


	public string $layOut = "main";
	public string $action = '';	// $view
/**
  * @var $middleWares gpa\vanillaphpmvc\MiddleWares\AuthMiddleware
  */
	public array  $middleWares = [];	// Use to store a CLASS

	public function setLayOut( $layOut )
	{
			
		$this->layOut = $layOut;

	}
 

	public function render( $view, $params = [] )
	{

		return Application::$app->view->renderView( $view, $params );

	}

/**
  * @param $middleware === where a Class/Object/Middlewawre is passed as a param
  * @param @var dataType === baseClass of the Middlewares
  */
	public function registerMiddleWare( BaseMiddleware $middleware ) : void
	{

		$this->middleWares[] = $middleware;	// Stores the Class

	}

	public function getMiddleWares() : array 
	{

		return $this->middleWares;	// Returns the Class passed in the param

	}

}