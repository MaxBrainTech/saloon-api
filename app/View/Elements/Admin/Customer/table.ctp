<?php

if (!empty($data)) {
   
    $this->ExPaginator->options = array('url' => $this->passedArgs);
    ?>
    <table class="wordwrap">
        <thead>
            <tr>
               <?php /* ?>
                <th><?php echo ($this->ExPaginator->sort('Customer.profile_image', 'Customer Image')); ?></th>
				<?php  */?>
                <th><?php echo ($this->ExPaginator->sort('Customer.name', 'Name')); ?></th>
                <th><?php echo ($this->ExPaginator->sort('Customer.kana', 'Kana')); ?></th>
                <th><?php echo ($this->ExPaginator->sort('Service.name', 'Service Name')); ?></th>
                <th><?php echo ($this->ExPaginator->sort('Customer.email', 'Email')); ?></th>
                <th><?php echo ($this->ExPaginator->sort('Customer.created', 'Created')) ?></th>
                <th width="120px">Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6" <?php //echo ($this->params['pass'][0]=='artist' ? '9' : '8');               ?>">
					<?php
					if($this->Session->check('Auth.Customer.id') && $this->Session->read('Auth.Customer.role_id')== Configure::read('App.Admin.role'))
					{ 
					?>
                    <div class="bulk-actions align-left">
                        <select name="data['Customer']['action']" id="UserAction<?php echo ($defaultTab); ?>">
                            <option selected="selected" value="">Choose an action...</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                <?php
						echo ($this->Form->submit('Apply to selected', array('name' => 'activate', 'class' => 'button', 'div' => false, 'type' => 'button', "onclick" => "javascript:return validateChk('Customer','UserAction{$defaultTab}');"))); 
				?>
                    </div>
					<?php
					}
					?>
                    <?php
                    $this->Paginator->options(array(
                        'url' => $this->passedArgs,
                    ));
                    echo $this->element('Admin/admin_pagination', array("paging_model_name" => "Customer", "total_title" => "customers"));
                    ?>

                </td>
            </tr>
        </tfoot>
		
        <tbody>

            <?php
            $alt = 0;
            //pr($data);
            foreach ($data as $value) {
//pr($value);
                ?>
                <tr <?php
                echo ($alt == 0) ? 'class="alt-row"' : '';
                $alt = !$alt;
                ?>>
                  
                    <td><?php echo ($value['Customer']['name']); ?></td>
                    <td><?php echo ($value['Customer']['kana']); ?></td>
                    <td><?php
                    if($value['Service']['id']!=1){
                     echo ($this->Html->link($value['Service']['name'], array('controller' => 'customers', 'action' => 'service_details', $value['Customer']['id'], $value['Customer']['service_id']), array('escape' => false)));
                    }else{
                    echo ($value['Service']['name']);
                    }
                    ?></td>
                    <td><b><?php echo ($this->Html->link($value['Customer']['email'], array('action' => 'view', $value['Customer']['id']), array('title' => 'View Details'))); ?></b></td> 
                    <td><?php echo ($this->Time->niceShort(strtotime($value['Customer']['created']))); ?></td>

                    
                    <td>
                       
                       <?php					
                        echo ($this->Html->link($this->Html->image('admin/view.jpg', array('title' => 'View', 'alt' => 'View')), array('controller' => 'customers', 'action' => 'view', $value['Customer']['id']), array('escape' => false)));
                        ?>
                        <?php					
                       // echo ($this->Html->link($this->Html->image('admin/list.png', array('title' => 'Service list', 'alt' => 'Service list')), array('controller' => 'customers', 'action' => 'services', $value['Customer']['id']), array('escape' => false)));
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>

        </tbody>

    </table>
    <?php
} else {
    echo ($this->element('admin_flash_info', array('message' => 'NO RESULTS FOUND')));
}
?>