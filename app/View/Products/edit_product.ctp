<?php 
$product_sale_price = substr($this->request->data['Product']['product_sale_price'],0,-3);
$product_purchase_price = substr($this->request->data['Product']['product_purchase_price'],0,-3);
?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Edit Product</li>
    </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
    </div><!--/.row-->
        <div class="panel panel-default">
            <div class="panel-heading">Edit Product</div>
            <div class="panel-body">
                <?php $this->Layout->sessionFlash(); ?>
                <?php echo $this->Form->create('Product',  array('url' => array('controller' => 'products', 'action' => 'edit_product'),'type'=>'file','inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                <?php
                    echo $this->Form->input('id');
                    $user_id = $_SESSION['User']['id'];  
                    echo $this->Form->input('user_id', array('type'=>'hidden','value'=>$user_id));
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('product_name', array('div' => false, 'label' => '商品名', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('product_purchase_price', array('div' => false, 'label' => '購買価格', 'type'=>'text','id'=>'product_purchase_price','value'=>$product_purchase_price, "class" => "form-control"))); ?>
                    </div>
                </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('product_stock', array('div' => false, 'label' => '在庫数','type'=>'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('product_sale_price', array('div' => false, 'label' => '売価','type'=>'text','id'=>'product_sale_price', 'value'=>$product_sale_price, "class" => "form-control"))); ?>
                    </div>
                </div>
                
                
                <div class="col-sm-12">
                    <?php echo ($this->Form->submit('送信', array('class' => 'btn btn-primary', "div" => false))); ?>
                    <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
                </div>
               <?php echo ($this->Form->end());    ?>
            </div>
        </div>
        </div><!-- /.panel-->
<script type="text/javascript">
    document.getElementById('product_purchase_price').addEventListener('input', event =>event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
    document.getElementById('product_sale_price').addEventListener('input', event =>event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
    // $(document).ready(function(){
    //     var val;
    //     $('#product_purchase_price').on('keyup',function(){
    //         // console.log($('#product_price').val());
    //             val = $('#product_purchase_price').val() + '円';
    //         // console.log(val);
    //         $('#product_purchase_price').val(val);
    //     });
    //     $('#product_sale_price').on('keyup',function(){
    //         // console.log($('#product_price').val());
    //             val = $('#product_sale_price').val() + '円';
    //         // console.log(val);
    //         $('#product_sale_price').val(val);
    //     });
    // });
</script>