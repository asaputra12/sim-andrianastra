<script>
	var noResi = "";
	// addToDatabase('');
		// show();
    function cancelAdd() {
        $("#add-more").show();
        $("#new_row_ajax").remove();
    }
    function editRow(editableObj) {
        $(editableObj).css("background", "#FFF");
    }
    function saveToDatabase(editableObj, column, id) {
        console.log('data');
        $(editableObj).css("background", "#FFF url(loaderIcon.gif) no-repeat right");
        $.ajax({
            url: "<?php echo site_url('bom/edit/'); ?>",
            type: "POST",
            data: 'column=' + column + '&editval=' + editableObj.value + '&id=' + id,
            success: function (data) {
                $(editableObj).css("background", "#FDFDFD");
                // setSecondDate();
            }
        });
    }

    function addToDatabase(id_pemesanan) {
        var id_pemesanan = id_pemesanan;
        var id_pengiriman = $("#id_pengiriman").val();
        var jenis_pengiriman = $("#jenis_pengiriman").val();
        var tgl_pengiriman = $("#tgl_pengiriman").val();
        var tgl_input = $("#tgl_input").val();
        var id_kendaraan = $("#id_kendaraan").val();
        var status_pengiriman = $("#status_pengiriman").val();
        var id_user = $("#id_user").val();

				resi=noResi;

				// $("#confirmAdd").html('<img src="<?php echo base_url() ;?>sximo/images/loaderIcon.gif" />');
        $.ajax({
            url: "<?php echo site_url('pengiriman/tambahPengiriman/'); ?>",
            type: "POST",
            data: 'id_pengiriman=' + id_pengiriman + '&id_pemesanan=' + id_pemesanan + '&noResi=' + resi + '&jenis_pengiriman=' + jenis_pengiriman + '&tgl_pengiriman=' + tgl_pengiriman + '&tgl_input=' + tgl_input + '&id_kendaraan=' + id_kendaraan + '&status_pengiriman=' + status_pengiriman + '&id_user=' + id_user,

            success: function (data) {
								var obj = JSON.parse(data);
								document.getElementById("id_pengiriman").value = obj.id_pengiriman;
                // // $("#new_row_ajax").remove();
								$("#table-row-" + id_pemesanan).remove();
                // // $("#add-more").show();
                $("#table-body-pengiriman").append(obj.data);
                // // // setSecondDate();
            }
        });
        
    }
    function addToHiddenField(addColumn, hiddenField) {
        var columnValue = $(addColumn).text();
        $("#" + hiddenField).val(columnValue);
    }
    function deleteRecord(id,id_pemesanan) {
        if (confirm("Are you sure you want to delete this row?")) {
            $.ajax({
                url: "<?php echo site_url('pengiriman/delete/'); ?>",
                type: "POST",
                data: { id: id, id_pemesanan: id_pemesanan},
                dataType: "text",
                success: function (data) {
              			$("#table-body").append(data);
                    $("#table-rows-" + id).remove();
                }
            });
        }
    }
    function setSecondDate() {
        var id = $("#id_pemesanan").val();
        $.ajax({
            url: "<?php echo site_url('bom/setTanggalSelesai/'); ?>",
            type: "POST",
            data: 'id=' + id,

            success: function (data) {
                console.log(data);

                document.getElementById("tgl_selesai").value = data;
                setDP();
            }
        });
    }
    function setDP() {
        var id = $("#id_pemesanan").val();
        $.ajax({
            url: "<?php echo site_url('bom/setDP/'); ?>",
            type: "POST",
            data: 'id=' + id,

            success: function (data) {
                document.getElementById("bayar_dp").value = data;
            }
        });
    }
    function setNoResi(resi) {
			noResi=resi.value;
    }
	function tipePengiriman() {
			var x = document.getElementById("jenis_pengiriman").value;
			$.ajax({
            url: "<?php echo site_url('pengiriman/selectPemesananByJenis/'); ?>",
            type: "POST",
            data: 'jenis=' + x,

            success: function (data) {
							$("#table-body").empty();
							$("#new_row_ajax").remove();
              $("#add-more").hide();
              $("#table-body").append(data);
            }
      });
    }
	// 	function show(){
	// 		var x = "<?php echo $row['id_pengiriman']; ?>";
	// 		var y = "<?php echo $row['jenis_pengiriman']; ?>";
	// 		$.ajax({
    //         url: "<?php echo site_url('pengiriman/showTable/'); ?>",
    //         type: "POST",
    //         data: 'id=' + x +'&jenis=' + y,

    //         success: function (data) {
	// 						var obj = JSON.parse(data);
    //           $("#table-body-pengiriman").append(obj.data);
    //           $("#table-body").append(obj.data1);

    //           // $("#table-body").append(data);
    //         }
    //   });
	// 	}
