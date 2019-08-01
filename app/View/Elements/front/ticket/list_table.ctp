<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Ticket List</li>
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
        Ticket List        
        <div class="col-md-4" style="float: right;">
            <?php echo $this->Html->link("Add", array('controller'=>'tickets', 'action'=>'add_ticket'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
        </div>
    </div>
    <div class="panel-body">
        <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
            <thead>
                <tr>
                    <th>チケット名</th>
                    <th>チケット金額</th>
                    <th>チケット合計</th>
                    <th>Ticket Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // pr($customerData);die;
                    if(isset($customerData)){
                    foreach($customerData['Ticket'] as $record){ 
                ?>
                    <tr>
                        <td><?php echo $record['ticket_name'];?></td>
                        <td><?php echo $record['ticket_price'];?></td>
                        <td><?php echo $record['ticket_amount'];?></td>
                        <td><?php echo isset($record['ticket_num_time'])?$record['ticket_num_time']:'NaN';?></td>
                        <td>
                            <!-- Icons -->
                            <a href="/tickets/delete_ticket/<?php echo $record['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                             <a href="/tickets/edit_ticket/<?php echo $record['id'];?>"><img src="/img/admin/pencil.png" title="Edit" alt="Edit"></a>
                        </td>
                    </tr>
                <?php } }?>
            </tbody>
            <tfoot>
                <tr>
                    <th>チケット名</th>
                    <th>チケット金額</th>
                    <th>チケット合計</th>
                    <th>Ticket Number</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
