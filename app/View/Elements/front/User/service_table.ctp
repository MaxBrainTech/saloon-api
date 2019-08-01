<?php
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
?>
<div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">My Services</li>
            </ol>
        </div><!--/.row-->
        
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
        </div><!--/.row-->
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                My Services        
                <div class="col-md-4" style="float: right;">
                    <?php echo $this->Html->link("Add", array('controller'=>'services', 'action'=>'add'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
                    <!-- <a href="" class="btn btn-primary" style="float: right;">Add</a> -->
                </div>
            </div>
            <?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Service Name</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach($data as $record){?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $record['Service']['name'];?></td>
                                <td>
                                    <div style="text-align: center; width: 20px;background-color: <?php echo $record['Service']['color_code'];?>">&nbsp;</div>
                                    <?php //echo $record['Service']['status'];?></td>
                                <td>
                                    <?php 
                                        if($record['Service']['status']){
                                            echo $this->Html->link("Deactivate", array('controller'=>'services', 'action'=>'status',$record['Service']['id']), array("class"=>"btn btn-warning", "escape"=>false));
                                        }else{
                                            echo $this->Html->link("Activate", array('controller'=>'services', 'action'=>'status',$record['Service']['id']), array("class"=>"btn btn-primary", "escape"=>false));
                                        }
                                    ?>   
                                </td>
                                <td>
                                    <!-- Icons -->
                                    <a href="/jtsboard/services/delete/<?php echo $record['Service']['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/jtsboard/img/admin/cross.png" title="Delete" alt="Delete"></a>
                                     <!-- <a href="/jtsboard/users/view_service/<?php echo $record['Service']['id'];?>"><img src="/jtsboard/img/admin/view.jpg" title="View" alt="View"></a> -->
                                     <a href="/jtsboard/services/edit/<?php echo $record['Service']['id'];?>"><img src="/jtsboard/img/admin/pencil.png" title="Edit" alt="Edit"></a>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <th>Sr. No</th>
                        <th>Service Name</th>
                        <th>Color</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tfoot>
                </table>
            </div>
        </div>