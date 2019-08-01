
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Customer Note Histories List</li>
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
        Customer Note Histories List        
        <div class="col-md-4" style="float: right;">
            <?php echo $this->Html->link("Add", array('controller'=>'customerhistories', 'action'=>'add_note',$customer_id), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
        </div>
    </div>
    <div class="panel-body">
        <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($responseArr)){
                    foreach($responseArr as $record){ 
                ?>
                    <tr>
                        <td><?php echo $record['date'];?></td>
                        <td>
                            <!-- Icons -->
                            <?php echo $this->Html->link("View Note", array('controller'=>'customerhistories', 'action'=>'get_note',$record['id']), array("class"=>"btn btn-primary",'style'=>'float: left;', "escape"=>false)); ?>
                        </td>
                    </tr>
                <?php } }?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
