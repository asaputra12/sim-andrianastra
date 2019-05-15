<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengirimanmodel extends SB_Model 
{

	public $table = 'tb_pengiriman';
	public $primaryKey = 'id_pengiriman';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_pengiriman.* FROM tb_pengiriman   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_pengiriman.id_pengiriman IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
