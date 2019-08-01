<?php 
    // echo "<pre>";
    // print_r($categoryArray);
    // echo "</pre>";
    
    $main_category_name = $categoryArray['main_category']['UserCategory']['name'];
    $main_category_image = SITE_URL.DS.CATEGORY_IMAGE_DIR.$categoryArray['main_category']['UserCategory']['image'];
    $parent_id = $categoryArray['main_category']['UserCategory']['id'];
?>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">カテゴリ</li>
    </ol>
        </div><!--/.row-->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
            <?php $this->Layout->sessionFlash(); ?>
        </div><!--/.row-->
        <!-- Manual Expenses Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Sub Category of <?php echo $main_category_name.' '. $this->Html->image($main_category_image, array('alt' => $main_category_name, 'width'=>'20px'));;?>
                <div class="col-md-4" style="float: right;">
                    <a class="btn btn-primary exp_btn" style="float: right;" data-toggle="modal" data-target="#addSubCategory" data-id="0">サブカテゴリを追加</a>
                </div>
            </div>
            
            <div class="panel-body">
                <div class="table-responsive">
                <table id="dataTables-example1" class="table table-bordered data-table-custom Calendar-table jts_table">
                    <thead>
                    <tr>
                        <th>S. No.</th>
                        <th>Category Name</th>
                        <th>Japanese Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $i = 1;
                        foreach ($categoryArray['SubCategory'] as $record) {
                     ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $record['name'] ?></td>
                            <td><?php echo $record['japanese_name'] ?></td>
                            <td>
                                <!-- Icons  -->
                                    <a href="/user_categories/delete_category/<?php echo $parent_id?>/<?php echo $record['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete" ></a>
                                     <a href="javascript:void(0);" class="az_test" data-id="<?php echo $record['id'];?>" data-value="<?php echo $record['japanese_name'];?>" data-toggle="modal" data-target="#editSubCategory"><img src="/img/admin/pencil.png" title="Edit" alt="Edit"></a>
                            </td>
                        </tr>
                            
                    <?php }?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>S. No.</th>
                        <th>Category Name</th>
                        <th>Japanese Name</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
                </div>
        </div>

<!-- Modal to add sub category -->
<div class="modal fade" id="addSubCategory" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">サブカテゴリを追加</h4>
          <span id="msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
            <div class="panel-body">
                <form action="javascript:void(0)" id="frm_add_sub_category">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('main_category', array('div' => false, 'label' => 'メインカテゴリ', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'main_category', 'value'=>$main_category_name))); ?>
                        <?php echo ($this->Form->input('parent_id', array( 'type' => 'hidden','id'=>'parent_id', 'value'=> $parent_id))); ?>
                    </div>
                </div>   
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('sub_category_name', array('div' => false, 'label' => 'Sub Category Name', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <a id="submit" action="javascript:void(0);" class="btn btn-primary">送信</a>
                </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
        </div>
      </div>
      
    </div>
  </div>



<!-- Modal to edit sub category -->
<div class="modal fade" id="editSubCategory" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Sub Category</h4>
          <span id="edit_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
            <div class="panel-body">
                <form action="javascript:void(0)" id="frm_edit_sub_category">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('main_category', array('div' => false, 'label' => 'メインカテゴリ', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'main_category', 'value'=>$main_category_name))); ?>
                        <?php echo ($this->Form->input('edit_id', array( 'type' => 'hidden','id'=>'edit_id', 'value'=> ''))); ?>
                    </div>
                </div>   
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('sub_category_name', array('div' => false, 'label' => 'Sub Category Name', 'type'=>'text', 'id'=>'edit_sub_category_name', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <a id="edit_submit" action="javascript:void(0);" class="btn btn-primary">送信</a>
                </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
        </div>
      </div>
      
    </div>
  </div>


<script>
$(document).ready(function(){
    $('#submit').click(function(){
        var data = $( "#frm_add_sub_category" ).serialize();
        // console.log(data);
        $.ajax({
            url: "../add_sub_category",
            type: 'post',
            data: data,
            success: function(result){
                // console.log(result);
                var obj = jQuery.parseJSON( result );
                $('#msg').text(obj.msg);
                if(obj.status != 'error'){
                    window.setTimeout(function(){location.reload()},2000);
                }                
            }
        });
    });

    var category_id ;
    var sub_category_name;
    $('.az_test').on('click',function(){
        category_id = $(this).attr("data-id");;
        sub_category_name = $(this).attr("data-value");;
        // console.log(category_id);
        $('#edit_sub_category_name').val(sub_category_name);        
        $('#edit_id').val(category_id);        
    });
    $('#edit_submit').click(function(){
        var data = $( "#frm_edit_sub_category" ).serialize();
        $.ajax({
            url: "../edit_sub_category",
            type: 'post',
            data: data,
            success: function(result){
                // console.log(result);
                var obj = jQuery.parseJSON( result );
                $('#edit_msg').text(obj.msg);
                if(obj.status != 'error'){
                    window.setTimeout(function(){location.reload()},2000);
                } 
            }
        });
    });
    
});

</script>

