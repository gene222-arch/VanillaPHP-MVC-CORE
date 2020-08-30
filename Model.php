<?php 
/**
  *	@Class Model handles over the validation of all data to be passed in the Actual Model
  *	@Class Model we define the rules of VALIDATION via Properties
  * @Class Model we load all kinds of data passed by the user
  */

namespace gpa\vanillaphpmvc;

abstract class Model
{

	public const RULE_REQUIRED = 'required';
	public const RULE_EMAIL = 'email';
	public const RULE_MIN = 'min';
	public const RULE_MAX = 'max';
	public const RULE_MATCH = 'match';
	public const RULE_UNIQUE = 'unique';
	public const RULE_ILLCONTENT = 'illcontent';
 	public array $errors = [];


 /**
  * @param data === POST data from Class Request 
  * General method of Model that must load all kinds of data not only specific data
  * Append data from POST/GET in Class Request in the RegisterModel properties
  */

	public function loadData( $data )			
	{

		foreach ( $data as $key => $value ) {			
			
			if ( property_exists($this, $key) ) {	// property_exists ($class, string)

				$this->{$key} = $value;	// Passing the value in each property	

			}
		}

	}


	public abstract function rules() : array;

	public function validate() : bool // Validating Register Model
	{

	 	foreach ( $this->rules() as $attribute => $rules ) {
			
			$value = $this->{$attribute};	  // Get the value of property $("firstname") 

			foreach ( $rules as $rule ) { 	  // Iterates over the values && arrays 

				$ruleName = $rule;			  // Iterate 1st index 

				if ( is_array($ruleName) ) { 

					$ruleName = $ruleName[0];	// Iterates over arrays and get 1st index 

				}

# Iterates non - Array values									
				if ( $ruleName === self::RULE_REQUIRED && empty($value) ) {	

					$this->addErrorForRule($attribute, self::RULE_REQUIRED);
				}

				if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){

					$this->addErrorForRule($attribute, self::RULE_EMAIL);
				}

# Iterates over Array values
				if ( $ruleName === self::RULE_MIN && ( strlen($value) < $rule['min'] ) ) {

					$this->addErrorForRule($attribute, self::RULE_MIN, $rule );					
				}

				if ( $ruleName === self::RULE_MAX && ( strlen($value) > $rule['max'] ) ) {

					$this->addErrorForRule($attribute, self::RULE_MAX, $rule );				
				}

				if ( $ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']} ) {
					
					$rule['match'] = $this->getLabel($rule['match']);
					$this->addErrorForRule($attribute, self::RULE_MATCH, $rule );		
				}

				if ( $ruleName === self::RULE_UNIQUE ) {

					$className = $rule['class'];
					$uniqueAttribute = $rule['attribute'] ?? $attribute;
					$tableName = $className::tableName();

					$statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttribute = :attr");

					$statement->bindValue(":attr", $value);
					$statement->execute();
					$record = $statement->fetchObject();

					if ( $record ) {

						$this->addErrorForRule($attribute, self::RULE_UNIQUE, 
							['field' => $this->getLabel( $attribute )] );
					}
				}

				if ( $ruleName === self::RULE_ILLCONTENT && preg_match_all("/\b(puta)|(gago)\b/i", $value))
				{

					$this->addErrorForRule($attribute, self::RULE_ILLCONTENT, ["field" => $this->getLabel( $attribute )]);

				}

			}

		}

		return empty( $this->errors );	// is error/s empty

	}


 /**
  * @param @string attribute === RegisterModel properties
  * @param @string rule === RegisterModel rules() method contains array
  * @param @array params === rules() method Arrays ---> [RULE_MIN, 'min' => 8]
  */
	private function addErrorForRule( string $attribute, string $rule, $params = [] ) : void
	{

		$message = $this->errorMessages()[ $rule ] ?? '';	//if rule exist

		foreach ( $params as $key => $value ) { // Array ( 'min', ['match/min'] => 8 )
		
			$message = str_replace("{{$key}}", $value, $message);

		}

		$this->errors[ $attribute ][] = $message;

	}


	public function addError( string $attribute, string $message ) 
	{

		$this->errors[$attribute][] = $message;

	}



	public function errorMessages() : array 
	{

		return [

			self::RULE_REQUIRED => 'This field is required',
			self::RULE_EMAIL	=> 'This field must be a valid Email',
			self::RULE_MIN		=> 'Min length of this field must be {min}',
			self::RULE_MAX		=> 'Max length of this field must be {max}',
			self::RULE_MATCH	=> 'This field must be the same as {match}',
			self::RULE_UNIQUE   => 'Record with this {field} already exists',
			self::RULE_ILLCONTENT => 'Your {field} contains vulgar words'

		];

	}


	 /**
	  * @param attribute === RegisterModel properties
	  * @return property/attr error value ----> [firstname => 'Must be a character']
	  */
	public function hasError( $attribute ) : bool
	{

		return !empty($this->errors[ $attribute ]);

	}


	 /**
	  * @param attribute === RegisterModel properties
	  * @return a string from an array Of Errors
	  */
	public function getFirstError( $attribute ) : string
	{

		return $this->errors[ $attribute ][0] ?? false;

	}



	public function setLabels() : array 
	{
		return [];
	}


	public function getLabel( $attribute )
	{
		return $this->setLabels()[$attribute] ?? $attribute;
	}



	

	
	
}