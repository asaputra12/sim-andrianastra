<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cekresi extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'cekresi';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('cekresimodel');
		$this->model = $this->cekresimodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'cekresi',
		));
		
		if(!$this->session->userdata('logged_in')) redirect('user/login',301);
		
	}
	
	function index($id = null) 
	{
		if($this->access['is_view'] ==0)
		{ 
			SiteHelpers::alert('error','Your are not allowed to access the page');
			redirect('dashboard',301);
		}	
		  
		// Filter sort and order for query 
		$sort = (!is_null($this->input->get('sort', true)) ? $this->input->get('sort', true) : 'id'); 
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
		
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);

		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	

		$row = $this->model->getRow( $id );
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_detail_pengiriman'); 
		}
	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('cekresi/form',$this->data, true );		
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
			$this->data['row'] = $this->model->getColumnTable('tb_detail_pengiriman'); 
		}
		
		$this->data['id'] = $id;
		$this->data['content'] =  $this->load->view('cekresi/view', $this->data ,true);	  
		$this->load->view('layouts/main',$this->data);
	}
  
	function add( $id = null ) 
	{
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);

		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	

		$row = $this->model->getRow( $id );
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_detail_pengiriman'); 
		}
	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('cekresi/form',$this->data, true );		
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
			$ID = $this->model->insertRow($data , $this->input->get_post( 'id' , true ));
			// Input logs
			if( $this->input->get( 'id' , true ) =='')
			{
				$this->inputLogs("New Entry row with ID : $ID  , Has Been Save Successfull");
			} else {
				$this->inputLogs(" ID : $ID  , Has Been Changed Successfull");
			}
			// Redirect after save	
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
			if($this->input->post('apply'))
			{
				redirect( 'cekresi/add/'.$ID,301);
			} else {
				redirect( 'cekresi',301);
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
		Redirect('cekresi',301); 
	}

	function cek()
	{
		$noresi=$_POST["noresi"];
		if(!empty($_POST["noresi"])) {

			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "waybill=SOCAG00183235715&courier=jne",
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: 74eaba06103c7e42049c4d5286da4b00"
			),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			echo "cURL Error #:" . $err;
			} else {
				$hasil = json_decode($response,TRUE);
				$hasil2 = $hasil['rajaongkir']['result']['manifest'];

				$i = 0;
				$val = 0;


				echo "<div class=login-box>";
				echo "<div class=login-logo>";
				echo "<center>";
				echo "<b>Cek Pengiriman </b>";
				echo "</center>";
				echo "</div>";
				echo "<div class=login-box-body>";
				echo "<center>";
				echo "Pengirim : ";
				echo $hasil['rajaongkir']['result']['summary']['shipper_name'];
				echo "<br>";
				echo "Tipe Pengiriman : ";
				echo $hasil['rajaongkir']['result']['summary']['service_code'];
				echo "<br>";
				echo "Kota Asal : ";
				echo $hasil['rajaongkir']['result']['summary']['origin'];
				echo "<br>";
				echo "Kota Tujuan : ";
				echo $hasil['rajaongkir']['result']['summary']['destination'];
				echo "<br>";
				echo "Status : ";
				echo $hasil['rajaongkir']['result']['summary']['status'];
				echo "<br>";


				echo "<table class=table table-striped table-responsive style=margin-top: 10000px><th>Tanggal</th><th>Waktu</th><th>Kota</th>";

				foreach($hasil2 as $value){

				$hasildate = $hasil['rajaongkir']['result']['manifest'][$i]['manifest_date'];
				echo "<tr><td width = '100'>".$hasildate."</td>";
				$hasiltime = $hasil['rajaongkir']['result']['manifest'][$i]['manifest_time'];
				echo "<td width = '100'>".$hasiltime."</td>";
				$hasilcity = $hasil['rajaongkir']['result']['manifest'][$i]['city_name'];
				echo "<td width = '100'>".$hasilcity."</td></tr>";

				$i++;

				}

				echo "</table>";
				echo "</div>";
				echo "</div>";
			}
		}
	}
}
