<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengadaanmodel extends SB_Model 
{

	public $table = 'tb_pengadaan';
	public $primaryKey = 'id_pengadaan';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_pengadaan.* FROM tb_pengadaan   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_pengadaan.id_pengadaan IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
