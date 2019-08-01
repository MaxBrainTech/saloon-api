<?php	echo ($this->Html->script(array('ckeditor/ckeditor')));?>
<?php if(!empty($data)){
		$this->ExPaginator->options = array('url' => $this->passedArgs);?>
<table>
				
	<thead>
		<tr>
			<th width="10px"><input name="chkbox_n" id="chkbox_id" type="checkbox" value="" class="check-all"/></th>
			<th><?php echo ($this->ExPaginator->sort('User.first_name', 'Name'))?></th>
			<th><?php echo ($this->ExPaginator->sort('User.email', 'Email'))?></th>
			<th><?php echo ($this->ExPaginator->sort('Template.slug', 'Email Type'))?></th>
			<th><?php echo ($this->ExPaginator->sort('Template.slug', 'Email Verified '))?></th>
			<th><?php echo ($this->ExPaginator->sort('TemplatesUser.created', 'Created'))?></th>
			<th width="100px">Action</th>
		</tr>
		
	</thead>
	<tfoot>
            <tr>
                <td colspan="6" <?php //echo ($this->params['pass'][0]=='artist' ? '9' : '8'); ?>">
					<?php
					if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
					{ 
					?>
                    <div class="bulk-actions align-left">
						
                        <select name="data['Template']['action']" id="UserAction<?php echo ($defaultTab); ?>">
                            <option selected="selected" value="">Choose an action...</option>
                            <option value="delete">Delete</option>
                        </select>
                        <?php echo ($this->Form->submit('Apply to selected', array('name' => 'activate', 'class' => 'button', 'div' => false, 'type' => 'button', "onclick" => "javascript:return validateChk('Template','UserAction{$defaultTab}');"))); ?>
	
                    </div>
					<?php
					}
					?>
                    <?php
                    $this->Paginator->options(array(
                        'url' => $this->passedArgs,
                    ));
                    echo $this->element('Admin/admin_pagination', array("paging_model_name" => "TemplatesUser", "total_title" => "Total History"));
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
					
			echo ($this->Form->checkbox('TemplatesUser.id.', array('value' => $value['TemplatesUser']['id'], 'hiddenField' => false))); ?></td>
			<td><b>
			<?php 
			
			if(!empty($value['User']['first_name']))
			{
			echo ($this->Html->link(ucwords($value['User']['first_name']." ".$value['User']['last_name']),Router::url(array('action'=>'display', $value['TemplatesUser']['id']), true) ,array('title'=>'View Template', 'class'=>'view', 'target'=>'_blank','rel'=>'model')));
			}
			else
			{
				echo "--";
			}
			?>
			</b></td>			
			<td><?php 
			if(!empty($value['User']['email']))
			{
				echo ($value['User']['email']);
			}
			elseif(!empty($value['TemplatesUser']['receiver_email_id']))
			{
				echo $value['TemplatesUser']['receiver_email_id'];
			}
			else
			{
				echo "--";
			}
			
			
			?></td>
			<td><?php echo Configure::read('App.Email.History.'.$value['Template']['slug']);?></td>	
			<td><?php 
			if($value['Template']['slug'] == 'user_registration')
			{
				if(empty($value['User']['is_email_verified']))
				{
					echo "Not Verified";
				}
				else{
					echo "Verified";
				}
			}
			else{
			echo "--";
			}
			
			?></td>				
			<td><?php echo ($this->Time->niceShort($value['TemplatesUser']['created']));?></td>			
			<td>
				<!-- Icons -->
				<?php
				if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
				{
				

				echo ($this->Html->link($this->Html->image('admin/cross.png', array('title' => 'Delete', 'alt' => 'Delete')), array('controller' => 'templates', 'action' => 'history_delete',$value['TemplatesUser']['id']), array('escape' => false, 'onclick' => 'javascript:return confirm_delete(this)')));
				?>
				
				<?php
				}
				echo ($this->Html->link($this->Html->image('admin/view.jpg', array('title' => 'View', 'alt' => 'View')), array('controller' => 'templates', 'action' => 'history_view', $value['TemplatesUser']['id']), array('escape' => false)));
				?>
			</td>
		</tr>
		<?php
		  }
		 ?>
	</tbody>
</table>
<?php
	}else{
		echo ($this->element('admin_flash_info',array('message'=>'NO RESULTS FOUND')));
	}
?>

<div id="templateDialog"></div>
<script type="text/javascript">
	$('a.view').facebox();
</script>