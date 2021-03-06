<?php

namespace gpa\vanillaphpmvc\MiddleWares;

use gpa\vanillaphpmvc\Application;
use gpa\vanillaphpmvc\Exception\ForbiddenException;


class AuthMiddleware extends BaseMiddleware
{

	public array $actions = [];

	/**
	 * Mostly used to authenticate Controller Actions From User Request to Controller
	 */

	public function __construct( array $actions = [] ) // set in AuthController
	{
		$this->actions = $actions;
	}


	public function execute() // BaseMiddleWare
	{

		if ( Application::isGuest() ) {

			if ( empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {

				throw new ForbiddenException();


			}

		}

	}

}