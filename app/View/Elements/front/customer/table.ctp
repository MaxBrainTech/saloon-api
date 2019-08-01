<div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">顧客リスト</li>
            </ol>
        </div><!--/.row-->
        
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
        </div><!--/.row-->
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                顧客リスト        
                <div class="col-md-4" style="float: right;">
                    <?php echo $this->Html->link("Add", array('controller'=>'customers', 'action'=>'add'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
                    <!-- <a href="" class="btn btn-primary" style="float: right;">Add</a> -->
                </div>
            </div>
<?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                            <th>名前</th>
                            <th>メール</th>
                            <th>電話番号</th>
                            <th>サービス</th>
                            <th>Last Visited Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $record){?>
                            <tr>
                                <td><?php echo $record['Customer']['name'];?></td>
                                <td><?php echo $record['Customer']['email'];?></td>
                                <td><?php echo $record['Customer']['tel'];?></td>
                                <td><?php echo $record['Service']['name'];?></td>
                                <td><?php echo date('Y-m-d',strtotime($record['Service']['modified']));?></td>
                                <td>
                                        <!-- Icons -->
                                        <a href="/customers/delete/<?php echo $record['Customer']['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                                         <a href="/customers/view/<?php echo $record['Customer']['id'];?>"><img src="/img/admin/view.jpg" title="View" alt="View"></a>
                                         <a href="/customers/edit/<?php echo $record['Customer']['id'];?>"><img src="/img/admin/pencil.png" title="Edit" alt="Edit"></a>
                                         <?php echo $this->Html->link("注意", array('controller'=>'customerhistories', 'action'=>'get_customer_analysis_dates',$record['Customer']['id']), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                         <th>名前</th>
                         <th>メール</th>
                         <th>電話番号</th>
                         <th>サービス</th>
                         <th>Last Visited Date</th>
                         <th>Action</th>
                    </tfoot>
                </table>

            </div>
        </div>