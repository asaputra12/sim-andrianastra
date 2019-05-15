<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Returmodel extends SB_Model 
{

	public $table = 'tb_retur';
	public $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_retur.* FROM tb_retur   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_retur.id IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
