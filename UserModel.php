<?php 

namespace App\Core\Routing;

use App\Core\Routing\Db\DbModel;

/**
 * User Class Map Data
 */

abstract class UserModel extends DbModel 
{

	abstract public function getDisplayName() : string;

}