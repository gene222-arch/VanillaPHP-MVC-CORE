<?php

namespace gpa\vanillaphpmvc\Exception;

use Exception;

class NotFoundException extends Exception
{
	// Override messages and codes
	protected $message = 'Page not found';
	protected $code = 404;

}