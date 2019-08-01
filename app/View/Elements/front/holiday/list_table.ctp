<?php 
// pr($customerData);die; 
?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">休日</li>
    </ol>
</div><!--/.row-->   
<?php $this->Layout->sessionFlash(); ?>     
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"></h1>
    </div>
</div><!--/.row-->
<div class="panel panel-default">
    <div class="panel-heading">
        休日        
        <div class="col-md-4" style="float: right;">
            <?php echo $this->Html->link("Add", array('controller'=>'holidays', 'action'=>'add_holiday'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
        </div>
    </div>
    <div class="panel-body">
        <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
            <thead>
                <tr>
                    <th>休暇名</th>
                    <th>祝日</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($customerData)){
                    foreach($customerData['Holiday'] as $record){ 
                ?>
                    <tr>
                        <td><?php echo $record['title'];?></td>
                        <td><?php echo $record['date'];?></td>
                        <td>
                            <!-- Icons -->
                            <a href="/holidays/delete_holiday/<?php echo $record['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                             <a href="/holidays/edit_holiday/<?php echo $record['id'];?>"><img src="/img/admin/pencil.png" title="Edit" alt="Edit"></a>
                        </td>
                    </tr>
                <?php } }?>
            </tbody>
            <tfoot>
                <tr>
                    <th>休暇名</th>
                    <th>祝日</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
