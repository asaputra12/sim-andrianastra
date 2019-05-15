<div class="page-content row">
  <!-- Page header -->
  <div class="page-header">
    <div class="page-title">
      <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
    </div>
    <ul class="breadcrumb">
      <li><a href="<?php echo site_url('dashboard') ?>">Profile</a></li>
      <li><a href="<?php echo site_url('pengadaan') ?>"><?php echo $pageTitle ?></a></li>
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
						<td width='30%' class='label-view text-right'>Nama Bahan Baku</td>
						<td><?php echo SiteHelpers::gridDisplayView($row['id_bahan_baku'],'id_bahan_baku','1:tb_bahan_baku:id_bahan_baku:jenis_bahan_baku|nama_bahan_baku') ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Nama Supplier</td>
						<td><?php echo SiteHelpers::gridDisplayView($row['id_supplier'],'id_supplier','1:tb_supplier:id_supplier:nama_supplier') ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Jumlah Pengadaan</td>
						<td><?php echo $row['jumlah_pengadaan'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Tanggal Pembelian</td>
						<td><?php echo $row['tanggal_pembelian'] ;?> </td>
						
					</tr>
				
            </tbody>  
          </table>    
        </div>
      </div>
    </div>
  </div>
  
</div>
    