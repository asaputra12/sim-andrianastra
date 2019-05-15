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
		
	var_dump($tb_bahan_baku);

	?>
		<select name="required[<?php echo $id;?>]" id="required" class="form-control" style="width:150px;" >
			<option value="0">No Required</option>
			<?php foreach($tb_bahan_baku as $item) { ?>
				<option value="<?php echo $item['id_bahan_baku'];?>"><?php echo $item['id_bahan_baku'];?></option>
			<?php } ?>
		</select> -->
<script>
function createNew() {
	$("#add-more").hide();
	var data = '<tr class="table-row" id="new_row_ajax">' +
	'<td contenteditable="true" onClick="editRow(this);">'+
	'<select name="title" id="title" class="select2" onBlur="addToHiddenField(this,\'title\')><option value="0">No Required</option><?php foreach($tb_bahan_baku as $item) { ?><option value="<?php echo $item['id_bahan_baku'];?>"><?php echo $item['nama_bahan_baku'];?></option><?php } ?>'+
	'</select>'+
	'</td>' +
	'<td contenteditable="true" id="txt_description" onBlur="addToHiddenField(this,\'description\')" onClick="editRow(this);"></td>' +
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
  $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
  $.ajax({
    url: "<?php echo site_url('pengadaan/edit/'); ?>",
    type: "POST",
    data:'column='+column+'&editval='+editableObj.value+'&id='+id,
    success: function(data){
      $(editableObj).css("background","#FDFDFD");
    }
  });
}
function addToDatabase() {
  var title = $("#title").val();
  var description = $("#description").val();
  var id = $("#id_pengadaan").val();
  
	  $("#confirmAdd").html('<img src="<?php echo base_url() ;?>sximo/images/loaderIcon.gif" />');
	  $.ajax({
		url: "<?php echo site_url('pengadaan/tambah/'); ?>",
		type: "POST",
		data:'title='+title+'&description='+description+'&id='+id,
		success: function(data){
		  $("#new_row_ajax").remove();
		  $("#add-more").show();		  
		  $("#table-body").append(data);
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
			url: "<?php echo site_url('pengadaan/delete/'); ?>",
			type: "POST",
			data: {id: id},
      dataType: "text",
			success: function(data){
			  $("#table-row-"+id).remove();
			}
		});
	}
}

function myFunction() {
    var x = document.getElementById("id_supplier").value;

		 $.ajax({
			url: "<?php echo site_url('pengadaan/getBahanBaku/'); ?>",
			type: "POST",
			data:'idSupplier='+x,
	  });
}
</script>
<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Profile </a></li>
    <li><a href="<?php echo site_url('pengadaan') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('pengadaan/save/'.$row['id_pengadaan']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 


<div class="col-md-12">
						<fieldset><legend> Pengadaan</legend>
						<div class="form-group  " >
									<label for="Id Pengadaan" class=" control-label col-md-4 text-left"> Id Pengadaan </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['id_pengadaan'];?>' name='id_pengadaan'   id='id_pengadaan' readonly/> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Nama Supplier" class=" control-label col-md-4 text-left"> Nama Supplier </label>
									<div class="col-md-8">
									  <select name='id_supplier' rows='5' id='id_supplier' code='{$id_supplier}' 
							class='select2 ' onchange="myFunction()"   ></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 				
								  <div class="form-group  " >
									<label for="Tanggal Pembelian" class=" control-label col-md-4 text-left"> Tanggal Pembelian </label>
									<div class="col-md-8">
									  
				<input type='text' class='form-control date' placeholder='' value='<?php echo $row['tanggal_pembelian'];?>' name='tanggal_pembelian'
				style='width:150px !important;'	   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div>		

<div class="form-group  " >
									<label for="Daftar Bahan Baku" class=" control-label col-md-4 text-left"> Daftar Bahan Baku </label>
									<div class="col-md-8">
<div class="btn btn-info btn-sm" id="add-more" onClick="createNew();">Tambah</div>

<table class="table table-striped table-bordered">
  <thead>
	<tr>
	  <th class="table-header">Bahan Baku</th>
	  <th class="table-header">Jumlah Kebutuhan</th>
	  <th class="table-header">Actions</th>
	</tr>
  </thead>
  <tbody id="table-body">
	
	<?php
	if(!empty($posts)) { 
	foreach($posts as $k=>$v) {
	  ?>
		

	  <tr class="table-row" id="table-row-<?php echo $posts[$k]["id_detail_bahan_baku"]; ?>">
		<td contenteditable="true" onClick="editRow(this);">
		<select name="required[<?php echo $id;?>]" id="required" class="form-control" style="width:150px;" onBlur="saveToDatabase(this,'id_bahan_baku','<?php echo $posts[$k]["id_detail_bahan_baku"]; ?>')">
			<!-- <option value="0">No Required</option> -->
			<?php foreach($tb_bahan_baku as $item) { ?>
				<option value="<?php echo $item['id_bahan_baku'];?>" <?php if($posts[$k]["id_bahan_baku"] == $item['id_bahan_baku']) echo 'selected="selected"';?>><?php echo $item['jenis_bahan_baku'];?> <?php echo $item['nama_bahan_baku'];?></option>
			<?php } ?>
		</select>
		</td>
		<td contenteditable="true" >
		<input type="text" onBlur="saveToDatabase(this,'jumlah_kebutuhan','<?php echo $posts[$k]["id_detail_bahan_baku"]; ?>')" onClick="editRow(this);" value="<?php echo $posts[$k]["jumlah_kebutuhan"]; ?>" />
		</td>
		<td><a class="ajax-action-links" onclick="deleteRecord(<?php echo $posts[$k]["id_detail_bahan_baku"]; ?>);">Delete</a></td>
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
									<label for="Jumlah Pengadaan" class=" control-label col-md-4 text-left"> Jumlah Pengadaan </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['jumlah_pengadaan'];?>' name='jumlah_pengadaan'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 				
									 </fieldset>
			</div>
			
			
    
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center">    
      <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('pengadaan');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
     </div>
            
    </form>
    
    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 

		$("#id_bahan_baku").jCombo("<?php echo site_url('pengadaan/comboselect?filter=tb_bahan_baku:id_bahan_baku:jenis_bahan_baku|nama_bahan_baku') ?>",
		{  selected_value : '<?php echo $row["id_bahan_baku"] ?>' });
		
		$("#id_supplier").jCombo("<?php echo site_url('pengadaan/comboselect?filter=tb_supplier:id_supplier:nama_supplier') ?>",
		{  selected_value : '<?php echo $row["id_supplier"] ?>' });
		    
});
</script>     