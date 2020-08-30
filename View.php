<?php 

namespace App\Core\Routing;

class View 
{

	public string $title = '';

	public function renderView( $view, $params = [] )
	{

		$viewOnly = $this->renderOnlyView($view, $params);	
		$layOut   = $this->layOutContent();

		return str_replace( '{{content}}', $viewOnly, $layOut);

	} 


	protected function layOutContent()
	{

		$layOut = Application::$app->layOut; // Application Class

		if ( Application::$app->controller ) {

			Application::$app->controller->layOut;	// Controller sub Classes
		}

		ob_start();
		
		require_once ( Application::$ROOT_DIRECTORY . "/src/Views/layouts/$layOut.php" );	
		return ob_get_clean();

	}



	protected function renderOnlyView( $view, $params = [] )
	{

		foreach ($params as $key => $value) {
	
			$$key = $value;
		}

		ob_start();

		require_once ( Application::$ROOT_DIRECTORY . "/src/Views/$view.php" );

		return ob_get_clean();	

	}


}