</script>
<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php echo $pageTitle ?>
                <small>
                    <?php echo $pageNote ?>
                </small>
            </h3>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo site_url('dashboard') ?>"> Profile </a>
            </li>
            <li>
                <a href="<?php echo site_url('pengiriman') ?>">
                    <?php echo $pageTitle ?>
                </a>
            </li>
            <li class="active"> Form </li>
        </ul>
    </div>

    <div class="page-content-wrapper m-t">
        <div class="sbox">
            <div class="sbox-title">
                <h5>
                    <?php echo $pageTitle ?>
                    <small>
                        <?php echo $pageNote ?>
                    </small>
                </h5>
            </div>
            <div class="sbox-content">


                <form action="<?php echo site_url('pengiriman/save/'.$row['id_pengiriman']); ?>" class='form-horizontal' parsley-validate='true'
                    novalidate='true' method="post" enctype="multipart/form-data">


                    <div class="col-md-4">
                        <fieldset>
                            <legend> pengiriman</legend>
														<div class="form-group" hidden>
                                <div class="col-md-8">
                                    <input type='text' class='form-control' placeholder='' value='<?php echo $row['id_pengiriman'];?>' name='id_pengiriman' id='id_pengiriman'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Jenis Pengiriman" class=" control-label col-md-4 text-left"> Jenis Pengiriman </label>
                                <div class="col-md-8">
                                    <?php $jenis_pengiriman = explode(',',$row['jenis_pengiriman']);$jenis_pengiriman_opt = array( 'jasa' => 'Jasa Pengiriman' ,  'perusahaan' => 'Perusahaan' , ); ?>
                                    <select name='jenis_pengiriman' id='jenis_pengiriman' rows='5' class='select2' onchange='tipePengiriman()'>
																				<option value=''>-- Jenis Pengiriman --</option>
																				<?php foreach($jenis_pengiriman_opt as $key=>$val){
																					echo "<option  value ='$key' ".($row['jenis_pengiriman'] == $key ? " selected='selected' " : '' ).">$val</option>";
																				}?>
																		</select>
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Tgl Pengiriman" class=" control-label col-md-4 text-left"> Tgl Pengiriman </label>
                                <div class="col-md-8">
                                    <input type='date' class='form-control' placeholder='' value='<?php echo $row['tgl_pengiriman'];?>' name='tgl_pengiriman' id='tgl_pengiriman'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Tgl Input" class=" control-label col-md-4 text-left"> Tgl Input </label>
                                <div class="col-md-8">

                                    <input type='date' class='form-control' placeholder='' value='<?php echo $row['tgl_input'];?>' name='tgl_input' id='tgl_input'
                                        style='width:150px !important;' />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Id Kendaraan" class=" control-label col-md-4 text-left"> Id Kendaraan </label>
                                <div class="col-md-8">
                                    <select name='id_kendaraan' rows='5' id='id_kendaraan' code='{$id_kendaraan}' class='select2 '></select>
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Status Pengiriman" class=" control-label col-md-4 text-left"> Status Pengiriman </label>
                                <div class="col-md-8">
                                    <input type='text' id='status_pengiriman' class='form-control' placeholder='' value='<?php echo $row['status_pengiriman'];?>' name='status_pengiriman'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  " hidden>
                                <label for="Id User" class=" control-label col-md-4 text-left"> Id User </label>
                                <div class="col-md-8">
                                    <select name='id_user' rows='5' id='id_user' code='{$id_user}' class='select2 '></select>
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-md-8">
                        <fieldset>
                            <legend>Daftar Pemesanan Produk Siap Kirim</legend>
                            <div class="form-group  ">
                                <div class="col-md-12">
                                    <!-- <div class="btn btn-info btn-sm" id="add-more" onClick="createNew();">Tambah</div> -->
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="table-header">ID Pemesanan</th>
                                                <th class="table-header">Nama Konsumen</th>
                                                <th class="table-header">Jumlah Pesanan</th>
                                                <th class="table-header">No Resi (Bila Memakai Jasa)</th>
                                                <th class="table-header">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                           
                                        </tbody>
                                    </table>
                                </div>
														</div>
														<div class="form-group  ">
                                <div class="col-md-12">
                                <label for="Status Pengiriman">List Produk Pengiriman</label>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="table-header">ID Pemesanan</th>
                                                <th class="table-header">Nama Konsumen</th>
                                                <th class="table-header">Jumlah Pesanan</th>
                                                <th class="table-header">No Resi (Bila Memakai Jasa)</th>
                                                <th class="table-header">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body-pengiriman">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div style="clear:both"></div>
                    <div class="toolbar-line text-center">
                        <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>"
                        />
                        <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>"
                        />
                        <a href="<?php echo site_url('pengiriman');?>" class="btn btn-sm btn-warning">
                            <?php echo $this->lang->line('core.btn_cancel'); ?> </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $("#id_kendaraan").jCombo("<?php echo site_url('pengiriman/comboselect?filter=tb_kendaraan:id:id') ?>",
            { selected_value: '<?php echo $row["id_kendaraan"] ?>' });

        $("#id_user").jCombo("<?php echo site_url('pengiriman/comboselect?filter=tb_users:id:id') ?>",
            { selected_value: '<?php echo $row["id_user"] ?>' });

    });
</script>