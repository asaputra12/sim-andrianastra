<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Peramalanmodel extends SB_Model 
{

	public $table = 'captcha';
	public $primaryKey = '';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "  SELECT SUM(dp_produk.qty) Permintaan, produk.nama_produk Produk, DATE_FORMAT(dp_produk.tgl_pemesanan,'%M %Y') Tahun FROM tb_detail_pemesan_produk dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk GROUP BY dp_produk.id_produk, dp_produk.tgl_pemesanan Order By dp_produk.tgl_pemesanan ASC  ";
	}
	public static function queryWhere(  ){
		
		return "    ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
