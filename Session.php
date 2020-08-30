<?php 
namespace App\Core\Routing;


class Session 
{

	public const FLASH_KEY = 'flash_messages';

	public function __construct()
	{
		session_start();

		$flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

		foreach ( $flashMessages as $key => &$flashMessage ) {

			// $key and &$flashMessage will both share the same fate
			$flashMessage['remove'] = true;
		}

		$_SESSION[self::FLASH_KEY] = $flashMessages;

	}


	public function __destruct()
	{
		// session_start();

		$flashMessages = $_SESSION[self::FLASH_KEY] ?? [];	// === ['success'] =>['remove', 'value'];

		// Iterate over mark to be removed
		foreach ( $flashMessages as $key => &$flashMessage ) {

			if ( $flashMessage['remove'] ) {

				unset($flashMessages[$key]);
			}
		}

		$_SESSION[self::FLASH_KEY] = $flashMessages;		

	}


// Setters

	public function setFlash( $key, $message )
	{

		$_SESSION[self::FLASH_KEY][$key] = [

			'remove' => false,
			'value'	 => $message
		];

	}

	public function set( $key, $value) 
	{

		$_SESSION[$key] = $value;

	}


// Getters

	public function get( $key )
	{

		return $_SESSION[$key] ?? false;

	}

	public function getFlash( $key ) 
	{

		return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;

	}

// Delete
	public function remove( $user )
	{

	 	unset($_SESSION[$user]);	

	}



}

