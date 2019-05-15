<script>
    function createNew() {
        $("#add-more").hide();
        var data = '<tr class="table-row" id="new_row_ajax">' +
            '<td contenteditable="true" onClick="editRow(this);">' +
            '<select name="title" id="title" class="select2" onBlur="addToHiddenField(this,\'title\')><option value="0">No Required</option><?php foreach($tb_bahan_baku as $item) { ?><option value="<?php echo $item['id_bahan_baku'];?>"><?php echo $item['nama_bahan_baku'];?>&nbsp<?php echo $item['jenis_bahan_baku'];?></option><?php } ?>' +
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
            url: "<?php echo site_url('bom/edit/'); ?>",
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
        var id = $("#id_produk").val();


        $("#confirmAdd").html('<img src="<?php echo base_url() ;?>sximo/images/loaderIcon.gif" />');
        $.ajax({
            url: "<?php echo site_url('bom/tambah/'); ?>",
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
                url: "<?php echo site_url('bom/delete/'); ?>",
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
        var id = $("#id_bom").val();
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
        var id = $("#id_bom").val();
        $.ajax({
            url: "<?php echo site_url('bom/setDP/'); ?>",
            type: "POST",
            data: 'id=' + id,

            success: function (data) {
                document.getElementById("bayar_dp").value = data;
            }
        });
    }
    function myFunction() {
        var x = document.getElementById("tgl_bom").value;
        document.getElementById("demo").innerHTML = "You selected: " + x;

    }
</script>
<?php $datetime = new DateTime(); ?>
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
                <a href="<?php echo site_url('bom') ?>">
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
                <form action="<?php echo site_url('bom/save/'.$row[0]['id_produk']); ?>" class='form-horizontal' parsley-validate='true' novalidate='true'
                    method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Komposisi (BOM)</legend>
                            <div class="form-group" hidden>
                                <div class="col-md-8">
                                    <input type='text' hidden class='form-control' placeholder='' value='<?php echo $row[0]['id_produk'];?>' name='id_produk' id='id_produk'
                                    />
                                    <br />
                                    <i>
                                        <small></small>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="Daftar Bahan Baku" class=" control-label col-md-2 text-left"> Daftar Bahan Baku </label>
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
                                            <?php if(!empty($row)) {  ?>
                                            <?php	foreach($row as $k=>$v) { ?>
                                            <tr class="table-row" id="table-row-<?php echo $row[$k]["id_bom"]; ?>">
                                                <td contenteditable="true" onClick="editRow(this);">
                                                    <select name="required[<?php echo $id;?>]" id="required" class="form-control" style="width:150px;" onBlur="saveToDatabase(this,'id_bahan_baku','<?php echo $row[$k]["id_bom"]; ?>')">
                                                        <?php foreach($tb_bahan_baku as $item) { ?>
                                                        <option value="<?php echo $item['id_bahan_baku'];?>" <?php if($row[$k][ "id_bahan_baku"]==$item[ 'id_bahan_baku']) echo 'selected="selected"';?>>
                                                          <?php echo $item['nama_bahan_baku'];?>&nbsp<?php echo $item['jenis_bahan_baku'];?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td contenteditable="true">
                                                    <input type="number" min="6" onBlur="saveToDatabase(this,'jumlah_kebutuhan','<?php echo $row[$k]["id_bom"]; ?>')" onClick="editRow(this);" value="<?php echo $row[$k]["jumlah_kebutuhan"]; ?>"
                                                    />
                                                </td>
                                                <td>
                                                    <a class="ajax-action-links" onclick="deleteRecord(<?php echo $row[$k]["id_bom"]; ?>);">Delete</a>
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
                        <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>"/>
                        <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>"/>
                        <a href="<?php echo site_url('bom');?>" class="btn btn-sm btn-warning"> <?php echo $this->lang->line('core.btn_cancel'); ?> </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () { });
</script>