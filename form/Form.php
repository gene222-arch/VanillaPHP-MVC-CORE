<?php 

namespace gpa\vanillaphpmvc\Form;

use gpa\vanillaphpmvc\Model;



/**
 *	Form Class must contain a method that gives it a privilege to access the Field Class
 */


class Form 
{


	public static function begin( $action, $method ) : Form
	{

		echo sprintf(' <form action="%s" method="%s"> ', $action, $method);
		return new Form(); 

	}


	public static function end() : void
	{

		echo '</form>';

	}


	public function field( Model $model, $attribute) : InputField
	{

		return new InputField( $model, $attribute );

	}


}