<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Profile </a></li>
    <li><a href="<?php echo site_url('kendaraan') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('kendaraan/save/'.$row['id']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 


<div class="col-md-6">
						<fieldset><legend> Master Kendaraan</legend>
									
								  <div class="form-group  " >
									<label for="Nomor Polisi" class=" control-label col-md-4 text-left"> Nomor Polisi </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['no_polisi'];?>' name='no_polisi'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Jenis Kendaraan" class=" control-label col-md-4 text-left"> Jenis Kendaraan </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['jenis_kendaraan'];?>' name='jenis_kendaraan'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Panjang Maksimal" class=" control-label col-md-4 text-left"> Panjang Maksimal </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['panjang_maksimal'];?>' name='panjang_maksimal'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Berat Maksimal" class=" control-label col-md-4 text-left"> Berat Maksimal </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['berat_maksimal'];?>' name='berat_maksimal'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Status Operasi" class=" control-label col-md-4 text-left"> Status Operasi </label>
									<div class="col-md-8">
									  
					<?php $status_operasi = explode(',',$row['status_operasi']);
					$status_operasi_opt = array( 'jalan' => 'Jalan' ,  'kosong' => 'Kosong' ,  'service' => 'Service' , ); ?>
					<select name='status_operasi' rows='5'   class='select2 '  > 
						<?php 
						foreach($status_operasi_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['status_operasi'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> </fieldset>
			</div>
			
			<div class="col-md-6">
						<fieldset><legend> .</legend>
				</fieldset>
			</div>
			
			
    
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center">    
      <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('kendaraan');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
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