<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produkmodel extends SB_Model 
{

	public $table = 'tb_produk';
	public $primaryKey = 'id_produk';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_produk.* FROM tb_produk   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_produk.id_produk IS NOT NULL    ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
