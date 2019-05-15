<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bommodel extends SB_Model 
{

	public $table = 'tb_produk';
	public $primaryKey = 'id_produk';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "  SELECT * FROM `tb_produk` ";
	}
	public static function queryWhere(  ){
		
		return "    ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
