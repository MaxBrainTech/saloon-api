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
                <li class="active">Service Information</li>
            </ol>
        </div><!--/.row-->
        
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
        </div><!--/.row-->
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Service Information        
                <div class="col-md-4" style="float: right;">
                    <?php //echo $this->Html->link("Add", array('controller'=>'customers', 'action'=>'add'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
                    <!-- <a href="" class="btn btn-primary" style="float: right;">Add</a> -->
                </div>
            </div>
<?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                        <?php if($data){?>
                        <?php foreach ($data[0]['Data'] as $key => $value) { ?>
                               <th><?php echo $key;?></th>
                        <?php } } ?>
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $record){?>
                            <tr>
                                <?php if($data){?>
                                <?php foreach($record['Data'] as $fieldKey => $fieldValue){?>
                                <td><?php echo $fieldValue;?></td>
                                <?php } } ?>
                                
                            </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <?php if($data){?>
                        <?php foreach ($data[0]['Data'] as $key => $value) { ?>
                               <th><?php echo $key;?></th>
                        <?php } } else{ echo 'No Information found.';}?>
                           
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>