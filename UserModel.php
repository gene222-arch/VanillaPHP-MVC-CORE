<?php 

namespace gpa\vanillaphpmvc;

use gpa\vanillaphpmvc\Db\DbModel;

/**
 * User Class Map Data
 */

abstract class UserModel extends DbModel 
{

	abstract public function getDisplayName() : string;

}