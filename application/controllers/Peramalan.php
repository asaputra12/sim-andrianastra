<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Peramalan extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'peramalan';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('peramalanmodel');
		$this->model = $this->peramalanmodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'peramalan',
		));
		
		if(!$this->session->userdata('logged_in')) redirect('user/login',301);
		
	}
	
	function index() 
	{
		if($this->access['is_view'] ==0)
		{ 
			$this->session->set_flashdata('error',SiteHelpers::alert('error','Your are not allowed to access the page'));
			redirect('dashboard',301);
		}	
		  
		// Filter sort and order for query 
		$sort = (!is_null($this->input->get('sort', true)) ? $this->input->get('sort', true) : ''); 
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
		$query = $this->db->query("SELECT SUM(dp_produk.qty) Permintaan, produk.nama_produk Produk, DATE_FORMAT(dp_produk.tgl_pemesanan,'%M %Y') Tahun FROM tb_detail_pemesan_produk dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk where dp_produk.tgl_pemesanan > DATE_SUB(now(), INTERVAL 6 MONTH) GROUP BY dp_produk.id_produk, dp_produk.tgl_pemesanan Order By dp_produk.tgl_pemesanan ASC")->result_array();
		$rowsss = $this->db->query("SELECT SUM(dp_produk.qty) Permintaan, produk.nama_produk Produk, DATE_FORMAT(dp_produk.tgl_pemesanan,'%M %Y') Tahun FROM tb_detail_pemesan_produk dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk where dp_produk.tgl_pemesanan > DATE_SUB(now(), INTERVAL 6 MONTH) GROUP BY dp_produk.id_produk, dp_produk.tgl_pemesanan Order By dp_produk.tgl_pemesanan ASC")->num_rows();
		$tb_produk = $this->db->get('tb_produk')->result_array();
		$this->data['tb_produk'] =  $tb_produk;
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		#$pagination = Paginator::make($results['rows'], $results['total'],$params['limit']);		
		$this->data['rowData']		= $results['rows'];
		$ii=0;
		$iii=0;
		for ($i=0; $i < $rowsss ; $i++) { 
			if ($i >= 3) {
				if ($i >= 5) {
						$data['singleMoving'][$i] =  array(
							'Produk' => $query[$i]["Produk"],
							'Permintaan' => $query[$i]["Permintaan"],
							'Tahun' => $query[$i]["Tahun"],
							'Per3Bulan' => ($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3,
							'kesalahan' => $query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3),
							'absolut' => abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),
							'kuadrat' => pow(abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),2),
		
							'Per5Bulan' => ($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3,
							'kesalahan5' => $query[$i]["Permintaan"]-(($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3),
							'absolut5' => abs($query[$i]["Permintaan"]-(($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3)),
							'kuadrat5' => pow(abs($query[$i]["Permintaan"]-(($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3)),2),
						); 
						$iii++;
				} else {
					$data['singleMoving'][$i] =  array(
						'Produk' => $query[$i]["Produk"],
						'Permintaan' => $query[$i]["Permintaan"],
						'Tahun' => $query[$i]["Tahun"],
						'Per3Bulan' => ($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3,
						'kesalahan' => $query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3),
						'absolut' => abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),
						'kuadrat' => pow(abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),2),
						'Per5Bulan' => Null,
						'kesalahan5' => Null,
						'absolut5' =>Null,
						'kuadrat5' => Null,
					); 
				}
				$ii++;
			} else{
				$data['singleMoving'][$i] =  array(
					'Produk' => $query[$i]["Produk"],
					'Permintaan' => $query[$i]["Permintaan"],
					'Tahun' => $query[$i]["Tahun"],
					'Per3Bulan' => NULL,
					'kesalahan' => NULL,
					'absolut' => NULL,
					'kuadrat' => NULL,
					'Per5Bulan' => Null,
					'kesalahan5' => Null,
					'absolut5' =>Null,
					'kuadrat5' => Null,
				);  
			}
			
			
		}
		$this->data['rowDatas']		= $data['singleMoving'];
		$myJSON = json_encode($data['singleMoving']);
		$sum=0;
		$count=0;
		foreach($data['singleMoving'] as $num => $values) {
			$sum += $values['kuadrat5']/2;
		}
		//peramalan

		// Build Pagination
		// var_dump($myJSON);
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
		
		$this->data['content'] = $this->load->view('peramalan/index',$this->data, true );
		
    	$this->load->view('layouts/main', $this->data );
    
	  
	}

	function find() {
		
		if( isset( $_POST['user_name'] ) )
{
	$name = $_POST['user_name'];
	$date = $_POST['date'];
	$query = $this->db->query("SELECT SUM(dp_produk.qty) Permintaan, produk.nama_produk Produk, DATE_FORMAT(dp_produk.tgl_pemesanan,'%M %Y') Tahun FROM tb_detail_pemesan_produk dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk where  dp_produk.tgl_pemesanan BETWEEN ('$date' - INTERVAL '7' MONTH) AND '$date' AND dp_produk.id_produk='$name' AND produk.id_produk='$name' GROUP BY dp_produk.id_produk, dp_produk.tgl_pemesanan Order By dp_produk.tgl_pemesanan ASC")->result_array();
	$rowsss = $this->db->query("SELECT SUM(dp_produk.qty) Permintaan, produk.nama_produk Produk, DATE_FORMAT(dp_produk.tgl_pemesanan,'%M %Y') Tahun FROM tb_detail_pemesan_produk dp_produk INNER JOIN tb_produk produk ON dp_produk.id_produk= produk.id_produk where  dp_produk.tgl_pemesanan BETWEEN ('$date' - INTERVAL '7' MONTH) AND '$date' AND dp_produk.id_produk='$name' AND produk.id_produk='$name' GROUP BY dp_produk.id_produk, dp_produk.tgl_pemesanan Order By dp_produk.tgl_pemesanan ASC")->num_rows();
		
		// Build pagination setting
		#$pagination = Paginator::make($results['rows'], $results['total'],$params['limit']);		
		$ii=0;
		$iii=0;
		for ($i=0; $i < $rowsss ; $i++) { 
			if ($i >= 3) {
				if ($i >= 5) {
						$data1[$i] =  array(
							'Produk' => $query[$i]["Produk"],
							'Permintaan' => $query[$i]["Permintaan"],
							'Tahun' => $query[$i]["Tahun"],
							'Per3Bulan' => round(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3,1),
							'kesalahan' =>  round($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3),1),
							'absolut' =>  round(abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),1),
							'kuadrat' =>  round(pow(round(abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),1),2),1),
		
							'Per5Bulan' =>  round(($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3,1),
							'kesalahan5' =>  round($query[$i]["Permintaan"]-(($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3),1),
							'absolut5' =>  round(abs($query[$i]["Permintaan"]-(($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3)),1),
							'kuadrat5' =>  round(pow(round(abs($query[$i]["Permintaan"]-(($query[0+$iii]["Permintaan"]+$query[1+$iii]["Permintaan"]+$query[2+$iii]["Permintaan"]+$query[3+$iii]["Permintaan"]+$query[4+$iii]["Permintaan"])/3)),1),2),1),
						); 
						$iii++;
				} else {
					$data1[$i] =  array(
						'Produk' => $query[$i]["Produk"],
						'Permintaan' => $query[$i]["Permintaan"],
						'Tahun' => $query[$i]["Tahun"],
						'Per3Bulan' =>  round(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3,1),
						'kesalahan' =>  round($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3),1),
						'absolut' =>  round(abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),1),
						'kuadrat' =>  round(pow(round(abs($query[$i]["Permintaan"]-(($query[0+$ii]["Permintaan"]+$query[1+$ii]["Permintaan"]+$query[2+$ii]["Permintaan"])/3)),1),2),1),
						'Per5Bulan' => Null,
						'kesalahan5' => Null,
						'absolut5' =>Null,
						'kuadrat5' => Null,
					); 
				}
				$ii++;
			} else{
				$data1[$i] =  array(
					'Produk' => $query[$i]["Produk"],
					'Permintaan' => $query[$i]["Permintaan"],
					'Tahun' => $query[$i]["Tahun"],
					'Per3Bulan' => NULL,
					'kesalahan' => NULL,
					'absolut' => NULL,
					'kuadrat' => NULL,
					'Per5Bulan' => Null,
					'kesalahan5' => Null,
					'absolut5' =>Null,
					'kuadrat5' => Null,
				);  
			}
			
			
		}
		$myJSON = json_encode($data1);
		$sum5=0;
		$sum3=0;
		$count=0;
		foreach($data1 as $num => $values) {
			$sum5 += $values['kuadrat5']/2;
		}
		foreach($data1 as $num => $values) {
			$sum3 += $values['kuadrat']/4;
		}
		echo '<p>Perhitungan MSE</p>
		<table id="example2" class="table table-bordered table-hover">
			<thead>
			<tr>
				<th>Bulan</th>
				<th>Jumlah Pesanan</th>
				<th>Per-3 Bulan</th>
				<th>Kesalahan</th>
				<th>Nilai Absolut</th>
				<th>Kuadrat</th>
				<th>Per-5 Bulan</th>
				<th>Kesalahan</th>
				<th>Nilai Absolut</th>
				<th>Kuadrat</th>
			</tr>
			</thead>
		</tbody>';
		  foreach($data1 as $num => $values) {
           	 echo  '<tr>';
             echo  '<td>';echo $values["Tahun"];echo'</td>';
             echo  '<td>'; echo $values['Permintaan'];echo'</td>';
			 echo  '<td>'; echo $values['Per3Bulan'];echo'</td>';
			 echo  '<td>';echo $values['kesalahan'];echo'</td>';
             echo  '<td>';echo $values['absolut'];echo'</td>';          
             echo  '<td>'; echo $values['kuadrat'];echo'</td>';           
			 echo  '<td>'; echo $values['Per5Bulan'];echo'</td>';
			echo  '<td>'; echo $values['kesalahan5'];echo'</td>';
			echo  '<td>'; echo $values['absolut5'];echo'</td>';            
			echo  '<td>'; echo $values['kuadrat5'];echo'</td>';   
           echo   '</tr>';
             }
			 echo  '</tbody>';
			 echo  '<tr>';
             echo  '<td>';echo'</td>';
             echo  '<td>';echo'</td>';
			 echo  '<td>';echo $sum3;echo'</td>';
			 echo  '<td>';echo'</td>';
             echo  '<td>';echo'</td>';          
             echo  '<td>';echo'</td>';           
			 echo  '<td>';echo'</td>';
			echo  '<td>';echo'</td>';
			echo  '<td>';echo'</td>';            
			echo  '<td>'; echo $sum5;echo'</td>';   
           echo   '</tr>';
			 echo  '</table>';
	;

	$querySef = $this->db->query("SELECT bahan.sisa sisa,bahan.satuan_bahan_baku satuan,bahan.id_bahan_baku id,bahan.pembagi pembagi,bahan.nama_bahan_baku as nama_bahan_baku, bom.jumlah_kebutuhan as qty FROM `tb_bom` bom INNER JOIN tb_bahan_baku bahan ON bom.id_bahan_baku= bahan.id_bahan_baku Where bom.id_produk='$name'")->result_array();
	$rowsSef = $this->db->query("SELECT bahan.sisa sisa,bahan.satuan_bahan_baku satuan,bahan.id_bahan_baku id,bahan.pembagi pembagi,bahan.nama_bahan_baku as nama_bahan_baku, bom.jumlah_kebutuhan as qty FROM `tb_bom` bom INNER JOIN tb_bahan_baku bahan ON bom.id_bahan_baku= bahan.id_bahan_baku Where bom.id_produk='$name'")->num_rows();
	
	
		for ($i=0; $i < $rowsSef ; $i++) { 
			$dataSef[$i] =  array(
				'nama_bahan_baku' => $querySef[$i]["nama_bahan_baku"],
				'sisa' => $querySef[$i]["sisa"],
				'satuan' => $querySef[$i]["satuan"],
				'perhitungan' => ($sum5*12),
				'jumlah_bahan' => $querySef[$i]["qty"],
				'jumlah_yang_harus' => (($querySef[$i]["qty"])*($sum5*12)),
			); 
		}
		
		echo '<p>Komposisi Produk Hasil Peramalan</p>
		<table id="example3" class="table table-bordered table-hover">
			<thead>
			<tr>
				<th>Nama Bahan Baku</th>
				<th>Perhitungan</th>
				<th>Jumlah Bahan</th>
				<th>Jumlah Yang Harus Dipesan</th>
			</tr>
			</thead>
		</tbody>';
		  foreach($dataSef as $num => $values) {
           	 echo  '<tr>';
             echo  '<td>';echo $values["nama_bahan_baku"];echo'</td>';
             echo  '<td>'; echo $values['perhitungan'];echo'</td>';
			 echo  '<td>'; echo $values['jumlah_bahan'];echo'</td>';
			 echo  '<td>';echo $values['jumlah_yang_harus'];echo ' ';echo $values['satuan'];echo'</td>';
           echo   '</tr>';
             }
			 echo  '</tbody>';
			 echo  '</table>';



			 //Perhitungan Safety Stock

			//  Jumlah peramalan bulan September 2017 	= 106620 pcs
				
			 $jmlHariKerja=26; //  Jumlah hari kerja dalam satu bulan 	
			 $leadTime= 2; // Lead Time pengadaan ke supplier (l) 
			 $rata= 106620/$jmlHariKerja; //Rata-rata pengadaan dalam satu bulan (d)
			 $sl= $leadTime/10; // Standar Deviasi Lead Time (sl)	
			 $sd= $rata/10; //Standar Deviasi Pemesanan (sd)	
			 $Z= 1.75; //Servis Level 96% (Z)
			  
			 $powRata= pow($rata,2);
			 $powSl= pow($sl,2);
			 $powSd= pow($sd,2);
			 $sdl= sqrt(($powRata*$powSl)+(2*$powSd));
			 $safetyStock= $Z * $sdl;
			 
		
			
			for ($i=0; $i < $rowsSef ; $i++) { 
				$dataSefFix[$i] =  array(
					'nama_bahan_baku' => $querySef[$i]["nama_bahan_baku"],
					'satuan' => $querySef[$i]["satuan"],
					'sisa' => $querySef[$i]["sisa"],
					'perhitungan' => round($safetyStock,1),
					'jumlah_bahan' => $querySef[$i]["qty"],
					'jumlah_yang_harus' =>round((($querySef[$i]["qty"])*$safetyStock)/$querySef[$i]["pembagi"],1),
				); 
			}

			 echo '<p>Safety Stock Bahan Baku</p>
			 <table id="example3" class="table table-bordered table-hover">
				 <thead>
				 <tr>
					 <th>Nama Bahan Baku</th>
					 <th>Perhitungan</th>
					 <th>Sisa Stok</th>
					 <th>Jumlah Bahan</th>
					 <th>Jumlah Yang Dibutuhkan</th>
					 <th>Status</th>
					 <th>Jumlah Yang Harus Dipesan</th>
				 </tr>
				 </thead>
			 </tbody>';
			   foreach($dataSefFix as $num => $values) {
					 echo  '<tr>';
				  echo  '<td>';echo $values["nama_bahan_baku"];echo'</td>';
				  echo  '<td>'; echo $values['perhitungan'];echo'</td>';
				  echo  '<td>'; echo $values['sisa'];echo'</td>';
				  echo  '<td>'; echo $values['jumlah_bahan'];echo'</td>';
				  if($values['sisa'] >=$values['jumlah_yang_harus']){
				       echo  '<td>';echo $values['jumlah_yang_harus'];echo ' ';echo $values['satuan'];echo'</td>';
				     echo  '<td>'; echo 'Aman';echo'</td>'; 
				     echo  '<td>';echo'</td>';
				  }else{
				      echo  '<td>';echo $values['jumlah_yang_harus'];echo ' ';echo $values['satuan'];echo'</td>';
				  echo  '<td>'; echo 'Tidak Aman';echo'</td>';
				  echo  '<td>';echo $values['jumlah_yang_harus']-$values['sisa'];echo ' ';echo $values['satuan'];echo'</td>';
				  }
				  
				echo   '</tr>';
				  }
				  echo  '</tbody>';
				  echo  '</table>';
	;
}
	}

}
