<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kendaraanmodel extends SB_Model 
{

	public $table = 'tb_kendaraan';
	public $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_kendaraan.* FROM tb_kendaraan   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_kendaraan.id IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
