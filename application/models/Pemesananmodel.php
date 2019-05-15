<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pemesananmodel extends SB_Model 
{

	public $table = 'tb_pemesanan_produk';
	public $primaryKey = 'id_pemesanan';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_pemesanan_produk.* FROM tb_pemesanan_produk   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_pemesanan_produk.id_pemesanan IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
