<div class="page-content row">
  <!-- Page header -->
  <div class="page-header">
    <div class="page-title">
      <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
    </div>
    <ul class="breadcrumb">
      <li><a href="<?php echo site_url('dashboard') ?>">Profile</a></li>
      <li><a href="<?php echo site_url('cekresi') ?>"><?php echo $pageTitle ?></a></li>
      <li class="active"> Detail </li>
    </ul>
  </div>  
  
   <div class="page-content-wrapper m-t">   
  
    <div class="sbox" >
      <div class="sbox-title" >
        <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
      </div>
      <div class="sbox-content" >

      <div class="table-responsive">
          <table class="table table-striped table-bordered" >
            <tbody>  
          
					<tr>
						<td width='30%' class='label-view text-right'>Id</td>
						<td><?php echo $row['id'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Id Pengiriman</td>
						<td><?php echo $row['id_pengiriman'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Id Pemesanan</td>
						<td><?php echo $row['id_pemesanan'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Id Produk</td>
						<td><?php echo $row['id_produk'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Status</td>
						<td><?php echo $row['status'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>NoResi</td>
						<td><?php echo $row['noResi'] ;?> </td>
						
					</tr>
				
            </tbody>  
          </table>    
        </div>
      </div>
    </div>
  </div>
  
</div>
    