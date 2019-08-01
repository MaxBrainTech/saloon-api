<?php //  pr($servicesList); 
// print_r($noteTicketDataService); 
?>
<style type="text/css">
    .note-comment-img img {
    width: 25%;
    height: 20%;
}
</style>
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

<!-- Note Service List Table -->

<div class="panel panel-default">
    <div class="panel-heading">
        Service List
        <div class="col-md-4" style="float: right;">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#serviceModal" class="btn btn-primary" style="float: right;">Add Service</a>
        </div>
    </div>
    <div class="panel-body">        
        <table id="dataTables-example852" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Employee Name</th>
                    <th>Service Price</th>
                    <th>Payment type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(!empty($customerAnalysisData['NoteService'])){
                    foreach($customerAnalysisData['NoteService'] as $service_record){ 
                ?>
                    <tr>
                        <td><?php echo $service_record['service_name'];?></td>
                        <td><?php echo $service_record['employee_name'];?></td>
                        <td><?php echo $service_record['service_price'];?></td>
                        <td><?php echo $service_record['payment_type'];?></td>
                        <td>
                            <!-- Icons -->
                            <?php echo $this->Html->link($this->Html->image("/img/admin/cross.png", ["alt" => "Delete"]), array('controller'=>'customerhistories', 'action'=>'delete_note_service',$service_record['id']), array("escape"=>false,'confirm' => 'Are you sure you wish to delete this recipe?')); ?>

                            <a href="javascript:void(0);" data-toggle="modal" data-target="#UpdateServiceModal" data-id="<?php echo $service_record['id']; ?>" class="editNoteService"><img src="/img/admin/pencil.png"></a>

                            <?php // echo $this->Html->link($this->Html->image("/img/admin/pencil.png", ["alt" => "Edit"]), array("escape"=>false,"data-toggle"=>"modal", "data-target"=>"#UpdateServiceModal")); ?>
                             
                        </td>
                    </tr>
                <?php } }?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Service Name</th>
                    <th>Employee Name</th>
                    <th>Service Price</th>
                    <th>Payment type</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


<!-- Note Product List Table -->

<div class="panel panel-default">
    <div class="panel-heading">
        Product List
        <div class="col-md-4" style="float: right;">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#productModal" class="btn btn-primary" style="float: right;">Add Product</a>
        </div>
    </div>
    <div class="panel-body">        
        <table id="dataTables-example253" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Employee Name</th>
                    <th>Sale Price</th>
                    <th>Product Quantity</th>
                    <th>Payment type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($customerAnalysisData['NoteProduct'])){
                    foreach($customerAnalysisData['NoteProduct'] as $product_record){ 
                ?>
                    <tr>
                        <td><?php echo $product_record['product_name'];?></td>
                        <td><?php echo $product_record['employee_name'];?></td>
                        <td><?php echo $product_record['sale_price'];?></td>
                        <td><?php echo $product_record['product_quantity'];?></td>
                        <td><?php echo $product_record['payment_type'];?></td>
                        <td>
                            <!-- Icons -->
                            <?php echo $this->Html->link($this->Html->image("/img/admin/cross.png", ["alt" => "Delete"]), array('controller'=>'customerhistories', 'action'=>'delete_note_product',$product_record['id']), array("escape"=>false,'confirm' => 'Are you sure you wish to delete this recipe?')); ?>
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#UpdateProductModal" data-id="<?php echo $product_record['id']; ?>" class="editNoteProduct"><img src="/img/admin/pencil.png"></a>
                        </td>
                    </tr>
                <?php }}?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Product Name</th>
                    <th>Employee Name</th>
                    <th>Sale Price</th>
                    <th>Product Quantity</th>
                    <th>Payment type</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


<!-- Note Ticket List Table -->

