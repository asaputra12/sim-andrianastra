<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pemesanan extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'pemesanan';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('pemesananmodel');
		$this->model = $this->pemesananmodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'pemesanan',
		));
		
		if(!$this->session->userdata('logged_in')) redirect('user/login',301);
		
	}
	
	function index() 
	{
		if($this->access['is_view'] ==0)
		{ 
			SiteHelpers::alert('error','Your are not allowed to access the page');
			redirect('dashboard',301);
		}	
		  
		// Filter sort and order for query 
		$sort = (!is_null($this->input->get('sort', true)) ? $this->input->get('sort', true) : 'id_pemesanan'); 
		$order = (!is_null($this->input->get('order', true)) ? $this->input->get('order', true) : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($this->input->get('search', true)) ? $this->buildSearch() : '');
		// End Filter Search for query 
		
		$page = max(1, (int) $this->input->get('page', 1));
		$params = array(
			'page'		=> $page ,
			'limit'		=> ($this->input->get('rows', true) !='' ? filter_var($this->input->get('rows', true),FILTER_VALIDATE_INT) : $this->per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query 
		$results = $this->model->getRows( $params );		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		#$pagination = Paginator::make($results['rows'], $results['total'],$params['limit']);		
		$this->data['rowData']		= $results['rows'];
		// Build Pagination
		
		$pagination = $this->paginator( array(
			'total_rows' => $results['total'] ,
			'per_page'	 => $params['limit']
		));
		$this->data['pagination']	= $pagination;
		// Row grid Number 
		$this->data['i']			= ($page * $params['limit'])- $params['limit']; 
		// Grid Configuration 
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= SiteHelpers::viewColSpan($this->info['config']['grid']);		
		// Group users permission
		$this->data['access']		= $this->access;
		// Render into template
		
		$this->data['content'] = $this->load->view('pemesanan/index',$this->data, true );
		
    	$this->load->view('layouts/main', $this->data );
    
	  
	}
	
	function show( $id = null) 
	{
		if($this->access['is_detail'] ==0)
		{ 
			SiteHelpers::alert('error','Your are not allowed to access the page');
			redirect('dashboard',301);
	  	}		

		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_pemesanan_produk'); 
		}
		
		$this->data['id'] = $id;
		$this->data['content'] =  $this->load->view('pemesanan/view', $this->data ,true);	  
		$this->load->view('layouts/main',$this->data);
	}
  
	function add( $id = null ) 
	{
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);

		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	
			$row = $this->model->getRow( $id );
		$tb_produk = $this->db->get('tb_produk')->result_array();
		$this->data['tb_produk'] =  $tb_produk;

		$tb_konsumen =$this->db->query("SELECT * FROM tb_users WHERE group_id = 0")->result_array();
		$this->data['tb_konsumen'] =  $tb_konsumen;

		$max =$this->db->query("SELECT * FROM tb_pemesanan_produk WHERE id_pemesanan = (SELECT MAX(id_pemesanan) FROM tb_pemesanan_produk)")->result_array();
		$rowmax =$this->db->query("SELECT * FROM tb_pemesanan_produk WHERE id_pemesanan = (SELECT MAX(id_pemesanan) FROM tb_pemesanan_produk)")->row();
		
		foreach($max as $max){
			$maxid=$max['id_pemesanan']+1;
		}
		if($rowmax == NULL){
			$maxid='0001';
		}	
		
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_pemesanan_produk'); 
		}
	

		if($row)
		{
			$this->data['row'] =  $row;
			$posts = $this->db->query("SELECT * FROM `tb_detail_pemesan_produk` WHERE id_pemesanan='$id'")->result_array();
			$posts1 = $this->db->query("SELECT SUM(produk.lama_produksi) as lama FROM `tb_detail_pemesan_produk` dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk  WHERE dp_produk.id_pemesanan='$id'")->result_array();
			$this->data['posts'] =  $posts;
			$this->data['lamaProduksi'] =  $posts1;

			
			// var_dump($posts);
			// die();
		} else {
			
			$a =  $this->model->getColumnTable('tb_pemesanan_produk');
			$b=  array(
				'id_pemesanan' => $maxid,
				'id_produk' => '',
				'id_user' => '',
				'tgl_pemesanan' => '',
				'bayar_dp' => '',
				'status_pemesanan' => '',
				'total_bayar' => '',
				'suffix' => '',
				'nama_pelanggan' => '',
				'tgl_selesai' => '',
				'tipePengiriman' => '',
			);         
			// var_dump($b); 
			// echo('<br>'); 
			// var_dump($a);   
			// die();
			$this->data['row'] = $b;
			$posts = $this->db->query("SELECT * FROM `tb_detail_pemesan_produk` WHERE id_pemesanan='$maxid'")->result_array();
			$this->data['posts'] =  $posts;
			// var_dump($posts);
			// die();
			
		
		}
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('pemesanan/form',$this->data, true );		
	  	$this->load->view('layouts/main', $this->data );
	
	}
	
	function save() {
		
		$rules = $this->validateForm();

		$this->form_validation->set_rules( $rules );

		if( !empty($rules) && $this->form_validation->run()){
			$data =	array(
					'message'	=> 'Ops , The following errors occurred',
					'errors'	=> validation_errors('<li>', '</li>')
					);			
			$this->displayError($data);
		}
		

			$data = $this->validatePost();
			$id = $this->input->post('id_pemesanan',true);
			$rows =$this->db->query("SELECT * FROM `tb_pemesanan_produk` WHERE id_pemesanan='$id'")->row();
			if(count($rows)>=1){
				$this->db->update('tb_pemesanan_produk', $data, 'id_pemesanan = '."$id");
			}else{
				$this->db->insert('tb_pemesanan_produk',$data);
			}
			// Input logs
			if( $this->input->get( 'id_pemesanan' , true ) =='')
			{
				$this->inputLogs("New Entry row with ID : $ID  , Has Been Save Successfull");
			} else {
				$this->inputLogs(" ID : $ID  , Has Been Changed Successfull");
			}
			// Redirect after save	
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
			if($this->input->post('apply'))
			{
				redirect( 'pemesanan/add/'.$ID,301);
			} else {
				redirect( 'pemesanan',301);
			}				
	}

	function destroy()
	{
		if($this->access['is_remove'] ==0)
		{ 
			SiteHelpers::alert('error','Your are not allowed to access the page');
			redirect('dashboard',301);
	  	}
			
		$this->model->destroy($this->input->post( 'id' , true ));
		$this->inputLogs("ID : ".implode(",",$this->input->post( 'id' , true ))."  , Has Been Removed Successfull");
			SiteHelpers::alert('success',"ID : ".implode(",",$this->input->post( 'id' , true ))."  , Has Been Removed Successfull");
		Redirect('pemesanan',301); 
	}

	function delete()
	{
		$id_detail_pemesan_produk=$_POST['id'];
		if($_POST['id'] == "")
		{
			
		}
		else
		{

			// $getJumlah =$this->db->query("SELECT * FROM tb_detail_pemesan_produk WHERE id_detail_pemesan_produk = '$id_detail_pemesan_produk'")->result_array();
			// foreach($getJumlah as $getJumlah){
			// 	$jumlah=$getJumlah['qty'];
			// 	$id_produk=$getJumlah['id_produk'];
			// }

			// $getSisa =$this->db->query("SELECT * FROM tb_produk WHERE id_produk = '$id_produk'")->result_array();
			
			// foreach($getSisa as $getSisa){
			// 	$sisaLast=$getSisa['sisa']-$jumlah;
			// }

			// $this->db->query("UPDATE `tb_produk` SET `sisa` = '$sisaLast' WHERE id_produk = '$id_produk'");
			$this->db->delete('tb_detail_pemesan_produk', 'id_detail_pemesan_produk = '.$_POST['id']);
		}
	
	}

	function tambah()
	{
		
		if(!empty($_POST["id"])) {
			$title = $_POST["title"];
			$id = $_POST["id"];
			$description = $_POST["description"];
			$tgl = $_POST["tgl"];
		
			$max =$this->db->query("SELECT * FROM tb_detail_pemesan_produk WHERE id_detail_pemesan_produk = (SELECT MAX(id_detail_pemesan_produk) FROM tb_detail_pemesan_produk)")->result_array();
			foreach($max as $max){
				$maxid=$max['id_detail_pemesan_produk'];
			}
			
			//Produk Lupa
			// $caaa = $this->db->query("SELECT * FROM `tb_detail_pemesan_produk` dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk  WHERE dp_produk.id_detail_pemesan_produk='$maxid'")->result_array();
			// $lama_prodok=$caaa[0]["lama_produksi"];

			//Produk Lupa Diperbaiki
			$caaa = $this->db->query("SELECT * FROM tb_produk produk WHERE produk.id_produk='$title'")->result_array();
			$lama_prodok=$caaa[0]["lama_produksi"];

			$maxTglProduk =$this->db->query("SELECT * FROM tb_detail_pemesan_produk WHERE tgl_selesai=(SELECT MAX(tgl_selesai) FROM tb_detail_pemesan_produk WHERE id_produk = '$title')")->result_array();
			foreach($maxTglProduk as $max){
				$tgl_selesaiProduk=$max['tgl_selesai'];
			}

			if(!$maxTglProduk){
				$tgl_selesaiProduk=$tgl;
			}	
			//kebutuhan Bahan Baku Kain
			$tb_bom = $this->db->query("SELECT nama_bahan_baku,sisa,jumlah_kebutuhan,SUM(sisa-jumlah_kebutuhan) as hasil FROM `tb_bom` bom INNER JOIN tb_bahan_baku bb ON bom.id_bahan_baku = bb.id_bahan_baku  WHERE bom.id_produk='$title' AND bb.nama_bahan_baku = 'Kain'")->result_array();
			$kebutuhanBBKain = $tb_bom[0]["hasil"];

			if (strtotime($tgl_selesaiProduk) <= strtotime($tgl)) {
				$cariLama = $this->db->query("SELECT ((SUM(qty)+$description)/$lama_prodok) as hasil FROM `tb_detail_pemesan_produk` dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk  WHERE dp_produk.id_produk='$title' AND dp_produk.tgl_selesai='$tgl'")->result_array();
				$lama=$cariLama[0]["hasil"];

				if ($cariLama[0]["hasil"]== null) {
					$lama=1;
				}

				if ($kebutuhanBBKain < 0) {
					$butuhBahanBaku=2;
					if ($butuhBahanBaku<=$lama) {
						$date=date('Y-m-d', strtotime($tgl. ' + '.ceil($lama+1).' days'));
					} else {
						$date=date('Y-m-d', strtotime($tgl. ' + '.ceil($butuhBahanBaku).' days'));
					}
					
				}else{
					$date=date('Y-m-d', strtotime($tgl_selesaiProduk. ' + '.ceil($lama+1).' days'));
				}

			} else {
				$cariLama = $this->db->query("SELECT ((SUM(qty)+$description)/$lama_prodok) as hasil FROM `tb_detail_pemesan_produk` dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk  WHERE dp_produk.id_produk='$title' AND dp_produk.tgl_selesai='$tgl_selesaiProduk'")->result_array();
				$lama=$cariLama[0]["hasil"];

				if ($cariLama[0]["hasil"]== null) {
					$lama=1;
				}
				if ($kebutuhanBBKain < 0) {
					$butuhBahanBaku=2;
					if ($butuhBahanBaku<=$lama) {
						// $date=date('Y-m-d', strtotime($tgl. ' + '.ceil($lama).' days'));
						$date=date('Y-m-d', strtotime($tgl_selesaiProduk. ' + '.ceil($lama+1).' days'));
					} else {
						$date=date('Y-m-d', strtotime($tgl_selesaiProduk. ' + '.ceil($butuhBahanBaku).' days'));
						// $date=date('Y-m-d', strtotime($tgl. ' + '.ceil($butuhBahanBaku).' days'));
					}
					
				} else{
					$date=date('Y-m-d', strtotime($tgl_selesaiProduk. ' + '.ceil($lama+1).' days'));
				}
				
			}
			

			$this->db->query("INSERT INTO `tb_detail_pemesan_produk` (`id_detail_pemesan_produk`, `id_pemesanan`, `id_produk`, `qty`,`tgl_pemesanan`,`tgl_selesai`) VALUES (NULL, '$id', '$title', '$description', '$tgl','$date')");
			$maxx =$this->db->query("SELECT * FROM tb_detail_pemesan_produk WHERE id_detail_pemesan_produk = (SELECT MAX(id_detail_pemesan_produk) FROM tb_detail_pemesan_produk)")->result_array();
			foreach($maxx as $max){
				$maxxid=$max['id_detail_pemesan_produk'];
			}

			$posts = $this->db->query("SELECT * FROM `tb_detail_pemesan_produk` dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk  WHERE dp_produk.id_detail_pemesan_produk='$maxxid'")->result_array();
			$idProduk=$posts[0]["id_produk"];
			$lama_produksi=$posts[0]["lama_produksi"];

			

			$tb_produk = $this->db->get('tb_produk')->result_array();
			
			// echo $lama_prodok;
			// echo '<br>';
			// echo $date1;
			// echo '<br>';
			// echo $date;
			// echo '<br>';
			// echo $tgl_selesaiProduk;
			// echo '<br>';
			// echo $tgl_selesaiProduk;
			// echo '<br>';
			// echo strtotime($tgl);
			// foreach($posts as $k=>$v) {
			echo '<tr class="table-row" id="table-row-';echo $posts[0]["id_detail_pemesan_produk"]; echo'">';
			echo '<td contenteditable="true" onClick="editRow(this);">';
			echo '<select name="required['; echo $id; echo']" id="required" class="form-control" style="width:150px;" onBlur="saveToDatabase(this,';echo "'id_produk'"; echo",'"; echo $posts[0]["id_detail_pemesan_produk"]; echo"'"; echo ')">';			
			foreach($tb_produk as $item) {
				echo '<option value="'; echo $item["id_produk"];echo '"';  if($posts[0]["id_produk"] == $item['id_produk']) echo 'selected="selected"'; echo '>'; echo $item["nama_produk"]; echo'</option>';
			};
			echo '</select>';
			echo '</td>';
			echo '<td contenteditable="true" >';
			echo '<input type="number" min="6" onBlur="saveToDatabase(this,';echo "'qty'"; echo",'"; echo $posts[0]["id_detail_pemesan_produk"]; echo"'"; echo ')" onClick="editRow(this);" value="'; echo $posts[0]["qty"]; echo'"/>';
			echo '</td>';
			echo '<td>';
			echo '<a class="ajax-action-links" onclick="deleteRecord('; echo $posts[0]["id_detail_pemesan_produk"]; echo ');">Delete</a>';
			echo '</td>';
			echo '</tr>';
			// }

			// $this->db->query("UPDATE `tb_produk` SET `sisa` = '$sisaLast' WHERE id_produk = '$title'");
		}else{
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
		}
	}
	function edit()
	{

			$colom=$_POST["column"] ;
			$id=$_POST["id"] ;
			$value=$_POST["editval"] ;
			if ($colom=='qty') {
				$getJumlah =$this->db->query("SELECT * FROM tb_detail_pemesan_produk WHERE id_detail_pemesan_produk = '$id'")->result_array();
				foreach($getJumlah as $getJumlah){
					$jumlah=$getJumlah['qty'];
					$id_produk=$getJumlah['id_produk'];
				}

				$getSisa =$this->db->query("SELECT * FROM tb_produk WHERE id_produk = '$id_produk'")->result_array();
				
				foreach($getSisa as $getSisa){
					$sisaLast=$getSisa['sisa']-$jumlah+$value;
				}
				$this->db->query("UPDATE tb_detail_pemesan_produk set  ". $_POST["column"] ."= '" . $_POST["editval"] . "' WHERE  id_detail_pemesan_produk='" . $_POST["id"] . "'");
				$this->db->query("UPDATE `tb_produk` SET `sisa` = '$sisaLast' WHERE id_produk = '$id_produk'");

			} else {
				$getJumlah =$this->db->query("SELECT * FROM tb_detail_pemesan_produk WHERE id_detail_pemesan_produk = '$id'")->result_array();
				foreach($getJumlah as $getJumlah){
					$jumlah=$getJumlah['qty'];
					$id_produk=$getJumlah['id_produk'];
				}

				$getSisa =$this->db->query("SELECT * FROM tb_produk WHERE id_produk = '$id_produk'")->result_array();
				
				foreach($getSisa as $getSisa){
					$sisaLast=$getSisa['sisa']-$jumlah;
				}

				$getSisa2 =$this->db->query("SELECT * FROM tb_produk WHERE id_produk = '$value'")->result_array();
			
				foreach($getSisa2 as $getSisa2){
					$sisaLast2=$getSisa2['sisa']+$jumlah;
				}

				$this->db->query("UPDATE tb_detail_pemesan_produk set  ". $_POST["column"] ."= '" . $_POST["editval"] . "' WHERE  id_detail_pemesan_produk='" . $_POST["id"] . "'");
				$this->db->query("UPDATE `tb_produk` SET `sisa` = '$sisaLast' WHERE id_produk = '$id_produk'");
				$this->db->query("UPDATE `tb_produk` SET `sisa` = '$sisaLast2' WHERE id_produk = '$value'");
			}
			
			
		
	}

	function getBahanBaku()
	{
		$idSupplier = $_POST["idSupplier"];
		$tb_produk = $this->db->query("SELECT * FROM `tb_produk` WHERE id_supplier='$idSupplier'")->result_array();
		$this->data['tb_produk'] =  $tb_produk;
	}

	function setTanggalSelesai()
	{
		if(!empty($_POST["id"])) {
			$id = $_POST["id"];
			$max =$this->db->query("SELECT * FROM tb_detail_pemesan_produk WHERE id_pemesanan = '$id ' AND tgl_selesai=(SELECT MAX(tgl_selesai) FROM tb_detail_pemesan_produk WHERE id_pemesanan = '$id')")->result_array();
			foreach($max as $max){
				$tgl_selesai=$max['tgl_selesai'];
			}
			echo $tgl_selesai;
		}
	}
	function setDP()
	{
		if(!empty($_POST["id"])) {
			$id = $_POST["id"];
			$cariDP = $this->db->query("SELECT (SUM((qty*(harga/12)))*0.30) as dp, SUM((qty*(harga/12))) as total FROM `tb_detail_pemesan_produk` dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk  WHERE dp_produk.id_pemesanan='$id'")->result_array();
			$data['dp'] =$cariDP[0]["dp"];
			$data['total'] =$cariDP[0]["total"];
			echo json_encode($data);
		}
	}
}
