<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Profile </a></li>
    <li><a href="<?php echo site_url('produk') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('produk/save/'.$row['id_produk']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 


<div class="col-md-6">
						<fieldset><legend> produk</legend>
									
								  <div class="form-group  " >
									<label for="Kode Produk" class=" control-label col-md-4 text-left"> Kode Produk </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['kode_produk'];?>' name='kode_produk'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Nama Produk" class=" control-label col-md-4 text-left"> Nama Produk </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['nama_produk'];?>' name='nama_produk'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Lama Produksi" class=" control-label col-md-4 text-left"> Lama Produksi </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['lama_produksi'];?>' name='lama_produksi'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Berat" class=" control-label col-md-4 text-left"> Berat </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['berat'];?>' name='berat'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Keterangan" class=" control-label col-md-4 text-left"> Keterangan </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['keterangan'];?>' name='keterangan'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> </fieldset>
			</div>
			
			<div class="col-md-6">
						<fieldset><legend> produk</legend>
									
								  <div class="form-group  " >
									<label for="Kategori" class=" control-label col-md-4 text-left"> Kategori </label>
									<div class="col-md-8">
									  <select name='kategori' rows='5' id='kategori' code='{$kategori}' 
							class='select2 '    ></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Satuan Produk" class=" control-label col-md-4 text-left"> Satuan Produk </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['satuan_produk'];?>' name='satuan_produk'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Ukuran Standar" class=" control-label col-md-4 text-left"> Ukuran Standar </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['ukuran_standar'];?>' name='ukuran_standar'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Harga" class=" control-label col-md-4 text-left"> Harga </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['harga'];?>' name='harga'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group hidethis " style="display:none;">
									<label for="Harga Beli" class=" control-label col-md-4 text-left"> Harga Beli </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['harga_beli'];?>' name='harga_beli'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Pic" class=" control-label col-md-4 text-left"> Pic </label>
									<div class="col-md-8">
									  <input  type='file' name='pic' id='pic' <?php if($row['pic'] =='') echo 'class="required"' ;?> style='width:150px !important;'  />
					<?php echo SiteHelpers::showUploadedFile($row['pic'],'') ;?>
				 <br />
									  <i> <small></small></i>
									 </div> 
								  </div> </fieldset>
			</div>
			
			
    
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center">    
      <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('produk');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
     </div>
            
    </form>
    
    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 

		$("#kategori").jCombo("<?php echo site_url('produk/comboselect?filter=tb_produk_kategori:id:kategori') ?>",
		{  selected_value : '<?php echo $row["kategori"] ?>' });
		    
});
</script>     