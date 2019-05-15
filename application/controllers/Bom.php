<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bom extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'bom';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('bommodel');
		$this->model = $this->bommodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'bom',
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
		$sort = (!is_null($this->input->get('sort', true)) ? $this->input->get('sort', true) : 'id_bom'); 
		$order = (!is_null($this->input->get('order', true)) ? $this->input->get('order', true) : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($this->input->get('search', true)) ? $this->buildSearch() : '');
		// End Filter Search for query 
		
		$page = max(1, (int) $this->input->get('page', 1));
		$params = array(
			'page'		=> $page ,
			'limit'		=> ($this->input->get('rows', true) !='' ? filter_var($this->input->get('rows', true),FILTER_VALIDATE_INT) : $this->per_page ) ,
			'sort'		=> 'kode_produk' ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);

		// var_dump($params);
		// die();
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
		// var_dump($this->data);
		// die();
		$this->data['content'] = $this->load->view('bom/index',$this->data, true );
		
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
			$this->data['row'] = $this->model->getColumnTable('tb_bom'); 
		}
		
		$this->data['id'] = $id;
		$this->data['content'] =  $this->load->view('bom/view', $this->data ,true);	  
		$this->load->view('layouts/main',$this->data);
	}
  
	function add( $id = null ) 
	{
		$tb_bahan_baku = $this->db->get('tb_bahan_baku')->result_array();
		$this->data['tb_bahan_baku'] =  $tb_bahan_baku;
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);

		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	

		$row = $this->db->query("SELECT * FROM tb_bom WHERE id_produk='$id'")->result_array();
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] =  $row;
		}
	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('bom/form',$this->data, true );		
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
			$ID = $this->model->insertRow($data , $this->input->get_post( 'id_bom' , true ));
			// Input logs
			if( $this->input->get( 'id_bom' , true ) =='')
			{
				$this->inputLogs("New Entry row with ID : $ID  , Has Been Save Successfull");
			} else {
				$this->inputLogs(" ID : $ID  , Has Been Changed Successfull");
			}
			// Redirect after save	
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
			if($this->input->post('apply'))
			{
				redirect( 'bom/add/'.$ID,301);
			} else {
				redirect( 'bom',301);
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
		Redirect('bom',301); 
	}

	function tambah()
	{
		
		if(!empty($_POST["id"])) {
			$title = $_POST["title"];
			$id = $_POST["id"];
			$description = $_POST["description"];

			$this->db->query("INSERT INTO `tb_bom` (`id_bom`, `id_produk`, `id_bahan_baku`,`jumlah_kebutuhan`) VALUES (NULL, '$id', '$title', '$description')");
			$maxx =$this->db->query("SELECT * FROM tb_bom WHERE id_bom = (SELECT MAX(id_bom) FROM tb_bom)")->result_array();
			foreach($maxx as $max){
				$maxxid=$max['id_bom'];
			}

			$posts = $this->db->query("SELECT * FROM `tb_bom` WHERE id_bom='$maxxid'")->result_array();
			

			$tb_bahan_baku = $this->db->get('tb_bahan_baku')->result_array();
			echo '<tr class="table-row" id="table-row-';echo $posts[0]["id_bom"]; echo'">';
			echo '<td contenteditable="true" onClick="editRow(this);">';
			echo '<select name="required['; echo $id; echo']" id="required" class="form-control" style="width:150px;" onBlur="saveToDatabase(this,';echo "'id_bahan_baku'"; echo",'"; echo $posts[0]["id_bom"]; echo"'"; echo ')">';			
			foreach($tb_bahan_baku as $item) {
				echo '<option value="'; echo $item["id_bahan_baku"];echo '"';  if($posts[0]["id_bahan_baku"] == $item['id_bahan_baku']) echo 'selected="selected"'; echo '>'; echo $item["nama_bahan_baku"];echo'&nbsp';echo $item["jenis_bahan_baku"];echo'</option>';
			};
			echo '</select>';
			echo '</td>';
			echo '<td contenteditable="true" >';
			echo '<input type="number" min="6" onBlur="saveToDatabase(this,';echo "'jumlah_kebutuhan'"; echo",'"; echo $posts[0]["id_bom"]; echo"'"; echo ')" onClick="editRow(this);" value="'; echo $posts[0]["jumlah_kebutuhan"]; echo'"/>';
			echo '</td>';
			echo '<td>';
			echo '<a class="ajax-action-links" onclick="deleteRecord('; echo $posts[0]["id_bom"]; echo ');">Delete</a>';
			echo '</td>';
			echo '</tr>';

		}else{
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
		}
	}
	function edit()
	{

		$colom=$_POST["column"] ;
		$id=$_POST["id"] ;
		$value=$_POST["editval"] ;
			
		$this->db->query("UPDATE tb_bom set  ". $_POST["column"] ."= '" . $_POST["editval"] . "' WHERE  id_bom='" . $_POST["id"] . "'");

		
	}
	function delete()
	{
		$id_detail_pemesan_produk=$_POST['id'];
		if($_POST['id'] == "")
		{
			
		}
		else
		{
			$this->db->delete('tb_bom', 'id_bom = '.$_POST['id']);
		}
	
	}
}
