<!-- <?php
		$reqType = array(
			'required'			=> 'Required',
			'alpa'				=> 'Required Only Alpha ',
			'numeric'			=> 'Required Only Number',	
			'alpa_num'			=> 'Required Alpha & Numeric ',			
			'email'				=> 'Required Email',
			'url'				=> 'Required Url',
			'date'				=> 'Required Date',
					
		);
		
	var_dump($tb_produk);

	?>
		<select name="required[<?php echo $id;?>]" id="required" class="form-control" style="width:150px;" >
			<option value="0">No Retquired</option>
			<?php foreach($tb_produk as $item) { ?>
				<option value="<?php echo $item['id_produk'];?>"><?php echo $item['id_produk'];?></option>
			<?php } ?>
		</select> -->
<script>
function createNew() {
	$("#add-more").hide();
	var data = '<tr class="table-row" id="new_row_ajax">' +
	'<td contenteditable="true" onClick="editRow(this);">'+
	'<select name="title" id="title" class="select2" onBlur="addToHiddenField(this,\'title\')><option value="0">No Required</option><?php foreach($tb_produk as $item) { ?><option value="<?php echo $item['id_produk'];?>"><?php echo $item['nama_produk'];?></option><?php } ?>'+
	'</select>'+
	'</td>' +
	'<td contenteditable="true"  onClick="editRow(this);"><input type="number" name="txt_description" min="6" value="6" id="txt_description" />&nbsp Pcs</td>' +
	'<td><input type="hidden" id="description" /><span id="confirmAdd"><a onClick="addToDatabase()" class="ajax-action-links">Save</a> / <a onclick="cancelAdd();" class="ajax-action-links">Cancel</a></span></td>' +	
	'</tr>';
  $("#table-body").append(data);
}
function cancelAdd() {
	$("#add-more").show();
	$("#new_row_ajax").remove();
}
function editRow(editableObj) {
  $(editableObj).css("background","#FFF");
}

function saveToDatabase(editableObj,column,id) {
	console.log('data');
  $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
  $.ajax({
    url: "<?php echo site_url('pemesanan/edit/'); ?>",
    type: "POST",
    data:'column='+column+'&editval='+editableObj.value+'&id='+id,
    success: function(data){
      $(editableObj).css("background","#FDFDFD");
			setSecondDate();
    }
  });
}
function addToDatabase() {
  var title = $("#title").val();
  var description = $("#txt_description").val();
  var id = $("#id_pemesanan").val();
  var tgl = $("#tgl_pemesanan").val();

	console.log(tgl);
  
	  $("#confirmAdd").html('<img src="<?php echo base_url() ;?>sximo/images/loaderIcon.gif" />');
	  $.ajax({
		url: "<?php echo site_url('pemesanan/tambah/'); ?>",
		type: "POST",
		data:'title='+title+'&description='+description+'&id='+id+'&tgl='+tgl,
		
		success: function(data){
		  $("#new_row_ajax").remove();
		  $("#add-more").show();		  
		  $("#table-body").append(data);
			setSecondDate();
		}
	  });
}
function addToHiddenField(addColumn,hiddenField) {
	var columnValue = $(addColumn).text();
	$("#"+hiddenField).val(columnValue);
}

function deleteRecord(id) {
	if(confirm("Are you sure you want to delete this row?")) {
		$.ajax({
			url: "<?php echo site_url('pemesanan/delete/'); ?>",
			type: "POST",
			data: {id: id},
      dataType: "text",
			success: function(data){
				// setSecondDate();
			  $("#table-row-"+id).remove();
			}
		});
	}
}
function setSecondDate() {
  var id = $("#id_pemesanan").val();
	$.ajax({
		url: "<?php echo site_url('pemesanan/setTanggalSelesai/'); ?>",
		type: "POST",
		data:'id='+id,
		
		success: function(data){
			console.log(data);
		
			document.getElementById("tgl_selesai").value = data;
			setDP();
		}
	});
}
function setDP() {
  var id = $("#id_pemesanan").val();
	$.ajax({
		url: "<?php echo site_url('pemesanan/setDP/'); ?>",
		type: "POST",
		data:'id='+id,
		
		success: function(data){
			var obj = JSON.parse(data);
			document.getElementById("bayar_dp").value = obj.dp;
			document.getElementById("total_bayar").value = obj.total;
		}
	});
}
function myFunction() {
    var x = document.getElementById("tgl_pemesanan").value;
    document.getElementById("demo").innerHTML = "You selected: " + x;

}
</script>
<?php $datetime = new DateTime(); ?>
<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Profile </a></li>
    <li><a href="<?php echo site_url('pemesanan') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('pemesanan/save/'.$row['id_pemesanan']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 


