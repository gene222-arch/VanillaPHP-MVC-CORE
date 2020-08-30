<?php

namespace App\Core\Exception;

use Exception;

class ForbiddenException extends Exception
{
	// Override messages and codes
	protected $message = 'You don`t have permission to access this page';
	protected $code = 403;

}