<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bahanbakumodel extends SB_Model 
{

	public $table = 'tb_bahan_baku';
	public $primaryKey = 'id_bahan_baku';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT tb_bahan_baku.* FROM tb_bahan_baku   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_bahan_baku.id_bahan_baku IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
