<?php
if (!empty($saleUserData)) {
	?>
<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">Manage Admins</h3>
               <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">
        <div id="page-loader">
            <img src="/img/admin/loading.gif" alt="">        
        </div>
            <div class="tab-content default-tab" id="All" style=""> <!-- This is the target div. id must match the href of this div's tab -->

                <div id="targetAll">    

       <table class="wordwrap">

        <thead>
            <tr>
                <th width="200px">Name</th>
                <th width="200px">Email</th>
                <th width="200px">Your Sales Code</th>
				<th width="150px">Created on</th>
                <th>Status</th>
				<th width="100px">Action</th>
            </tr>

        </thead>
        <tbody>
			<tr class="alt-row">
                <td><?php echo $saleUserData['SaleUser']['name']; ?></td>
                <td><?php echo $saleUserData['SaleUser']['email']; ?></td>
                <td><?php echo $saleUserData['SaleUser']['unique_sales_code']; ?></td>
                <td><?php echo ($this->Time->niceShort(strtotime($saleUserData['SaleUser']['created']))); ?></td>
                <td>Active</td>
                <td>
                	<?php 
                        echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('controller' => 'sale_users', 'action' => 'sales_admin_edit', $saleUserData['SaleUser']['id']), array('escape' => false)));
						echo ($this->Html->link($this->Html->image('admin/hammer_screwdriver.png', array('title' => 'Change Password', 'alt' => 'Change Password')), array('controller' => 'sale_users', 'action' => 'sales_admin_change_password', $saleUserData['SaleUser']['id']), array('escape' => false)));
					
                        ?>
                </td>
            </tr>
        </tbody>

    </table>
    </div>

            </div>
    </div> <!-- End .content-box-content -->

</div>
<?php
} else {
    echo ($this->element('admin_flash_info', array('message' => 'NO RESULTS FOUND')));
}
?>