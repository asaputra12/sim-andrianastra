<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kategoriprodukmodel extends SB_Model 
{

	public $table = 'tb_produk_kategori';
	public $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_produk_kategori.* FROM tb_produk_kategori   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_produk_kategori.id IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
