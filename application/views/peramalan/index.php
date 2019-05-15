<head>
<script type="text/javascript">
    function loaddata()
    {
    var name=document.getElementById( "username" ).value;
    var date=document.getElementById( "date" ).value;
		console.log(name);
		console.log(date);
    if(name)
    {
    $.ajax({
            type: 'post',
            url: '<?php echo site_url('peramalan/find/'); ?>',
            data: {
            user_name:name,
            date:date,
            },
            success: function (response) {
							console.log(response);
            // We get the element having id of display_info and put the response inside it
            $( '#display_info' ).html(response);
            }
           });
    }
    else
    {
    $( '#display_info' ).html("Please Enter Some Words");
    }
    }
</script>
    </head>
    <body>
	<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="<?php echo site_url('dashboard') ?>">Profile</a></li>
        <li class="active"><?php echo $pageTitle ?></li>
      </ul>

    </div>


	<div class="page-content-wrapper m-t">
    <div class="toolbar-line ">		
		<?php
		if($this->access['is_excel'] ==1) : ?>	
		<a href="<?php echo site_url('peramalan/download') ?>" class="tips btn btn-xs btn-default" title="Download">
		<i class="fa fa-download"></i>&nbsp;Download</a>
		<?php endif;
		if($this->session->userdata('gid') ==1) : ?>	
		<!-- <a href="<?php echo site_url('sximo/module/config/peramalan') ?>" class="tips btn btn-xs btn-default"  title="Configuration">
		<i class="fa fa-cog"></i>&nbsp;Configuration</a> -->
		<?php endif; ?>		

	</div>
	 <form action='<?php echo site_url('peramalan/destroy') ?>' class='form-horizontal' id ='SximoTable' method="post" >
	 <div class="table-responsive">
    <table class="table table-striped ">
        <thead>
			<tr>
				<th> No </th>
				<?php foreach ($tableGrid as $k => $t) : ?>
					<?php if($t['view'] =='1'): ?>
						<th><?php echo $t['label'] ?></th>
					<?php endif; ?>
				<?php endforeach; ?>
			  </tr>
        </thead>

        <tbody>
			<tr >
			<?php foreach ( $rowData as $i => $row ) : ?>
                <tr>
					<td width="50"> <?php echo ($i+1+$page) ?> </td>
				 <?php foreach ( $tableGrid as $j => $field ) : ?>
					 <?php if($field['view'] =='1'): ?>
					 <td>
					 	<?php if($field['attribute']['image']['active'] =='1'): ?>
							<?php echo SiteHelpers::showUploadedFile($row->$field['field'] , $field['attribute']['image']['path'] ) ?>
						<?php else: ?>
							<?php 
							$conn = (isset($field['conn']) ? $field['conn'] : array() ) ;
							echo SiteHelpers::gridDisplay($row->$field['field'] , $field['field'] , $conn ) ?>
						<?php endif; ?>
					 </td>
					 <?php endif; ?>
				 <?php endforeach; ?>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
	</div>
	</form>

<br>

<select name="username" id="username" >
			<option value="0">Pilih Produk</option>
			<?php foreach($tb_produk as $item) { ?>
				<option value="<?php echo $item['id_produk'];?>" ><?php echo $item['nama_produk'];?></option>
			<?php } ?>
		</select>
    <input type='date'  name="date" id="date" onchange="loaddata();"/>
     <div id="display_info" >
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
            </table>
	<?php $this->load->view('footer');?>
	</div>
</div>
</body>
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#SximoTable').attr('action','<?php echo site_url("peramalan/multisearch");?>');
		$('#SximoTable').submit();
	});
	
});	
</script>