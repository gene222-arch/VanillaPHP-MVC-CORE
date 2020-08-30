<?php

namespace App\Core\Routing\Db;

use \PDO;

class Database 
{
	
	public PDO $pdo;

	function __construct( array $config )	//  === ['db']['dsn']
	{
		
		$dsn  = $config['dsn'] ?? '';
		$user = $config['user'] ?? '';
		$password = $config['password'] ?? '';	

		$this->pdo = new PDO( $dsn, $user, $password );
		$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			
	}

	// Purpose is Creating the Database/Tables 
	public function applyMigrations() : void
	{

		// Start Creating Table `migrations` 
		$this->createMigrationsTable();	

		// Getting migration column data from Database
		$appliedMigrations = $this->getAppliedMigrations(); 

		// Return an array of files from a Directory
		$files = scandir( Application::$ROOT_DIRECTORY . '/src/Migrations' );	

		// Returns the difference between multiple arrays
		$toApplyMigrations = array_diff( $files, $appliedMigrations );	

		$newMigrations = [];

		// Processing Creation of Table
		foreach ( $toApplyMigrations as $migration ) {	
														
			if ( $migration === '.' || $migration === '..' ) {
					
				continue;
			}

			//	Start Creating a Table If a file have not yet been migrated/imported
			require_once Application::$ROOT_DIRECTORY . '/src/Migrations/' . $migration; // Get the migration file

			// get the migration FILE name 
			$className = pathinfo( $migration, PATHINFO_FILENAME );	

			// instantiate the Class 		
			$instance = new $className();	

			$this->log("Applying Migration $migration");
			$instance->up();
			$this->log("Migration Applied $migration");

		 	$newMigrations[] = $migration;

		}

		if ( !empty( $newMigrations )) :

			// Returns an array of files to be Migrated
			$this->saveMigrations( $newMigrations );

		else :

			$this->log("All Migrations Are Applied");

		endif;

	}


	public function createMigrationsTable() : void
	{

		$this->pdo->exec(
			"CREATE TABLE IF NOT EXISTS migrations (
				id INT(11) AUTO_INCREMENT PRIMARY KEY,
				migration VARCHAR(255),
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP )
				ENGINE=INNODB;"
			);

	}

	/**
	  * @return an array of migrated files/tables in the Database
	  */ 
	public function getAppliedMigrations() : array
	{

		$statement = $this->prepare( "SELECT migration FROM migrations" );
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_COLUMN);

	}

	/**
	  * @param $migrations === array of filename from migrations folder to be migrated
	  */
	public function saveMigrations( array $migrations ) : void
	{

		//1. ['m0001, m0002'] => ['('m_0001')', '('m_0002')'] => 
		//2. $str = ('m_0001'), ('m_0002')
		$str = implode(",", array_map( fn($m)=> "('$m')", $migrations ));
		$statement = $this->prepare("INSERT INTO `migrations`(migration) VALUES $str"); 
		$statement->execute();

	}

	public function prepare( $sqlStatement ) 
	{

		return $this->pdo->prepare( $sqlStatement );

	}

	protected function log( $message )
	{
		
		echo "[" . date('Y-m-d H:i:s a') . "] - " . $message . PHP_EOL;
		
	}

	

}



