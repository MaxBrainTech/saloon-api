<?php
    // echo "<pre>";
    // print_r($todaySell);
    // echo "</pre>";
?>
<div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">合計売上</li>
            </ol>
        </div><!--/.row-->
        
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
        </div><!--/.row-->
        
        <!-- Service Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Service List
                <div style="float: right;">
                    <?php echo $this->Html->link("合計売上", array('controller'=>'users', 'action'=>'get_total_sell'), array("class"=>"btn btn-primary", "escape"=>false)); ?>
                </div>
            </div>
            <?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>サービス</th>
                            <th>合計売上</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach($todaySell['Service'] as $record){?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $record['service_name'];?></td>
                                <td><?php echo $record['total_sell'];?></td>
                            </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <th>Sr. No</th>
                        <th>サービス</th>
                        <th>合計売上</th>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                スタッフ
            </div>
            <?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>サービス</th>
                            <th>合計売上</th>
                            <th>Customer Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach($todaySell['Staff'] as $record){?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $record['staff_name'];?></td>
                                <td><?php echo $record['total_sell'];?></td>
                                <td><?php echo $record['customer_count'];?></td>
                            </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <th>Sr. No</th>
                        <th>サービス</th>
                        <th>合計売上</th>
                        <th>Customer Count</th>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Today Cash Box Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                キャッシャー合計金額
            </div>
            <?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                            <th>( キャッシャー + 現金金額 ) - 合計費用 </th> 
                            <th>キャッシャー合計金額</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php if($todaySell['TodaySell']){?>
                            <td><?php echo '( '.$todaySell['TodaySell']['cash_box'].' + '.$todaySell['TodaySell']['total_cash_price'].' )'.' - '.$todaySell['TodaySell']['total_expense'];?></td>
                            <td><?php echo $todaySell['TodaySell']['total_cash_box'];?></td>
                            <?php }?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Today Sell Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                合計売上 
            </div>
            <?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                            <th>( 現金総計 + カード支払額総計 )</th> 
                            <th>合計売上</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php if($todaySell['TodaySell']){?>
                            <td><?php echo '( '.$todaySell['TodaySell']['total_cash_price'].' + '.$todaySell['TodaySell']['total_card_price'].' )';?></td>
                            <td><?php echo $todaySell['TodaySell']['total_sell'];?></td>
                            <?php }?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