<div class="panel panel-default">
    <div class="panel-heading">
        Ticket List
        <div class="col-md-4" style="float: right;">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#ticketModal" class="btn btn-primary" style="float: right;">Add Ticket</a>
        </div>
    </div>
    <div class="panel-body">        
        <table id="dataTables-example123" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
            <thead>
                <tr>
                    <th>Ticket Name</th>
                    <th>Employee Name</th>
                    <th>Ticket Price</th>
                    <th>Ticket Quantity</th>
                    <th>Payment type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($customerAnalysisData['NoteTicket'])){
                    foreach($customerAnalysisData['NoteTicket'] as $ticket_record){ 
                ?>
                    <tr>
                        <td><?php echo $ticket_record['ticket_name'];?></td>
                        <td><?php echo $ticket_record['employee_name'];?></td>
                        <td><?php echo $ticket_record['ticket_price'];?></td>
                        <td><?php echo $ticket_record['ticket_num_time'];?></td>
                        <td><?php echo $ticket_record['payment_type'];?></td>
                        <td>
                            <!-- Icons -->
                            
                            <?php echo $this->Html->link($this->Html->image("/img/admin/cross.png", ["alt" => "Delete"]), array('controller'=>'customerhistories', 'action'=>'delete_note_ticket',$ticket_record['id']), array("escape"=>false,'confirm' => 'Are you sure you wish to delete this recipe?')); ?>
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#UpdateTicketModal" data-id="<?php echo $ticket_record['id']; ?>" class="editNoteTicket"><img src="/img/admin/pencil.png"></a>
                        </td>
                    </tr>
                <?php }}?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Ticket Name</th>
                    <th>Employee Name</th>
                    <th>Ticket Price</th>
                    <th>Ticket Quantity</th>
                    <th>Payment type</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Customer History and Ticket List
    </div>
    <div class="panel-body">  
        <div class="col-sm-12">
          <div class="form-group">
            <label>Total Service Price:</label>
            <span class="EditProfTxtRi">
                <?php echo $customerAnalysisData['CustomerHistory']['service_total_price']; ?>
            </span>
          </div>
          <div class="form-group">
            <label>Product Total Price:</label>
            <span class="EditProfTxtRi">
                <?php echo $customerAnalysisData['CustomerHistory']['product_total_price']; ?>
            </span>
          </div>
          <div class="form-group" style="color: #d5b348;">
            <label>Grand Total Price:</label>
            <span class="EditProfTxtRi">
                <?php echo $customerAnalysisData['CustomerHistory']['grand_total_price']; ?>
            </span>
          </div>
            <?php 
              if(!empty($customerAnalysisData['TicketList'])){
                foreach($customerAnalysisData['TicketList'] as $ticket_list_record){ ?>
            <div class="form-group">
                <label><?php echo $ticket_list_record['name'] ?>:</label>
                <span class="EditProfTxtRi">
                    <?php echo $ticket_list_record['ticket_amount'] ?>
                </span>
            </div>
            <?php }} ?>
            <hr>
            <?php if(!empty($customerAnalysisData['NoteImage'])){
                foreach($customerAnalysisData['NoteImage'] as $image_record){ ?>
            <div class="col-sm-12">
                    <div class="form-group">
                        <?php echo $this->Html->image("/img/images/profile-image.png", ["alt" => "Profile Image"]); ?>
                        <label><?php echo $image_record['employee_name']; ?></label>
                        <p class="">
                            <label>Comment Posted: </label>
                            <?php echo $image_record['created']; ?>
                        </p>
                    </div>
                    <div class="form-group note-comment-img">
                        <?php 
                        echo (isset($image_record['image'])? $this->Html->image("/uploads/note_image/original/". $image_record['image'], ["alt" => "Upload Image"]): $image_record['note_text']); 
                        ?>
                    </div>
            </div>
            <?php }} ?>
            <div class="col-sm-12">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $this->Form->create('CustomerHistory',  array('url' => array('controller' => 'customerhistories', 'action' => 'add_note_text'),'type'=>'file'));?>
                        <?php
                        $customer_id = $customerAnalysisData['CustomerHistory']['id'];  
                        echo $this->Form->input('customer_id', array('type'=>'hidden','value'=>$customer_id));
                        ?>
                    <div class="form-group">
                        <?php echo ($this->Form->input('note_text', array('div' => false, 'label' => 'Comment','type'=>'textarea','rows'=>'2','id'=>'note_text', "class" => "form-control"))); ?>
                    </div>
                    <?php echo ($this->Form->submit('Comment', array('class' => 'btn btn-primary', "div" => false))); ?>
                    <?php echo ($this->Form->end());    ?>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $this->Form->create('CustomerHistory',  array('url' => array('controller' => 'customerhistories', 'action' => 'add_note_image'),'type'=>'file'));?>
                        <?php
                        $customer_id = $customerAnalysisData['CustomerHistory']['id'];  
                        echo $this->Form->input('customer_id', array('type'=>'hidden','value'=>$customer_id));
                        ?>
                    <div class="form-group">
                        <?php echo ($this->Form->input('note_image', array('div' => false, 'label' => 'Upload Image','type'=>'file','id'=>'note_image', "class" => "form-control"))); ?>
                    </div>
                    <?php echo ($this->Form->submit('Submit Image', array('class' => 'btn btn-primary', "div" => false))); ?>
                    <?php echo ($this->Form->end());    ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




