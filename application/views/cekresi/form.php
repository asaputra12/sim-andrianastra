
<script>
function myFunction() {
    var x = document.getElementById("resi").value;
		$.ajax({
		url: "<?php echo site_url('cekresi/cek/'); ?>",
		type: "POST",
		data: 'noresi=' + x,

		success: function (data) {
			$("#data").append(data);
	}
	
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
    <li><a href="<?php echo site_url('cekresi') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('cekresi/save/'.$row['id']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 


<div class="col-md-12">
						<fieldset><legend> Pengecekan Pengiriman</legend>
									
								  <div class="form-group  " hidden>
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['id'];?>' name='id'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 	
									<div class="form-group  " >
									<label for="Id Pengiriman" class=" control-label col-md-2 text-left"> Cek No Resi </label>
									<div class="col-md-8">
									<input type='text' class='form-control' placeholder='' value='<?php echo $row['id'];?>' name='resi' id='resi'  /> <br />
									 </div> 
									 <div class="col-md-1">
									<a onClick="myFunction()" class="ajax-action-links">Cek</a><br />
									 </div>
								  </div>	
									<div id="data" >
								  </div>				
								  <div class="form-group  " hidden>
									<label for="Id Pengiriman" class=" control-label col-md-4 text-left"> Id Pengiriman </label>
									<div class="col-md-8">
									  <select name='id_pengiriman' rows='5' id='id_pengiriman' code='{$id_pengiriman}' 
							class='select2 '    ></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " hidden>
									<label for="Id Pemesanan" class=" control-label col-md-4 text-left"> Id Pemesanan </label>
									<div class="col-md-8">
									  <select name='id_pemesanan' rows='5' id='id_pemesanan' code='{$id_pemesanan}' 
							class='select2 '    ></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " hidden>
									<label for="Id Produk" class=" control-label col-md-4 text-left"> Id Produk </label>
									<div class="col-md-8">
									  <select name='id_produk' rows='5' id='id_produk' code='{$id_produk}' 
							class='select2 '    ></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " hidden>
									<label for="Status" class=" control-label col-md-4 text-left"> Status </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['status'];?>' name='status'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " hidden>
									<label for="NoResi" class=" control-label col-md-4 text-left"> NoResi </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['noResi'];?>' name='noResi'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> </fieldset>
			</div>
			
			
    
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center" hidden>    
      <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('cekresi');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
     </div>
            
    </form>
    
    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 

		$("#id_pengiriman").jCombo("<?php echo site_url('cekresi/comboselect?filter=tb_pengiriman:id_pengiriman:id_pengiriman') ?>",
		{  selected_value : '<?php echo $row["id_pengiriman"] ?>' });
		
		$("#id_pemesanan").jCombo("<?php echo site_url('cekresi/comboselect?filter=tb_pemesanan_produk:id_pemesanan:id_pemesanan') ?>",
		{  selected_value : '<?php echo $row["id_pemesanan"] ?>' });
		
		$("#id_produk").jCombo("<?php echo site_url('cekresi/comboselect?filter=tb_produk:id_produk:id_produk') ?>",
		{  selected_value : '<?php echo $row["id_produk"] ?>' });
		    
});
</script>     