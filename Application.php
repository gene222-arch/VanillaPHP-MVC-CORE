<?php 

namespace gpa\vanillaphpmvc;

use gpa\vanillaphpmvc\Controller;
use gpa\vanillaphpmvc\Db\Database;
use Exception;

class Application {


	public string $layOut = 'main';
	public static string $ROOT_DIRECTORY;
	public static Application $app;
	public Request $request;
	public Router  $router;
	public ?Controller $controller = null;
	public Response $response;
	public Database $db;
	public Session  $session;
	public string $userClass;
	public ?UserModel $user = null;	// User Object + fetchAll
	public View $view;


	public function __construct( $rootPath, array $config ) 
	{

		self::$ROOT_DIRECTORY = $rootPath;
		self::$app = $this;
		$this->request  = new Request;
		$this->response = new Response;
		$this->router   = new Router( $this->request, $this->response );
		$this->session  = new Session;
		$this->db = new Database( $config['db'] );
		$this->view = new View;

		// Get User data again via its ID after EMAIL in CONSTRUCTOR 
		// Updates data from database every refresh/redirecting of the browser
		$this->userClass = $config['userClass']; 
		$primaryValue = $this->session->get('user'); // set user again after login === userId
		
		if ( $primaryValue ) {

			$primaryKey = $this->userClass::primaryKey(); // 'id'

		// Reloads all the data in User Class, so you can access its properties' values
			$this->user = $this->userClass::findOne([ $primaryKey => $primaryValue ]);
		
		}

	}


	public function run() 
	{

		try {

			echo $this->router->resolve(); //Sets the View

		} catch (Exception $err) {

			$this->response->sendStatusCode($err->getCode());

			echo $this->view->renderView('_error', [
				'exception' => $err
			]);
		}

	}

	/**
 	  * @param $user instance of gpa\vanillaphpmvc\UserModel
 	  * Purpose is to set the SESSION and App::user property
	  */ 
	public function login( UserModel $user ) 
	{


		$this->user   = $user;	// fetchObject(User Class) // setting the User
		$primaryKey   = $user::primaryKey();	// User Class method === 'id'
		$primaryValue = $user->{$primaryKey};	// $user->id === Database	
		$this->session->set('user', $primaryValue);

		return true;

	}


	public function logout()
	{

		// $this->user = null;	// Set App property user to null
		$this->session->remove('user');	// Unset session @var User to 

	}


	public static function isGuest()
	{

		return !self::$app->user; // user default value is FALSE/NULL

	}

}




/**

__Class Application__

(1): Instantiation of other Classes

__Why__?

	-> So we can access them in any part of our MVC Framework 

*/