<div class="col-md-12">
						<fieldset><legend> pemesanan</legend>
						<p id="demo"></p>

						<div class="form-group  " >
									<label for="Id Pemesanan" class=" control-label col-md-4 text-left"> Id Pemesanan </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['id_pemesanan'];?>' name='id_pemesanan'   id='id_pemesanan' readonly/> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 	

									<div class="form-group  " >
									<label for="Nama Pelanggan" class=" control-label col-md-4 text-left"> Nama Pelanggan </label>
									<div class="col-md-8">

											<select name='id_user'  class='select2'  >
												<option value="" style="text-align:center;" >--Pilih Konsumen--</option>
												<?php foreach($tb_konsumen as $item) { ?>
													<option value="<?php echo $item['id'];?>" <?php if($row['id_user'] == $item['id']) echo 'selected="selected"';?>><?php echo $item['first_name'];?></option>
												<?php } ?>
											</select>

									  <!-- <input type='text' class='form-control' placeholder='' value='<?php echo $row['nama_pelanggan'];?>' name='nama_pelanggan'   /> <br /> -->
									  <i> <small></small></i>
									 </div> 
								  </div>	
								  <div class="form-group  " >
									<label for="Tanggal Pemesanan" class=" control-label col-md-4 text-left"> Tanggal Pemesanan </label>
									<div class="col-md-8">
									  
				<input type='text' class='form-control' placeholder='' value='<?php if($row['tgl_pemesanan'] == null){ echo $datetime->format('Y-m-d');}else{
				    echo $row['tgl_pemesanan'];
				}?>'  name='tgl_pemesanan' id='tgl_pemesanan'
				style='width:150px !important;'	   onchange="setSecondDate();" readonly/> <br />
									  <i> <small></small></i>
									 </div> 
									 
								  </div> 
									<div class="form-group  " >
									<label for="Daftar Bahan Baku" class=" control-label col-md-4 text-left"> Daftar Produk </label>
									<div class="col-md-8">
<div class="btn btn-info btn-sm" id="add-more" onClick="createNew();">Tambah</div>

<table class="table table-striped table-bordered">
  <thead>
	<tr>
	  <th class="table-header">Produk</th>
	  <th class="table-header">Jumlah Pemesanan</th>
	  <th class="table-header">Actions</th>
	</tr>
  </thead>
  <tbody id="table-body">
	
	<?php

	if(!empty($posts)) { 
		foreach($posts as $k=>$v) {
			// $lama=$lamaProduksi[$k]["lama"]+2;
		}?>
		<input type='text' readonly  name='lama' id='lama' value='<?php echo $lama;?>' hidden/>
<?php	foreach($posts as $k=>$v) {
	  ?>
		

	  <tr class="table-row" id="table-row-<?php echo $posts[$k]["id_detail_pemesan_produk"]; ?>">
		<td contenteditable="true" onClick="editRow(this);">
		<select name="required[<?php echo $id;?>]" id="required" class="form-control" style="width:150px;" onBlur="saveToDatabase(this,'id_produk','<?php echo $posts[$k]["id_detail_pemesan_produk"]; ?>')">
			<!-- <option value="0">No Required</option> -->
			<?php foreach($tb_produk as $item) { ?>
				<option value="<?php echo $item['id_produk'];?>" <?php if($posts[$k]["id_produk"] == $item['id_produk']) echo 'selected="selected"';?>><?php echo $item['nama_produk'];?></option>
			<?php } ?>
		</select>
		</td>
		<td contenteditable="true" >
		<input type="number" min="6" onBlur="saveToDatabase(this,'qty','<?php echo $posts[$k]["id_detail_pemesan_produk"]; ?>')" onClick="editRow(this);" value="<?php echo $posts[$k]["qty"]; ?>" />&nbsp Pcs</td>
		<td><a class="ajax-action-links" onclick="deleteRecord(<?php echo $posts[$k]["id_detail_pemesan_produk"]; ?>);">Delete</a></td>
	  </tr>
	  <?php
	}
	}
	?>
  </tbody>
</table>
</div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Bayar Dp" class=" control-label col-md-4 text-left"> Bayar Dp </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['bayar_dp'];?>' name='bayar_dp'  id='bayar_dp' /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Status Pemesanan" class=" control-label col-md-4 text-left"> Status Pemesanan </label>
									<div class="col-md-8">
									  
					<?php $status_pemesanan = explode(',',$row['status_pemesanan']);
					$status_pemesanan_opt = array( 'tunggu' => 'Tunggu' ,  'proses' => 'Proses' ,  'selesai' => 'Selesai' ,  'batal' => 'Batal' ,  'retur' => 'Retur' ,  'Dikirim' => 'Dikirim', ); ?>
					<select name='status_pemesanan' rows='5'   class='select2 '  > 
						<?php 
						foreach($status_pemesanan_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['status_pemesanan'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Total Bayar" class=" control-label col-md-4 text-left"> Total Bayar </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['total_bayar'];?>' name='total_bayar'  id='total_bayar' /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 	
									<div class="form-group  " >
									<label for="Tipe Pengiriman" class=" control-label col-md-4 text-left"> Jenis Pengiriman </label>
									<div class="col-md-8">
									  
					<?php $tipePengiriman = explode(',',$row['tipePengiriman']);
					$tipePengiriman_opt = array( 'jasa' => 'Jasa Pengiriman' ,  'diantar' => 'Diantar Perusahaan' ,  'diambil' => 'Diambil Sendiri' ,   ); ?>
					<select name='tipePengiriman' rows='5'   class='select2 '  > 
					<option value=''>-- Tipe Pengiriman --</option>
						<?php 
						foreach($tipePengiriman_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['tipePengiriman'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 				
								   </fieldset>
			</div>
			
			
    <div class="form-group  " >
									<label for="Tanggal Pemesanan" class=" control-label col-md-4 text-left"> Tanggal Perkiraan Selesai </label>
									<div class="col-md-8">
									  
				<input type='text' readonly class='form-control' name='tgl_selesai' id='tgl_selesai'/> <br />
									  <i> <small></small></i>
									 </div> 
									 
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center">    
      <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('pemesanan');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
     </div>
            
    </form>
    
    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 
    
});
</script>     