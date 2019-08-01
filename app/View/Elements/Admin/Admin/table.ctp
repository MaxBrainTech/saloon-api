<?php
if (!empty($data)) {
    $this->ExPaginator->options = array('url' => $this->passedArgs);
    ?>
    <table class="wordwrap">

        <thead>
            <tr>
				<th width="150px"><?php echo ($this->ExPaginator->sort('User.name', 'Name')) ?></th>
				<th width="150px"><?php echo ($this->ExPaginator->sort('User.username', 'Username')) ?></th>
                <th width="200px"><?php echo ($this->ExPaginator->sort('User.email', 'Email')) ?></th>
                <th width="200px"><?php echo ($this->ExPaginator->sort('User.company_name', 'Comapny Name')) ?></th>
				<th width="150px"><?php echo ($this->ExPaginator->sort('User.created', 'Created on')) ?></th>
                <th><?php echo ($this->ExPaginator->sort('User.status', 'Status')) ?></th>
				<?php if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role')){ ?>
                <th width="100px">Action</th>
                <?php } ?>
            </tr>

        </thead>
		
		<tfoot>
            <tr>
                <td colspan="6" <?php //echo ($this->params['pass'][0]=='artist' ? '9' : '8');               ?>">
					<?php
					if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
					{
						if(count($data)>1)
						{
					?>
                    <div class="bulk-actions align-left">
						
                        <select name="data['User']['action']" id="UserAction<?php echo ($defaultTab); ?>">
                            <option selected="selected" value="">Choose an action...</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <?php echo ($this->Form->submit('Apply to selected', array('name' => 'activate', 'class' => 'button', 'div' => false, 'type' => 'button', "onclick" => "javascript:return validateChk('User','UserAction{$defaultTab}');"))); ?>
	
                    </div>
					<?php
						}
					}
					?>
                    <?php
                    $this->Paginator->options(array(
                        'url' => $this->passedArgs,
                    ));
                    echo $this->element('Admin/admin_pagination', array("paging_model_name" => "User", "total_title" => "Admins/Analysts"));
                    ?>

                </td>
            </tr>
        </tfoot>


        <tbody>

            <?php
            $alt = 0;
            foreach ($data as $value) {
                ?>
                <tr <?php
                echo ($alt == 0) ? 'class="alt-row"' : '';
                $alt = !$alt;
                ?>>
				
					<td><?php echo (ucfirst($value['User']['name'])); ?></td>
					<td><b><?php echo ($this->Html->link($value['User']['username'], array('action' => 'view', $value['User']['id']), array('title' => 'View Details'))); ?></b></td>
                    <td><?php echo ($value['User']['email']); ?></td>
                    <td><?php echo ($value['User']['company_name']); ?></td>
					<td><?php echo ($this->Time->niceShort(strtotime($value['User']['created']))); ?></td>
                    
					<td><?php 
					if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
					{
						if($value['User']['role_id']==Configure::read('App.Admin.role'))
						{
							echo $this->Layout->userStatus($value['User']['status']);
						}
						else
						{
							echo $this->Layout->userStatus($value['User']['status']);
						}
						
							
					}
					else
					{
						echo $this->Layout->userStatus($value['User']['status']);
					}
					?>
                    </td>
                    <?php if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role')){ ?>
                    <td>
                        <!-- Icons -->
                        <?php 
                        echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('controller' => 'admins', 'action' => 'edit', $value['User']['id']), array('escape' => false)));
						echo ($this->Html->link($this->Html->image('admin/hammer_screwdriver.png', array('title' => 'Change Password', 'alt' => 'Change Password')), array('controller' => 'admins', 'action' => 'change_password', $value['User']['id']), array('escape' => false)));
					
                        ?>
                    </td>
                    <?php } ?>
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