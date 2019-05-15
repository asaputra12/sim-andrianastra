<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cekresimodel extends SB_Model 
{

	public $table = 'tb_detail_pengiriman';
	public $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_detail_pengiriman.* FROM tb_detail_pengiriman   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_detail_pengiriman.id IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
