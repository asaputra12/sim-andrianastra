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
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id User" class=" control-label col-md-4 text-left"> Id User </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['id_user'];?>' name='id_user'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Tanggal Pemesanan" class=" control-label col-md-4 text-left"> Tanggal Pemesanan </label>
									<div class="col-md-8">
									  
				<input type='text' class='form-control date' placeholder='' value='<?php echo $row['tgl_pemesanan'];?>' name='tgl_pemesanan'
				style='width:150px !important;'	   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Bayar Dp" class=" control-label col-md-4 text-left"> Bayar Dp </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['bayar_dp'];?>' name='bayar_dp'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Status Pemesanan" class=" control-label col-md-4 text-left"> Status Pemesanan </label>
									<div class="col-md-8">
									  
					<?php $status_pemesanan = explode(',',$row['status_pemesanan']);
					$status_pemesanan_opt = array( 'tunggu' => 'Tunggu' ,  'proses' => 'Proses' ,  'selesai' => 'Selesai' ,  'batal' => 'Batal' ,  'retur' => 'Retur' , ); ?>
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
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['total_bayar'];?>' name='total_bayar'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Tgl Selesai" class=" control-label col-md-4 text-left"> Tgl Selesai </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['tgl_selesai'];?>' name='tgl_selesai'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> </fieldset>
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