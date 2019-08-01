<?php
if (!empty($data)) {
   
    $this->ExPaginator->options = array('url' => $this->passedArgs);
    ?>
    <table class="wordwrap">
        <thead>
            <tr>
                <th width="10px"><input name="chkbox_n" id="chkbox_id" type="checkbox" value="" class="check-all"/></th>
				<?php /* ?>
                <th><?php echo ($this->ExPaginator->sort('User.profile_image', 'User Image')); ?></th>
				<?php  */?>
                <th><?php echo ($this->ExPaginator->sort('User.email', 'Email')); ?></th>
                <th><?php echo ($this->ExPaginator->sort('User.created', 'Created')) ?></th>
                <th><?php echo ($this->ExPaginator->sort('User.status', 'Status')) ?></th>
                <th width="120px">Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6" <?php //echo ($this->params['pass'][0]=='artist' ? '9' : '8');               ?>">
					<?php
					if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
					{ 
					?>
                    <div class="bulk-actions align-left">
                        <select name="data['User']['action']" id="UserAction<?php echo ($defaultTab); ?>">
                            <option selected="selected" value="">Choose an action...</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                <?php
						echo ($this->Form->submit('Apply to selected', array('name' => 'activate', 'class' => 'button', 'div' => false, 'type' => 'button', "onclick" => "javascript:return validateChk('User','UserAction{$defaultTab}');"))); 
				?>
                    </div>
					<?php
					}
					?>
                    <?php
                    $this->Paginator->options(array(
                        'url' => $this->passedArgs,
                    ));
                    echo $this->element('Admin/admin_pagination', array("paging_model_name" => "User", "total_title" => "Users"));
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
					if($value['User']['email'] != 'info@flymigo.com')
					{
					echo ($this->Form->checkbox('User.id.', array('value' => $value['User']['id'], 'hiddenField' => false))); 
					}
					?></td>
				<?php /* ?>
                    <td>
					<?php		
		if(!empty($value['User']['profile_image']) &&  file_exists(WWW_ROOT . USER_THUMB_DIR
	. DS .$value['User']['profile_image']) )
		{
			echo $this->General->user_show_pic($value['User']['profile_image'], 'SMALL',$value['User']['username'], 'main_profile_photo');
		}
		else
		{
			echo $this->Html->image('no-picture.gif', array('alt'=>'img'  ,'id'=>"main_profile_photo", 'width'=>'60', 'height'=>'60'));
		}
		?>
					</td> 
				<?php  */?>                  
                    <td><b><?php echo ($this->Html->link($value['User']['email'], array('action' => 'view', $value['User']['id']), array('title' => 'View Details'))); ?></b></td> 
                    <td><?php echo ($this->Time->niceShort(strtotime($value['User']['created']))); ?></td>

                    <td><?php 
						echo ($this->Html->link($this->Layout->Status($value['User']['status']), array('action' => 'status', $value['User']['id']), array('title' => $value['User']['status'] == 1 ? 'deactivate' : 'activate')));
					?>
                    </td>
                    <td>
                        <!-- Icons -->
                        <?php
						
                       // echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('controller' => 'users', 'action' => 'edit', $value['User']['id']), array('escape' => false)));
                        ?>
                        <?php
						
						echo ($this->Html->link($this->Html->image('admin/cross.png', array('title' => 'Delete', 'alt' => 'Delete')), array('controller' => 'users', 'action' => 'delete', $value['User']['id']), array('escape' => false, 'onclick' => 'javascript:return confirm_delete(this)')));
						
                        ?>
                        <?php
                        echo ($this->Html->link($this->Html->image('admin/hammer_screwdriver.png', array('title' => 'Change Password', 'alt' => 'Change Password')), array('controller' => 'users', 'action' => 'change_password', $value['User']['id']), array('escape' => false)));
                        ?>
                        <?php					
                        echo ($this->Html->link($this->Html->image('admin/view.jpg', array('title' => 'View', 'alt' => 'View')), array('controller' => 'users', 'action' => 'view', $value['User']['id']), array('escape' => false)));
                        ?>
                        <?php					
                        echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('controller' => 'users', 'action' => 'edit', $value['User']['id']), array('escape' => false)));
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