<?php 
    // echo "<pre>";
    // print_r($expenseData);
    // echo "</pre>";
?>

<div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">費用</li>
            </ol>
        </div><!--/.row-->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
            <?php $this->Layout->sessionFlash(); ?>
        </div><!--/.row-->
        <!-- Total Expenses Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                合計費用
                <!-- <div class="col-md-4" style="float: right;">
                    <a class="btn btn-primary exp_btn" style="float: right;" data-toggle="modal" data-target="#addManualExpense" data-id="0">Add Manual Expenses</a>
                </div>
 -->            </div>
            
            <div class="panel-body">
                <table id="dataTables-example2" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                    <tr>
                        <th>合計費用</th>
                        <th>合計予算</th>
                        <th>予算残高</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php //foreach ($expenseData['TotalExpense'] as $record) {
                     ?>
                        <tr>
                            <td><?php echo $expenseData['TotalExpense']['total_expense'] ?></td>
                            <td><?php echo $expenseData['TotalExpense']['total_budget'] ?></td>
                            <td><?php echo $expenseData['TotalExpense']['total_left_budget'] ?></td>
                        </tr>
                    <?php //}?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>合計費用</th>
                        <th>合計予算</th>
                        <th>予算残高</th>
                    </tr>
                </tfoot>
                </table>
                </div>
        </div>


        <!-- Manual Expenses Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                任意の費用
                <div class="col-md-4" style="float: right;">
                    <a class="btn btn-primary exp_btn" style="float: right;" data-toggle="modal" data-target="#addManualExpense" data-id="0">任意の費用追加</a>
                </div>
            </div>
            
            <div class="panel-body">
                <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Due Date</th>
                        <th>金額</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($expenseData['Expense'])){?>
                    <?php foreach ($expenseData['Expense'] as $record) {
                     ?>
                        <tr>
                            <td><?php echo $record['name'] ?></td>
                            <td><?php echo $record['parent_category_name'] ?></td>
                            <td><?php echo $record['date'] ?></td>
                            <td><?php echo $record['price'] ?></td>
                        </tr>
                    <?php }}?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Due Date</th>
                        <th>金額</th>
                    </tr>
                    </tfoot>
                </table>
                </div>
        </div>



<!-- Fixed Expenses Table -->
<div class="panel panel-default">
    <div class="panel-heading">
        修正費用
        <div class="col-md-4" style="float: right;">
            <a class="btn btn-primary exp_btn" style="float: right;" data-toggle="modal" data-target="#addManualExpense" data-id="1">Add fixed Expenses</a>
        </div>
    </div>
    <div class="panel-body">
        <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th>支払い方法</th>
                    <th>金額</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($expenseData['fixed_expense_list']['Expense'])){?>
                <?php foreach ($expenseData['fixed_expense_list']['Expense'] as $record) {
                 ?>
                    <tr>
                        <td><?php echo $record['name'] ?></td>
                        <td><?php echo $record['parent_category_name'] ?></td>
                        <td><?php echo $record['payment_type'] ?></td>
                        <td><?php echo $record['price'] ?></td>
                    </tr>
                <?php }}?>

                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th>支払い方法</th>
                    <th>金額</th>
                </tr>
                </tfoot>
        </table>

    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="addManualExpense" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">任意の費用追加</h4>
          <span id="msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
            <div class="panel-body">
                <form action="javascript:void(0)" id="frm_add_exp">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('main_category_id', array('options'=>$expenseData['user_category'],'div'=>false,  'label' => 'Category',  "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('category_id', array('options'=>'','div'=>false,  'label' => 'Sub Category',  "class" => "form-control")));?>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('due_date', array('div' => false, 'label' => 'Due Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'due_date'))); ?>
                    </div>
                </div>   
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('note', array('div' => false, 'label' => '注意', 'type'=>'text', 'id'=>'note', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('payment_type', array('options'=>$expenseData['payment_type'],'div'=>false,  'label' => '支払い方法',  "class" => "form-control")));?>
                    </div>                    
                </div>  
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('price', array('div' => false, 'label' => '金額', 'type'=>'text', 'id'=>'price', "class" => "form-control","maxlength"=>30))); ?>
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



<script>
$(document).ready(function(){
    // var status;
    var is_fixed;
    $('.exp_btn').on('click',function(){
        is_fixed = $(this).attr("data-id");
    });
    var category_id;
    $('.az_test').on('change',function(){
        // console.log("hello");
        category_id = $(this).val();
        // status = $(this).attr("data-ongoing-value");
        console.log(category_id);
        $.ajax({
            url: "get_sub_categories",
            type: 'post',
            data: {'id':category_id},
            success: function(result){
                console.log(result);
                $("#category_id").html(result);
            }
        });
    });
    
    $('#submit').click(function(){
        var data = $( "#frm_add_exp" ).serialize();
        if(is_fixed == 1){
            data = data + "&is_fixed=1";
        }
        $.ajax({
            url: "add_expense",
            type: 'post',
            data: data,
            success: function(result){
                console.log(result);
                $('#msg').text('Expense Added.');
                setTimeout("location.href = 'manual_expense_list'",3000);
            }
        });
    });

});

</script>

<?php 
    echo $this->Html->script(
                        array(
                            'datepicker/jquery.js',
                            'datepicker/jquery.datetimepicker.full.js'
                        ));
    echo $this->Html->css(array('datepicker/jquery.datetimepicker.css'));
?>
 <link
     rel="stylesheet"
     href="http://code.jquery.com/ui/1.9.0/themes/smoothness/jquery-ui.css" />

<script>
 jQuery(document).ready(function () {
    'use strict';
    jQuery('#due_date').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false
    });
    jQuery('#fix_due_date').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false
    });
});   
</script>