<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'pengiriman';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('pengirimanmodel');
		$this->model = $this->pengirimanmodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'pengiriman',
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
		$sort = (!is_null($this->input->get('sort', true)) ? $this->input->get('sort', true) : 'id_pengiriman'); 
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
		
		$this->data['content'] = $this->load->view('pengiriman/index',$this->data, true );
		
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
			$this->data['row'] = $this->model->getColumnTable('tb_pengiriman'); 
		}
		
		$this->data['id'] = $id;
		$this->data['content'] =  $this->load->view('pengiriman/view', $this->data ,true);	  
		$this->load->view('layouts/main',$this->data);
	}
  
	function add( $id = null ) 
	{
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);

		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	

		$row = $this->model->getRow( $id );
		$rows = $this->db->query("SELECT *, SUM(dpp.qty) as hasil FROM `tb_pemesanan_produk` pp INNER JOIN tb_detail_pemesan_produk dpp ON pp.id_pemesanan= dpp.id_pemesanan INNER JOIN tb_users user ON pp.id_user= user.id WHERE pp.status_pemesanan = 'selesai' GROUP BY dpp.id_pemesanan")->result_array();
		if($row)
		{
			$this->data['row'] =  $row;
			$this->data['rows'] =  $rows;
		} else {
			$this->data['rows'] =  $rows;
			$this->data['row'] = $this->model->getColumnTable('tb_pengiriman'); 
		}
	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('pengiriman/form',$this->data, true );		
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
			$ID = $this->model->insertRow($data , $this->input->get_post( 'id_pengiriman' , true ));
			// Input logs
			if( $this->input->get( 'id_pengiriman' , true ) =='')
			{
				$this->inputLogs("New Entry row with ID : $ID  , Has Been Save Successfull");
			} else {
				$this->inputLogs(" ID : $ID  , Has Been Changed Successfull");
			}
			// Redirect after save	
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
			if($this->input->post('apply'))
			{
				redirect( 'pengiriman/add/'.$ID,301);
			} else {
				redirect( 'pengiriman',301);
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
		Redirect('pengiriman',301); 
	}

	function selectPemesananByJenis()
	{
		if(!empty($_POST["jenis"])) {
			$jenis = $_POST["jenis"];
			$posts = $this->db->query("SELECT *, SUM(dpp.qty) as hasil FROM `tb_pemesanan_produk` pp INNER JOIN tb_detail_pemesan_produk dpp ON pp.id_pemesanan= dpp.id_pemesanan INNER JOIN tb_users user ON pp.id_user= user.id where pp.tipePengiriman='$jenis' AND pp.status_pemesanan ='selesai' GROUP BY dpp.id_pemesanan")->result_array();
			if (isset($posts)) {
				foreach($posts as $posts){
					echo '<tr class="table-row" id="table-row-';echo $posts["id_pemesanan"]; echo'">';
					echo '<td>';
					echo $posts["id_pemesanan"];
					echo '</td>';
					echo '<td>';
					echo $posts["first_name"].' '.$posts["last_name"];
					echo '</td>';
					echo '<td>';
					echo $posts["hasil"];
					echo '</td>';
					echo '<td><input type="text" name="noResi" id="noResi" onBlur="setNoResi(this)"/></td>';
					echo '<td><input type="hidden" id="description" /><a onClick="addToDatabase('.$posts["id_pemesanan"].')" class="ajax-action-links">Kirim</a></td>';
					echo '</tr>';
				}
			} 
		}
	}

	function tambahPengiriman()
	{
		$id_pemesanan = $_POST["id_pemesanan"];
		$id_pengiriman = $_POST["id_pengiriman"];
		$jenis_pengiriman = $_POST["jenis_pengiriman"];
		$tgl_pengiriman = $_POST["tgl_pengiriman"];
		$tgl_input = $_POST["tgl_input"];
		$id_kendaraan = $_POST["id_kendaraan"];
		$status_pengiriman = $_POST["status_pengiriman"];
		$id_user = $_POST["id_user"];
		$resi = $_POST["noResi"];

		if ($id_pengiriman) {
			$this->db->query("UPDATE `tb_pengiriman` SET `jenis_pengiriman` = '$jenis_pengiriman', `tgl_pengiriman` = '$tgl_pengiriman', `tgl_input` = '$tgl_input', `id_kendaraan` = '$id_kendaraan', `status_pengiriman` = '$status_pengiriman', `id_user` = '$id_user' WHERE `tb_pengiriman`.`id_pengiriman` = '$id_pengiriman'");
			$this->db->query("UPDATE tb_pemesanan_produk set  status_pemesanan= 'Dikirim' WHERE  id_pemesanan='" . $_POST["id_pemesanan"] . "'");
			if ($id_pemesanan != null) {
				$this->db->query("INSERT INTO `tb_detail_pengiriman` (`id`, `id_pengiriman`, `id_pemesanan`, `status`, `noResi`) VALUES (NULL, '$id_pengiriman', '$id_pemesanan', 'Dikirim', '$resi')");
			}
			
			$posts = $this->db->query("select *,SUM(dpp.qty) as hasil,dp.id as id_detail from tb_detail_pengiriman dp INNER join tb_pemesanan_produk pp on dp.id_pemesanan=pp.id_pemesanan INNER JOIN tb_detail_pemesan_produk dpp ON pp.id_pemesanan= dpp.id_pemesanan INNER JOIN tb_users user ON pp.id_user= user.id where id_pengiriman='$id_pengiriman' GROUP BY dpp.id_pemesanan")->result_array();
			if (isset($posts)) {
				foreach($posts as $posts){
					$data['data'] = '<tr class="table-rows" id="table-rows-'.$posts["id_detail"].'">'
					.'<td>'
					.$posts["id_pemesanan"]
					.'</td>'
					.'<td>'
					.$posts["first_name"].' '.$posts["last_name"]
					.'</td>'
					.'<td>'
					.$posts["hasil"]
					.'</td>'
					.'<td><input type="text" name="noResi" id="noResi" onBlur="setNoResi(this)" value="'.$posts["noResi"].'"/></td>'
					.'<td><input type="hidden" id="description" /><a onClick="deleteRecord('.$posts["id_detail"].','.$posts["id_pemesanan"].')" class="ajax-action-links">Delete</a></td>'
					.'</tr>';
				}
			} 
			$data['id_pengiriman'] = $id_pengiriman;
			echo json_encode($data);
				
		} else {
			$this->db->query("INSERT INTO `tb_pengiriman` (`id_pengiriman`, `jenis_pengiriman`, `tgl_pengiriman`, `tgl_input`, `id_kendaraan`, `status_pengiriman`, `id_user`) VALUES (NULL, '$jenis_pengiriman', '$tgl_pengiriman', CURRENT_TIMESTAMP, '$id_kendaraan', '$status_pengiriman', '$id_user')");
			$this->db->query("UPDATE tb_pemesanan_produk set  status_pemesanan= 'Dikirim' WHERE  id_pemesanan='" . $_POST["id_pemesanan"] . "'");
			$maxx =$this->db->query("SELECT * FROM tb_pengiriman WHERE id_pengiriman = (SELECT MAX(id_pengiriman) FROM tb_pengiriman)")->result_array();
			foreach($maxx as $max){
				$maxxid=$max['id_pengiriman'];
			}
			if ($id_pemesanan != null) {
				$data['id_pengiriman'] = $maxxid;
				$this->db->query("INSERT INTO `tb_detail_pengiriman` (`id`, `id_pengiriman`, `id_pemesanan`, `status`, `noResi`) VALUES (NULL, '$maxxid', '$id_pemesanan', 'Dikirim', '$resi')");
				$posts = $this->db->query("select *,SUM(dpp.qty) as hasil,dp.id as id_detail from tb_detail_pengiriman dp INNER join tb_pemesanan_produk pp on dp.id_pemesanan=pp.id_pemesanan INNER JOIN tb_detail_pemesan_produk dpp ON pp.id_pemesanan= dpp.id_pemesanan INNER JOIN tb_users user ON pp.id_user= user.id where id_pengiriman='$maxxid' GROUP BY dpp.id_pemesanan")->result_array();
				if (isset($posts)) {
					foreach($posts as $posts){
						$data['data'] = '<tr class="table-rows" id="table-rows-'.$posts["id_detail"].'">'
						.'<td>'
						.$posts["id_pemesanan"]
						.'</td>'
						.'<td>'
						.$posts["first_name"].' '.$posts["last_name"]
						.'</td>'
						.'<td>'
						.$posts["hasil"]
						.'</td>'
						.'<td><input type="text" name="noResi" id="noResi" onBlur="setNoResi(this)" value="'.$posts["noResi"].'"/></td>'
						.'<td><input type="hidden" id="description" /><a onClick="deleteRecord('.$posts["id_detail"].','.$posts["id_pemesanan"].')" class="ajax-action-links">Delete</a></td>'
						.'</tr>';
					}
				} 
				echo json_encode($data);
				
			} else {
				# code...
			}
		}

		

		
		
	}
	function delete()
	{
		$id_detail_pengiriman=$_POST['id'];
		$id_pemesanan=$_POST['id_pemesanan'];
		$jenis = 'jasa';
		if($_POST['id'] == "")
		{
			
		}
		else
		{
			$this->db->delete('tb_detail_pengiriman', 'id = '.$_POST['id']);
			$this->db->query("UPDATE tb_pemesanan_produk set  status_pemesanan= 'selesai' WHERE  id_pemesanan='" . $_POST["id_pemesanan"] . "'");
			$posts = $this->db->query("SELECT *, SUM(dpp.qty) as hasil FROM `tb_pemesanan_produk` pp INNER JOIN tb_detail_pemesan_produk dpp ON pp.id_pemesanan= dpp.id_pemesanan INNER JOIN tb_users user ON pp.id_user= user.id where pp.tipePengiriman='$jenis' GROUP BY dpp.id_pemesanan")->result_array();
			if (isset($posts)) {
				foreach($posts as $posts){
					echo '<tr class="table-row" id="table-row-';echo $posts["id_pemesanan"]; echo'">';
					echo '<td>';
					echo $posts["id_pemesanan"];
					echo '</td>';
					echo '<td>';
					echo $posts["first_name"].' '.$posts["last_name"];
					echo '</td>';
					echo '<td>';
					echo $posts["hasil"];
					echo '</td>';
					echo '<td><input type="text" name="noResi" id="noResi" onBlur="setNoResi(this)"/></td>';
					echo '<td><input type="hidden" id="description" /><a onClick="addToDatabase('.$posts["id_pemesanan"].')" class="ajax-action-links">Kirim</a></td>';
					echo '</tr>';
				}
			} 
		}
	
	}
	function showTable(){
		$id_pengiriman = $_POST["id"];
		if ($id_pengiriman != null) {
			$posts = $this->db->query("select *,SUM(dpp.qty) as hasil,dp.id as id_detail from tb_detail_pengiriman dp INNER join tb_pemesanan_produk pp on dp.id_pemesanan=pp.id_pemesanan INNER JOIN tb_detail_pemesan_produk dpp ON pp.id_pemesanan= dpp.id_pemesanan INNER JOIN tb_users user ON pp.id_user= user.id where id_pengiriman='$id_pengiriman' GROUP BY dpp.id_pemesanan")->result_array();
			if(!empty($_POST["jenis"])) {
				$jenis = $_POST["jenis"];
				$posts1 = $this->db->query("SELECT *, SUM(dpp.qty) as hasil FROM `tb_pemesanan_produk` pp INNER JOIN tb_detail_pemesan_produk dpp ON pp.id_pemesanan= dpp.id_pemesanan INNER JOIN tb_users user ON pp.id_user= user.id where pp.tipePengiriman='$jenis' AND pp.status_pemesanan ='selesai' GROUP BY dpp.id_pemesanan")->result_array();
				if (isset($posts1)) {
					foreach($posts1 as $posts1){
						$data['data1'] ='<tr class="table-row" id="table-row-'. $posts1["id_pemesanan"]. '">'
						.'<td>'
						. $posts1["id_pemesanan"].
						'</td>'.
						'<td>'.
						$posts1["first_name"].' '.$posts1["last_name"].
						'</td>'.
						'<td>'.
						$posts1["hasil"].
						'</td>'.
						'<td><input type="text" name="noResi" id="noResi" onBlur="setNoResi(this)"/></td>'.
						'<td><input type="hidden" id="description" /><a onClick="addToDatabase('.$posts1["id_pemesanan"].')" class="ajax-action-links">Kirim</a></td>'.
						'</tr>';
					}
				} 
			}
			if (isset($posts)) {
				foreach($posts as $posts){
					$data['data'] = '<tr class="table-rows" id="table-rows-'.$posts["id_detail"].'">'
					.'<td>'
					.$posts["id_pemesanan"]
					.'</td>'
					.'<td>'
					.$posts["first_name"].' '.$posts["last_name"]
					.'</td>'
					.'<td>'
					.$posts["hasil"]
					.'</td>'
					.'<td><input type="text" name="noResi" id="noResi" onBlur="setNoResi(this)" value="'.$posts["noResi"].'"/></td>'
					.'<td><input type="hidden" id="description" /><a onClick="deleteRecord('.$posts["id_detail"].','.$posts["id_pemesanan"].')" class="ajax-action-links">Delete</a></td>'
					.'</tr>';
				}
			} 
			$data['id_pengiriman'] = $id_pengiriman;
			echo json_encode($data);
		}

		
	}

}
