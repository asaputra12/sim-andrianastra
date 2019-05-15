<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengadaan extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'pengadaan';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('pengadaanmodel');
		$this->model = $this->pengadaanmodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'pengadaan',
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
		$sort = (!is_null($this->input->get('sort', true)) ? $this->input->get('sort', true) : 'id_pengadaan'); 
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
		
		$this->data['content'] = $this->load->view('pengadaan/index',$this->data, true );
		
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
			$this->data['row'] = $this->model->getColumnTable('tb_pengadaan'); 
		}
		
		$this->data['id'] = $id;
		$this->data['content'] =  $this->load->view('pengadaan/view', $this->data ,true);	  
		$this->load->view('layouts/main',$this->data);
	}
  
	function add( $id = null ) 
	{
	
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);
		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	

		$row = $this->model->getRow( $id );
		
		
		$tb_bahan_baku = $this->db->get('tb_bahan_baku')->result_array();
		$this->data['tb_bahan_baku'] =  $tb_bahan_baku;
		
		$max =$this->db->query("SELECT * FROM tb_pengadaan WHERE id_pengadaan = (SELECT MAX(id_pengadaan) FROM tb_pengadaan)")->result_array();
		$rowmax =$this->db->query("SELECT * FROM tb_pengadaan WHERE id_pengadaan = (SELECT MAX(id_pengadaan) FROM tb_pengadaan)")->row();
		
		foreach($max as $max){
			$maxid=$max['id_pengadaan']+1;
		}
		if($rowmax == NULL){
			$maxid='0001';
		}
		
		if($row)
		{
			$this->data['row'] =  $row;
			$posts = $this->db->query("SELECT * FROM `tb_detail_pembelian_bahan_baku` WHERE id_pengadaan='$id'")->result_array();
			$this->data['posts'] =  $posts;
			// var_dump($posts);
			// die();
		} else {
			
			$a =  $this->model->getColumnTable('tb_pengadaan');
			$b=  array(
				'id_pengadaan' => $maxid,
				'id_supplier' => '',
				'id_bahan_baku' => '',
				'jumlah_pengadaan' => '',
				'tanggal_pembelian' => '',
				'id_user' => '',
				'status_pengadaan' => '',
				'periode' => '',
			);         
			// var_dump($b); 
			// echo('<br>'); 
			// var_dump($a);   
			// die();
			$this->data['row'] = $b;
			$posts = $this->db->query("SELECT * FROM `tb_detail_pembelian_bahan_baku` WHERE id_pengadaan='$maxid'")->result_array();
			$this->data['posts'] =  $posts;
			// var_dump($posts);
			// die();
			
		
		}
	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('pengadaan/form',$this->data, true );		
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
			$id = $this->input->post('id_pengadaan',true);
			$rows =$this->db->query("SELECT * FROM `tb_pengadaan` WHERE id_pengadaan='$id'")->row();
			if(count($rows)>=1){
            	$this->db->update('tb_pengadaan', $data, 'id_pengadaan = '."$id");
        	}else{
				$this->db->insert('tb_pengadaan',$data);
			}
			
			// Input logs
			if( $this->input->get( 'id_pengadaan' , true ) =='')
			{
				$this->inputLogs("New Entry row with ID : $ID  , Has Been Save Successfull");
			} else {
				$this->inputLogs(" ID : $ID  , Has Been Changed Successfull");
			}
			// Redirect after save	
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
			if($this->input->post('apply'))
			{
				redirect( 'pengadaan/add/'.$ID,301);
			} else {
				redirect( 'pengadaan',301);
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
		Redirect('pengadaan',301); 
	}

	function delete()
	{
		$id_detail_bahan_baku=$_POST['id'];
		if($_POST['id'] == "")
		{
			
		}
		else
		{

			$getJumlah =$this->db->query("SELECT * FROM tb_detail_pembelian_bahan_baku WHERE id_detail_bahan_baku = '$id_detail_bahan_baku'")->result_array();
			foreach($getJumlah as $getJumlah){
				$jumlah=$getJumlah['jumlah_kebutuhan'];
				$id_bahan_baku=$getJumlah['id_bahan_baku'];
			}

			$getSisa =$this->db->query("SELECT * FROM tb_bahan_baku WHERE id_bahan_baku = '$id_bahan_baku'")->result_array();
			
			foreach($getSisa as $getSisa){
				$sisaLast=$getSisa['sisa']-$jumlah;
			}

			$this->db->query("UPDATE `tb_bahan_baku` SET `sisa` = '$sisaLast' WHERE id_bahan_baku = '$id_bahan_baku'");
			$this->db->delete('tb_detail_pembelian_bahan_baku', 'id_detail_bahan_baku = '.$_POST['id']);
		}
	
	}

	function tambah()
	{
		
		if(!empty($_POST["id"])) {
			$title = $_POST["title"];
			$id = $_POST["id"];
			$description = $_POST["description"];

			$getSisa =$this->db->query("SELECT * FROM tb_bahan_baku WHERE id_bahan_baku = '$title'")->result_array();
			
			foreach($getSisa as $getSisa){
				$sisaLast=$getSisa['sisa']+$description;
			}


			$this->db->query("INSERT INTO `tb_detail_pembelian_bahan_baku` (`id_detail_bahan_baku`, `id_pengadaan`, `id_bahan_baku`, `jumlah_kebutuhan`, `harga`, `status_bahan_baku`) VALUES (NULL, '$id', '$title', '$description', NULL, NULL)");
			$this->db->query("UPDATE `tb_bahan_baku` SET `sisa` = '$sisaLast' WHERE id_bahan_baku = '$title'");
		}else{
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
		}
	}
	function edit()
	{

			$colom=$_POST["column"] ;
			$id=$_POST["id"] ;
			$value=$_POST["editval"] ;
			if ($colom=='jumlah_kebutuhan') {
				$getJumlah =$this->db->query("SELECT * FROM tb_detail_pembelian_bahan_baku WHERE id_detail_bahan_baku = '$id'")->result_array();
				foreach($getJumlah as $getJumlah){
					$jumlah=$getJumlah['jumlah_kebutuhan'];
					$id_bahan_baku=$getJumlah['id_bahan_baku'];
				}

				$getSisa =$this->db->query("SELECT * FROM tb_bahan_baku WHERE id_bahan_baku = '$id_bahan_baku'")->result_array();
				
				foreach($getSisa as $getSisa){
					$sisaLast=$getSisa['sisa']-$jumlah+$value;
				}
				$this->db->query("UPDATE tb_detail_pembelian_bahan_baku set  ". $_POST["column"] ."= '" . $_POST["editval"] . "' WHERE  id_detail_bahan_baku='" . $_POST["id"] . "'");
				$this->db->query("UPDATE `tb_bahan_baku` SET `sisa` = '$sisaLast' WHERE id_bahan_baku = '$id_bahan_baku'");

			} else {
				$getJumlah =$this->db->query("SELECT * FROM tb_detail_pembelian_bahan_baku WHERE id_detail_bahan_baku = '$id'")->result_array();
				foreach($getJumlah as $getJumlah){
					$jumlah=$getJumlah['jumlah_kebutuhan'];
					$id_bahan_baku=$getJumlah['id_bahan_baku'];
				}

				$getSisa =$this->db->query("SELECT * FROM tb_bahan_baku WHERE id_bahan_baku = '$id_bahan_baku'")->result_array();
				
				foreach($getSisa as $getSisa){
					$sisaLast=$getSisa['sisa']-$jumlah;
				}

				$getSisa2 =$this->db->query("SELECT * FROM tb_bahan_baku WHERE id_bahan_baku = '$value'")->result_array();
			
				foreach($getSisa2 as $getSisa2){
					$sisaLast2=$getSisa2['sisa']+$jumlah;
				}

				$this->db->query("UPDATE tb_detail_pembelian_bahan_baku set  ". $_POST["column"] ."= '" . $_POST["editval"] . "' WHERE  id_detail_bahan_baku='" . $_POST["id"] . "'");
				$this->db->query("UPDATE `tb_bahan_baku` SET `sisa` = '$sisaLast' WHERE id_bahan_baku = '$id_bahan_baku'");
				$this->db->query("UPDATE `tb_bahan_baku` SET `sisa` = '$sisaLast2' WHERE id_bahan_baku = '$value'");
			}
			
			
		
	}

	function getBahanBaku()
	{
		$idSupplier = $_POST["idSupplier"];
		$tb_bahan_baku = $this->db->query("SELECT * FROM `tb_bahan_baku` WHERE id_supplier='$idSupplier'")->result_array();
		$this->data['tb_bahan_baku'] =  $tb_bahan_baku;
	}
}