<!-- Modal Service -->
  <div class="modal fade" id="serviceModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Service</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
            <div class="panel-body">
                <?php echo $this->Form->create('NoteService',  array('url' => array('controller' => 'customerhistories', 'action' => 'add_note_service')));?>
                <?php
                // $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];  
                echo $this->Form->input('customer_history_id', array('type'=>'hidden','value'=>$customerAnalysisData['CustomerHistory']['id']));
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('service_id', array('options'=>$servicesList,'div'=>false,  'label' => 'Service',  "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('employee_id', array('options'=>$employeeList,'div'=>false,  'label' => 'Employee',  "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('service_price', array('div' => false, 'label' => 'Service Price','type'=>'text','id'=>'service_price', "class" => "form-control"))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('ticket_id', array('options'=>$noteTicketDataService,'div'=>false,  'label' => 'Ticket type',  "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                    <?php echo ($this->Form->submit('Submit', array('class' => 'btn btn-primary', "div" => false))); ?>
                    </div>
                </div>
                <?php echo ($this->Form->end());    ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<!-- Modal Service Update-->
  <div class="modal fade" id="UpdateServiceModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Service</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
            <div class="panel-body">
                <?php echo $this->Form->create('NoteService',  array('url' => array('controller' => 'customerhistories', 'action' => 'edit_note_service')));?>
                <?php
                // $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];  
                echo $this->Form->input('customer_history_id', array('type'=>'hidden','value'=>$customerAnalysisData['CustomerHistory']['id']));
                echo $this->Form->input('NoteServiceId', array('type'=>'hidden','id'=>'NoteServiceId'));
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('service_id', array('options'=>$servicesList,'div'=>false,  'label' => 'Service', 'id'=>'update_ticket_service_name', "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('employee_id', array('options'=>$employeeList,'div'=>false,  'label' => 'Employee', 'id'=>'update_ticket_employee_name',  "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('service_price', array('div' => false, 'label' => 'Service Price','type'=>'text','id'=>'update_service_price', "class" => "form-control"))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('ticket_id', array('options'=>$noteTicketDataService,'div'=>false,  'label' => 'Ticket type','id'=>'update_ticket_payment_type',  "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                    <?php echo ($this->Form->submit('Update', array('class' => 'btn btn-primary', "div" => false))); ?>
                    </div>
                </div>
                <?php echo ($this->Form->end());    ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



<!-- Modal Product -->
  <div class="modal fade" id="productModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Product</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
          <div class="panel-body">
                <?php // echo $this->Form->create('NoteProduct',  array('url' => array('controller' => 'customerhistories', 'action' => 'add_note_product')));?>
                <form action="javascript:void(0);" id="frm_add_note_product">
                <?php

                // $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];  
                echo $this->Form->input('customer_history_id', array('type'=>'hidden','value'=>$customerAnalysisData['CustomerHistory']['id']));
                echo $this->Form->input('productStock', array('type'=>'hidden','id'=>'productStock'));
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('product_id', array('options'=>$productList,'div'=>false,  'label' => 'Product',  "class" => "form-control productId")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('employee_id', array('options'=>$employeeList,'div'=>false,  'label' => 'Employee',  "class" => "form-control ")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('sale_price', array('div' => false, 'label' => 'Sale Price','type'=>'text','id'=>'service_price', "class" => "form-control"))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php // echo ($this->Form->input('product_quantity', array('options'=>'','div'=>false,  'label' => 'Product Quantity',  "class" => "form-control ")));?>
                        <?php echo ($this->Form->input('product_quantity', array('div' => false, 'label' => 'Product Quantity','type'=>'text','id'=>'productQuantity',"class" => "form-control"))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('ticket_id', array('options'=>(isset($noteTicketListProduct)?$noteTicketListProduct:''),'div'=>false,  'label' => 'Ticket type',  "class" => "form-control az_test")));?>
                    </div>                    
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                    <?php echo ($this->Form->submit('Submit', array('class' => 'btn btn-primary','id'=>"product_submit", "div" => false))); ?>
                    </div>
                </div>
                </form>
                <?php // echo ($this->Form->end());    ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<!-- Modal Product Update-->
  <div class="modal fade" id="UpdateProductModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Product</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
          <div class="panel-body">
                <?php // echo $this->Form->create('NoteProduct',  array('url' => array('controller' => 'customerhistories', 'action' => 'add_note_product')));?>
                <form action="javascript:void(0);" id="frm_update_note_product">
                <?php

                // $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];  
                echo $this->Form->input('customer_history_id', array('type'=>'hidden','value'=>$customerAnalysisData['CustomerHistory']['id']));

                echo $this->Form->input('NoteProductId', array('type'=>'hidden','id'=>'NoteProductId'));
                echo $this->Form->input('UpdateproductStock', array('type'=>'hidden','id'=>'UpdateproductStock'));
                
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('product_id', array('options'=>$productList,'div'=>false,  'label' => 'Product', 'id'=>'update_Product_Name',  "class" => "form-control UpdateProductIdByProduct")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('employee_id', array('options'=>$employeeList,'div'=>false,  'label' => 'Employee', 'id'=>'update_Employee_Name', "class" => "form-control ")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('sale_price', array('div' => false, 'label' => 'Sale Price','type'=>'text','id'=>'update_sale_price', "class" => "form-control"))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php // echo ($this->Form->input('product_quantity', array('options'=>'','div'=>false,  'label' => 'Product Quantity', 'id'=>'UpdateProductIds', "class" => "form-control UpdateProductId")));?>
                        <?php echo ($this->Form->input('product_quantity', array('div' => false, 'label' => 'Product Quantity','type'=>'text','id'=>'updateProductQuantity',"class" => "form-control"))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('ticket_id', array('options'=>$noteTicketListProduct,'div'=>false, 'id'=>'UpdateTicketName', 'label' => 'Ticket type',  "class" => "form-control")));?>
                    </div>                    
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                    <?php echo ($this->Form->submit('Update', array('class' => 'btn btn-primary', 'id'=>'update_product_submit', "div" => false))); ?>
                    </div>
                </div>
                </form>
                <?php // echo ($this->Form->end());    ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<!-- Modal Ticket -->
  <div class="modal fade" id="ticketModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Ticket</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
         <div class="panel-body">
                <?php // echo $this->Form->create('NoteProduct',  array('url' => array('controller' => 'customerhistories', 'action' => 'add_note_product')));?>
                <form action="javascript:void(0);" id="frm_add_note_ticket">
                <?php

                // $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];  
                echo $this->Form->input('customer_history_id', array('type'=>'hidden','value'=>$customerAnalysisData['CustomerHistory']['id']));
                // pr($ticketList);
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('ticket_id', array('options'=>$ticketList,'div'=>false,  'label' => 'Product',  "class" => "form-control ticketId")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('employee_id', array('options'=>$employeeList,'div'=>false,  'label' => 'Employee',  "class" => "form-control ")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_price', array('div' => false, 'label' => 'Ticket Price','type'=>'text','id'=>'ticket_price', "class" => "form-control",'readonly'))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_amount', array('div' => false, 'label' => 'Ticket Amount','type'=>'text','id'=>'ticket_amount', "class" => "form-control",'readonly'))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('payment_type', array('options'=>$noteTicketListTicket,'div'=>false,  'label' => 'Ticket type',  "class" => "form-control ")));?>
                    </div>                    
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                    <?php echo ($this->Form->submit('Submit', array('class' => 'btn btn-primary','id'=>"ticket_submit", "div" => false))); ?>
                    </div>
                </div>
                </form>
                <?php // echo ($this->Form->end());    ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!-- Modal Ticket Update-->
  <div class="modal fade" id="UpdateTicketModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Ticket</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
         <div class="panel-body">
                <?php // echo $this->Form->create('NoteProduct',  array('url' => array('controller' => 'customerhistories', 'action' => 'add_note_product')));?>
                <form action="javascript:void(0);" id="frm_update_note_ticket">
                <?php

                // $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];  
                echo $this->Form->input('customer_history_id', array('type'=>'hidden','value'=>$customerAnalysisData['CustomerHistory']['id']));

                echo $this->Form->input('NoteTicketId', array('type'=>'hidden','id'=>'NoteTicketId'));
                // pr($ticketList);
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('ticket_id', array('options'=>$ticketList,'div'=>false,  'label' => 'Product', 'id'=>'update_Ticket_name', "class" => "form-control UpdateTicketId")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('employee_id', array('options'=>$employeeList,'div'=>false,  'label' => 'Employee', 'id'=>'update_Employee_name', "class" => "form-control ")));?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_price', array('div' => false, 'label' => 'Ticket Price','type'=>'text','id'=>'update_ticket_price', "class" => "form-control",'readonly'))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_amount', array('div' => false, 'label' => 'Ticket Amount','type'=>'text','id'=>'update_ticket_amount', "class" => "form-control",'readonly'))); ?>
                    </div>                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('payment_type', array('options'=>$noteTicketListTicket,'div'=>false, 'id'=>'update_Payment_Type', 'label' => 'Ticket type',  "class" => "form-control ")));?>
                    </div>                    
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                    <?php echo ($this->Form->submit('Submit', array('class' => 'btn btn-primary','id'=>"update_ticket_submit", "div" => false))); ?>
                    </div>
                </div>
                </form>
                <?php // echo ($this->Form->end());    ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<script type="text/javascript">

//Product Section

      $(document).ready(function(){
        $('.productId').on('change',function(){
            var product_id = this.value;
            $.ajax({
                    url: '../get_product_stock',
                    type: 'post',
                    data: {'id':product_id},
                    success: function(data){
                        console.log(data);
                         // $("#product_quantity").html(data);
                         $('#productStock').val(data);
                    }
                });
        });

        $('#product_submit').click(function(){

                var product_stock = $('#productStock').val();
                var product_quan = $('#productQuantity').val();
                console.log(product_stock);
                console.log(product_quan);

                if(parseInt(product_stock) == 0){
                    alert("Product Stock: "+product_stock+" you can't added this product");

                }else if(parseInt(product_quan) > parseInt(product_stock)){
                    alert("Product Quantity greater then Product Stock: "+product_stock+"");
                }else{
                    var data = $( "#frm_add_note_product" ).serialize();

                    console.log(data);
                    $.ajax({
                        url: "../add_note_product",
                        type: 'post',
                        data: data,
                        success: function(result){
                            console.log(result);
                            window.setTimeout(function(){location.reload()},3000);

                        }
                    });
                }
            });

$('.UpdateProductIdByProduct').on('change',function(){
            var product_id = this.value;
            // console.log(product_id);
            $.ajax({
                    url: '../get_product_stock',
                    type: 'post',
                    data: {'id':product_id},
                    success: function(data){
                        console.log(data);
                         // $("#UpdateProductId").html(data);
                         $('#UpdateproductStock').val(data);
                    }
                });
        });
        $('#update_product_submit').click(function(){
                var update_product_stock = $('#UpdateproductStock').val();
                var update_product_quan = $('#updateProductQuantity').val();
                console.log(update_product_stock);
                console.log(update_product_quan);
                if(parseInt(update_product_stock) == 0){
                    alert("Product Stock: "+update_product_stock+" you can't added this product");
                }
                if(parseInt(update_product_quan) > parseInt(update_product_stock)){
                    alert("Product Quantity greater then Product Stock: "+update_product_stock+"");
                }else{
                    var data = $( "#frm_update_note_product" ).serialize();
                    console.log(data);
                    $.ajax({
                        url: "../edit_note_product",
                        type: 'post',
                        data: data,
                        success: function(result){
                            console.log(result);
                            window.setTimeout(function(){location.reload()},3000);

                        }
                    });
                }
            });

        $('.editNoteProduct').click(function(){
                var id = $(this).data("id");
                // console.log(id);
                // var data = $( "#frm_add_note_ticket" ).serialize();
                // console.log(data);
                $.ajax({
                    url: "../get_edit_note_productData",
                    type: 'post',
                    data: {'id':id},
                    success: function(data){
                        console.log(data);
                        var obj = jQuery.parseJSON(data);
                        // console.log(obj.str);
                        // console.log(obj.id);
                        console.log(obj.str1.ticket_id);
                        console.log(obj.str1.product_id);
                        console.log(obj.str1.payment_type);

                        // $( ".UpdateProductId" ).html(obj.str);


                        var sale_price = obj.str1.sale_price.slice(0,-1);
                        $('#update_sale_price').val(sale_price);
                        $('#updateProductQuantity').val(obj.str1.product_quantity);
                        $('#UpdateproductStock').val(obj.str);
                        $('#NoteProductId').val(obj.str1.id);

                        $( "#update_Product_Name" ).find('option[value="'+obj.str1.product_id+'"]').attr('selected','selected');
                        $( "#update_Employee_Name" ).find('option[value="'+obj.str1.employee_id+'"]').attr('selected','selected');
                        // $( "#UpdateProductIds" ).find('option[value="'+obj.str1.product_quantity+'"]').attr('selected','selected');
                        
                        

                        if(parseInt(obj.str1.ticket_id) !=0){
                            $( "#UpdateTicketName" ).find('option[value="'+obj.str1.ticket_id+'"]').attr('selected','selected');
                        }else{
                            $( "#UpdateTicketName" ).find('option[value="'+obj.str1.payment_type+'"]').attr('selected','selected');
                        }

                        // var value = $("#update_payment_type option:selected").val(obj.payment_type);
                        //To display the selected value we used <p id="result"> tag in HTML file
                        // $('#update_payment_type').append(value);

                        // $('#update_payment_type').val(obj.payment_type);
                        // $('#update_employee_name').val(obj.employee_name);
                        // $('#update_service_name').val(obj.service_name);

                        // window.setTimeout(function(){location.reload()},3000);
                    }
                });
            });


//Ticket Section

        $('.ticketId').on('change',function(){
            var ticket_id = this.value;
            // console.log(ticket_id);
            $.ajax({
                    url: '../get_ticket_price_amount',
                    type: 'post',
                    data: {'id':ticket_id},
                    success: function(data){
                        // console.log(data);
                        var obj = jQuery.parseJSON( data );
                        // console.log(obj.result1);
                        $('#ticket_price').val(obj.result1);
                        $('#ticket_amount').val(obj.result2);
                    }
                });
        });

        $('#ticket_submit').click(function(){
                var data = $( "#frm_add_note_ticket" ).serialize();
                // console.log(data);
                $.ajax({
                    url: "../add_note_ticket",
                    type: 'post',
                    data: data,
                    success: function(result){
                        // console.log(result);
                        window.setTimeout(function(){location.reload()},3000);
                    }
                });
            });

        $('.editNoteTicket').click(function(){
                var id = $(this).data("id");
                // console.log(id);
                $('#NoteTicketId').val(id);

                $.ajax({
                    url: '../get_edit_ticketData',
                    type: 'post',
                    data: {'id':id},
                    success: function(data){
                        // console.log(data);
                        var obj = jQuery.parseJSON( data );
                        // console.log(obj.result1);
                        var ticket_price = obj.ticket_price.slice(0,-1);
                        var ticket_amount = obj.ticket_amount.slice(0,-1);

                        $('#update_ticket_price').val(ticket_price);
                        $('#update_ticket_amount').val(ticket_amount);
                        // console.log(obj.ticket_name);
                        if(parseInt(obj.ticket_id) !=0){
                            $( "#update_Ticket_name" ).find('option[value="'+obj.ticket_id+'"]').attr('selected','selected');
                        }else{
                            $( "#update_Ticket_name" ).find('option[value="'+obj.ticket_name+'"]').attr('selected','selected');
                        }
                        // $( "#update_Ticket_name" ).find('option[value="'+obj.ticket_id+'"]').attr('selected','selected');
                        $( "#update_Employee_name" ).find('option[value="'+obj.employee_id+'"]').attr('selected','selected');
                        $( "#update_Payment_Type" ).find('option[value="'+obj.payment_type+'"]').attr('selected','selected');

                    }
                });
        });

        $('.UpdateTicketId').on('change',function(){
            var ticket_id = this.value;
            // console.log(id);
            $.ajax({
                    url: '../get_edit_ticket_price_amount',
                    type: 'post',
                    data: {'id':ticket_id},
                    success: function(data){
                        console.log(data);
                        var obj = jQuery.parseJSON( data );
                        // console.log(obj.ticket_price);
                        // console.log(obj.ticket_amount);
                        // var ticket_amount = obj.ticket_amount.slice(0,-1);
                        // console.log(obj.ticket_amount);
                        // console.log(obj);
                        $('#update_ticket_price').val(obj.ticket_price);
                        $('#update_ticket_amount').val(obj.ticket_amount);
                    }
                });
        });

        $('#update_ticket_submit').click(function(){
                var data = $( "#frm_update_note_ticket" ).serialize();
                // console.log(data);
                $.ajax({
                    url: "../edit_note_ticket",
                    type: 'post',
                    data: data,
                    success: function(result){
                        // console.log(result);
                        window.setTimeout(function(){location.reload()},3000);
                    }
                });
            });

//Service Section

        $('.editNoteService').click(function(){
                var id = $(this).data("id");
                // var sel_val;
                // console.log(id);
                // var data = $( "#frm_add_note_ticket" ).serialize();
                // console.log(data);
                $.ajax({
                    url: "../get_edit_note_serviceData",
                    type: 'post',
                    data: {'id':id},
                    success: function(data){
                        console.log(data);
                        var obj = jQuery.parseJSON(data);
                        // console.log(obj);
                        console.log(obj.payment_type);
                        // console.log(obj.id);
                        var service_price = obj.service_price.slice(0,-1);

                        $('#update_service_price').val(service_price);
                        $('#NoteServiceId').val(obj.id);

                        if(parseInt(obj.ticket_id) !=0){
                            $( "#update_ticket_payment_type" ).find('option[value="'+obj.ticket_id+'"]').attr('selected','selected');
                        }else{
                            $( "#update_ticket_payment_type" ).find('option[value="'+obj.payment_type+'"]').attr('selected','selected');
                        }
                        
                        $( "#update_ticket_service_name" ).find('option[value="'+obj.service_id+'"]').attr('selected','selected');
                        $( "#update_ticket_employee_name" ).find('option[value="'+obj.employee_id+'"]').attr('selected','selected');
                    }
                });

                
            });

        
        
      });
  </script>