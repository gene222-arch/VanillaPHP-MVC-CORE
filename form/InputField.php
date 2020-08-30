<?php 

namespace gpa\vanillaphpmvc\Form;

use gpa\vanillaphpmvc\Model;

class InputField extends BaseField
{

	public const TYPE_TEXT = 'text';
	public const TYPE_PASSWORD = 'password';
	public const TYPE_NUMBER = 'number';
	public const TYPE_EMAIL = 'email';

	public string $type;		// input Type

/**
  * @param $model gpa\vanillaphpmvc\Model 
  * @param $string $attribute
  */	
	public Model $model;		
	public string $attribute;	

	public function __construct( Model $model, string $attribute)
	{	

		$this->type = self::TYPE_TEXT;		
		parent::__construct($model, $attribute);

	}


	public function renderInput() : string
	{

		return sprintf('<input type="%s" name="%s" value="%s" class="form-control%s">', 
			$this->type,
			$this->attribute,
			$this->model->{$this->attribute},
			$this->model->hasError($this->attribute) ? ' is-invalid' : ''

		);
	
	}


	public function passwordField() : InputField
	{

		$this->type = self::TYPE_PASSWORD;
		return $this; //Returns the current Object after updating the type property`s value

	}

	public function emailField() : InputField 
	{
		
		$this->type = self::TYPE_EMAIL;
		return $this;
		
	}	




}