<?php 

namespace gpa\vanillaphpmvc\Form;

use gpa\vanillaphpmvc\Model; 
/**
 * 
 */
abstract class BaseField
{

	public Model $model;		// Model Abstract Class
	public string $attribute;	// Field Name
	public string $type;		// input Type


	public function __construct( Model $model, $attribute)
	{	
	
		$this->model = $model;
		$this->attribute = $attribute;

	}

	public function __toString() // When this class is called it prints this out
	{

		return sprintf(
		'			  
			<div class="form-group">

			    <label>%s</label>
			    %s
			    <div class="invalid-feedback">
			    	%s
			    </div>
			  </div>

		', $this->model->getLabel( $this->attribute ),
		   $this->renderInput(),
		   $this->model->getFirstError($this->attribute)

		);

	}

	abstract public function renderInput() : string;

}