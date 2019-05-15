<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Konsumenmodel extends SB_Model 
{

	public $table = 'tb_users';
	public $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_users.* FROM tb_users   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_users.group_id = 0   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
