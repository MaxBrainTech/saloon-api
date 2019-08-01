<?php
if (!empty($data)) {
   
    $this->ExPaginator->options = array('url' => $this->passedArgs);
    ?>
    <table class="wordwrap">
        <thead>
            <tr>
                <th width="10px"><input name="chkbox_n" id="chkbox_id" type="checkbox" value="" class="check-all"/></th>
				<?php /* ?>
                <th><?php echo ($this->ExPaginator->sort('HelpQuestion.profile_image', 'HelpQuestion Image')); ?></th>
				<?php  */?>
                <th><?php echo ($this->ExPaginator->sort('HelpQuestion.email', 'Email')); ?></th>
                <th><?php echo ($this->ExPaginator->sort('HelpQuestion.created', 'Created')) ?></th>
                <th><?php echo ($this->ExPaginator->sort('HelpQuestion.status', 'Status')) ?></th>
                <th width="120px">Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6" <?php //echo ($this->params['pass'][0]=='artist' ? '9' : '8');               ?>">
					<?php
					if($this->Session->check('Auth.HelpQuestion.id') && $this->Session->read('Auth.HelpQuestion.role_id')== Configure::read('App.Admin.role'))
					{ 
					?>
                    <div class="bulk-actions align-left">
                        <select name="data['HelpQuestion']['action']" id="HelpQuestionAction<?php echo ($defaultTab); ?>">
                            <option selected="selected" value="">Choose an action...</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                <?php
						echo ($this->Form->submit('Apply to selected', array('name' => 'activate', 'class' => 'button', 'div' => false, 'type' => 'button', "onclick" => "javascript:return validateChk('HelpQuestion','HelpQuestionAction{$defaultTab}');"))); 
				?>
                    </div>
					<?php
					}
					?>
                    <?php
                    $this->Paginator->options(array(
                        'url' => $this->passedArgs,
                    ));
                    echo $this->element('Admin/admin_pagination', array("paging_model_name" => "HelpQuestion", "total_title" => "Helps"));
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
                    <td><?php 
					if($value['HelpQuestion']['email'] != 'info@flymigo.com')
					{
					echo ($this->Form->checkbox('HelpQuestion.id.', array('value' => $value['HelpQuestion']['id'], 'hiddenField' => false))); 
					}
					?></td>
				                 
                    <td><b><?php echo ($this->Html->link($value['HelpQuestion']['email'], array('action' => 'view', $value['HelpQuestion']['id']), array('title' => 'View Details'))); ?></b></td> 
                    <td><?php echo ($this->Time->niceShort(strtotime($value['HelpQuestion']['created']))); ?></td>

                    <td><?php 
						echo ($this->Html->link($this->Layout->Status($value['HelpQuestion']['status']), array('action' => 'status', $value['HelpQuestion']['id']), array('title' => $value['HelpQuestion']['status'] == 1 ? 'deactivate' : 'activate')));
					?>
                    </td>
                    <td>
                        <!-- Icons -->
                        <?php
						
                       // echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('controller' => 'helps', 'action' => 'edit', $value['HelpQuestion']['id']), array('escape' => false)));
                        ?>
                        <?php
						
						echo ($this->Html->link($this->Html->image('admin/cross.png', array('title' => 'Delete', 'alt' => 'Delete')), array('controller' => 'helps', 'action' => 'delete', $value['HelpQuestion']['id']), array('escape' => false, 'onclick' => 'javascript:return confirm_delete(this)')));
						
                        ?>
                        <?php
                        echo ($this->Html->link($this->Html->image('admin/hammer_screwdriver.png', array('title' => 'Change Password', 'alt' => 'Change Password')), array('controller' => 'helps', 'action' => 'change_password', $value['HelpQuestion']['id']), array('escape' => false)));
                        ?>
                        <?php					
                        echo ($this->Html->link($this->Html->image('admin/view.jpg', array('title' => 'View', 'alt' => 'View')), array('controller' => 'helps', 'action' => 'view', $value['HelpQuestion']['id']), array('escape' => false)));
                        ?>
                        <?php					
                        echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('controller' => 'helps', 'action' => 'edit', $value['HelpQuestion']['id']), array('escape' => false)));
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