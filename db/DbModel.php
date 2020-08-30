<?php 

namespace App\Core\Routing\Db;

use App\Core\Routing\Model;
use App\Core\Routing\Application;

/**
 * Object Relational Mapping === Mapped the Tables/Class` that must be implemented 
 */

abstract class DbModel extends Model {


// Database Properties (Table name, primary key, column names)

	abstract public static function tableName() : string;	

	abstract public static function primaryKey() : string;	

	abstract public function attributes() : array; 


// Create, Read, Update, and Delete Database/Table Data FUNCTIONS

	public function save()	// INSERT
	{

		$tableName  = static::tableName();	
		$attributes = $this->attributes();	
		$attrToString = implode(',', $attributes);	
		$param  = implode(',', array_map(fn($attr) => ":$attr", $attributes)); 

		$statement = self::prepare("INSERT INTO $tableName ($attrToString) VALUES ($param)");

		foreach ( $attributes as $attribute ) {

			$statement->bindValue(":$attribute", $this->{$attribute}); // Array of Props
		
		}

		return $statement->execute() ?? false;

	}


	/**
	  * @param $where === @var type array 
	  * @param is used to be pass as CONDITION ( WHERE firstname = :firstname )
	  */
	public static function findOne( array $where ) // SELECT 
	{

		$tableName = static::tableName(); // refers to the current Object
		$attributes = array_keys( $where );
		$param = implode("AND ", array_map( fn( $attr ) => "$attr = :$attr", $attributes));

		$statement = self::prepare("SELECT * FROM $tableName WHERE $param");

		foreach ($where as $key => $item) {
	
			$statement->bindValue(":$key", $item);

		}

		$statement->execute();

		// Return Instance of a Class(able access on its props/methods) and the Array/Fetched Data from the Database
		return $statement->fetchObject(static::class);	

	}


	public static function prepare( $sqlStatement ) 
	{

		return Application::$app->db->pdo->prepare( $sqlStatement );

	}


}