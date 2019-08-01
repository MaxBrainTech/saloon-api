<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Product List</li>
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
        Product List        
        <div class="col-md-4" style="float: right;">
            <?php echo $this->Html->link("Add", array('controller'=>'products', 'action'=>'add_product'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
        </div>
    </div>
    <div class="panel-body">
        <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
            <thead>
                <tr>
                    <th>商品名</th>
                    <th>売価</th>
                    <th>購買価格</th>
                    <th>在庫数</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($customerData)){
                    foreach($customerData['Product'] as $record){ 
                ?>
                    <tr>
                        <td><?php echo $record['product_name'];?></td>
                        <td><?php echo $record['product_sale_price'];?></td>
                        <td><?php echo $record['product_purchase_price'];?></td>
                        <td><?php echo $record['product_stock'];?></td>
                        <td>
                            <!-- Icons -->
                            <a href="/products/delete_product/<?php echo $record['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                             <a href="/products/edit_product/<?php echo $record['id'];?>"><img src="/img/admin/pencil.png" title="Edit" alt="Edit"></a>
                        </td>
                    </tr>
                <?php } }?>
            </tbody>
            <tfoot>
                <tr>
                    <th>商品名</th>
                    <th>売価</th>
                    <th>購買価格</th>
                    <th>在庫数</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
