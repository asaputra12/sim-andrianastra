<script>
    function createNew() {
        $("#add-more").hide();
        var data = '<tr class="table-row" id="new_row_ajax">' +
            '<td contenteditable="true" onClick="editRow(this);">' +
            '<select name="title" id="title" class="select2" onBlur="addToHiddenField(this,\'title\')><option value="0">No Required</option><?php foreach($tb_supplier as $item) { ?><option value="<?php echo $item['id_supplier'];?>"><?php echo $item['nama_supplier'];?></option><?php } ?>' +
                '</select>' +
                '</td>' +
                '<td contenteditable="true"  onClick="editRow(this);"><input type="number" name="txt_description" id="txt_description" /></td>' +
                '<td><input type="hidden" id="description" /><span id="confirmAdd"><a onClick="addToDatabase()" class="ajax-action-links">Save</a> / <a onclick="cancelAdd();" class="ajax-action-links">Cancel</a></span></td>' +
                '</tr>';
        $("#table-body").append(data);
    }
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
            url: "<?php echo site_url('bahanbaku/edit/'); ?>",
            type: "POST",
            data: 'column=' + column + '&editval=' + editableObj.value + '&id=' + id,
            success: function (data) {
                $(editableObj).css("background", "#FDFDFD");
                // setSecondDate();
            }
        });
    }
    function addToDatabase() {
        var title = $("#title").val();
        var description = $("#txt_description").val();
        var id = $("#id_bahan_baku").val();


        $("#confirmAdd").html('<img src="<?php echo base_url() ;?>sximo/images/loaderIcon.gif" />');
        $.ajax({
            url: "<?php echo site_url('bahanbaku/tambah/'); ?>",
            type: "POST",
            data: 'title=' + title + '&description=' + description + '&id=' + id,

            success: function (data) {
                $("#new_row_ajax").remove();
                $("#add-more").show();
                $("#table-body").append(data);
                // setSecondDate();
            }
        });
    }
    function addToHiddenField(addColumn, hiddenField) {
        var columnValue = $(addColumn).text();
        $("#" + hiddenField).val(columnValue);
    }
    function deleteRecord(id) {
        if (confirm("Are you sure you want to delete this row?")) {
            $.ajax({
                url: "<?php echo site_url('bahanbaku/delete/'); ?>",
                type: "POST",
                data: { id: id },
                dataType: "text",
                success: function (data) {
                    // setSecondDate();
                    $("#table-row-" + id).remove();
                }
            });
        }
    }
    function setSecondDate() {
        var id = $("#id_bahanbaku").val();
        $.ajax({
            url: "<?php echo site_url('bahanbaku/setTanggalSelesai/'); ?>",
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
        var id = $("#id_bahanbaku").val();
        $.ajax({
            url: "<?php echo site_url('bahanbaku/setDP/'); ?>",
            type: "POST",
            data: 'id=' + id,

            success: function (data) {
                document.getElementById("bayar_dp").value = data;
            }
        });
    }
    function myFunction() {
        var x = document.getElementById("tgl_bahanbaku").value;
        document.getElementById("demo").innerHTML = "You selected: " + x;

    }
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
                <a href="<?php echo site_url('bahanbaku') ?>">
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


                <form action="<?php echo site_url('bahanbaku/save/'.$row['id_bahan_baku']); ?>" class='form-horizontal' parsley-validate='true'
                    novalidate='true' method="post" enctype="multipart/form-data">


                    <div class="col-md-6">
                        <fieldset>
                            <legend> Master Bahan Baku</legend>
														<div class="form-group" hidden>
                                <div class="col-md-8">
                                    <input type='text' hidden class='form-control' placeholder='' value='<?php echo $row['id_bahan_baku'];?>' name='id_bahan_baku' id='id_bahan_baku'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Jenis Bahan Baku" class=" control-label col-md-4 text-left"> Jenis Bahan Baku </label>
                                <div class="col-md-8">
                                    <input type='text' class='form-control' placeholder='' value='<?php echo $row['jenis_bahan_baku'];?>' name='jenis_bahan_baku'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Nama Bahan Baku" class=" control-label col-md-4 text-left"> Nama Bahan Baku </label>
                                <div class="col-md-8">
                                    <input type='text' class='form-control' placeholder='' value='<?php echo $row['nama_bahan_baku'];?>' name='nama_bahan_baku'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  " hidden>
                                <label for="Nama Supplier" class=" control-label col-md-4 text-left"> Nama Supplier </label>
                                <div class="col-md-8">
                                    <select name='id_supplier' rows='5' id='id_supplier' code='{$id_supplier}' class='select2 '></select>
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            
                            <div class="form-group  " hidden>
                                <label for="Harga Bahan Baku" class=" control-label col-md-4 text-left"> Harga Bahan Baku </label>
                                <div class="col-md-8">
                                    <input type='text' class='form-control' placeholder='' value='<?php echo $row['harga_bahan_baku'];?>' name='harga_bahan_baku'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
														</div>
														<div class="form-group  ">
                                <label for="Satuan Bahan Baku" class=" control-label col-md-4 text-left"> Satuan Bahan Baku </label>
                                <div class="col-md-8">
                                    <input type='text' class='form-control' placeholder='' value='<?php echo $row['satuan_bahan_baku'];?>' name='satuan_bahan_baku'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset>
                            <legend>Daftar Supplier Penyedia Bahan Baku</legend>
                            <div class="form-group  " hidden>
                                <label for="Ukuran" class=" control-label col-md-4 text-left"> Ukuran </label>
                                <div class="col-md-8">
                                    <input type='text' class='form-control' placeholder='' value='<?php echo $row['ukuran'];?>' name='ukuran' />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  " hidden>
                                <label for="Keterangan" class=" control-label col-md-4 text-left"> Keterangan </label>
                                <div class="col-md-8">
                                    <textarea name='keterangan' rows='2' id='keterangan' class='form-control '><?php echo $row['keterangan'] ;?></textarea>
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
														</div>
														<div class="form-group  ">
                                <div class="col-md-12">
                                    <div class="btn btn-info btn-sm" id="add-more" onClick="createNew();">Tambah</div>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="table-header">Supplier</th>
                                                <th class="table-header">Harga Bahan Baku</th>
                                                <th class="table-header">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <?php if(!empty($rows)) {  ?>
                                            <?php	foreach($rows as $k=>$v) { ?>
                                            <tr class="table-row" id="table-row-<?php echo $rows[$k]["id_detail_bahan_baku"]; ?>">
                                                <td contenteditable="true" onClick="editRow(this);">
                                                    <select name="required[<?php echo $id;?>]" id="required" class="form-control" style="width:150px;" onBlur="saveToDatabase(this,'id_supplier','<?php echo $rows[$k]["id_detail_bahan_baku"]; ?>')">
                                                        <?php foreach($tb_supplier as $item) { ?>
                                                        <option value="<?php echo $item['id_supplier'];?>" <?php if($rows[$k]["id_supplier"]==$item[ 'id_supplier']) echo
                                                            'selected="selected"';?>>
                                                            <?php echo $item['nama_supplier'];?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td contenteditable="true">
                                                    <input type="number" min="6" onBlur="saveToDatabase(this,'Harga','<?php echo $rows[$k]["id_detail_bahan_baku"]; ?>')" onClick="editRow(this);"
                                                        value="<?php echo $rows[$k]["Harga"]; ?>" />
                                                </td>
                                                <td>
                                                    <a class="ajax-action-links" onclick="deleteRecord(<?php echo $rows[$k]["id_detail_bahan_baku"]; ?>);">Delete</a>
                                                </td>
                                            </tr>
                                            <?php }
                                            }?>
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
                        <a href="<?php echo site_url('bahanbaku');?>" class="btn btn-sm btn-warning">
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

        $("#id_supplier").jCombo("<?php echo site_url('bahanbaku/comboselect?filter=tb_supplier:id_supplier:nama_supplier') ?>",
            { selected_value: '<?php echo $row["id_supplier"] ?>' });

    });
</script>