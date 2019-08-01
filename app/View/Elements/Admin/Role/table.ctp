<?php if(!empty($data)){
		$this->ExPaginator->options = array('url' => $this->passedArgs);?>
<table>
				
	<thead>
		<tr>
			<th width="20px"><input name="chkbox_n" id="chkbox_id" type="checkbox" value="" class="check-all"/></th>
			<th><?php echo ($this->ExPaginator->sort('Role.name', 'Role Name'))?></th>		<th><?php echo ($this->ExPaginator->sort('Role.status', 'Status'))?></th>
			<th width="50px">Action</th>
		</tr>
		
	</thead>
 
	<tfoot>
		<tr>
			<td colspan="7">
				<div class="bulk-actions align-left">
					<select name="data['Role']['action']" id="RoleAction<?php echo ($defaultTab);?>">
						<option selected="selected" value="">Choose an action...</option>
						<option value="activate">Activate</option>
						<option value="deactivate">Deactivate</option>
						<option value="delete">Delete</option>
					</select>
					<?php echo ($this->Form->submit('Apply to selected', array('name'=>'activate', 'class'=>'button','div'=>false, 'type'=>'button', "onclick" => "javascript:return validateChk('Role','RoleAction{$defaultTab}');")));?>
					
				</div>				
				<?php 
				
				$this->Paginator->options(array('url' => $this->passedArgs));
				echo $this->element('Admin/admin_pagination', array("paging_model_name"=>"Role", "total_title"=>"Roles")); ?>
				
			</td>
		</tr>
	</tfoot>
 
	<tbody>
			
		<?php
			$alt=0;
			foreach($data as $value){		 
		 ?>
		<tr <?php echo ($alt==0)?'class="alt-row"':''; $alt=!$alt;?>>
			<td><?php echo ($this->Form->checkbox('Role.id.', array('value'=>$value['Role']['id'], 'hiddenField'=>false ))); ?></td>
			<td><b><?php echo ($value['Role']['name']);?></b></td>
			<td>
			<?php echo ($this->Html->link($this->Layout->Status($value['Role']['status']), array('action'=>'status',$value['Role']['id'],'token'=>$this->params['_Token']['key']), array('title'=>$value['Role']['status']==1?'deactivate':'activate')));?></td>
			<td>
				<!-- Icons -->
				 <?php 
					echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title'=>'Edit','alt'=>'Edit')), array('controller'=>'roles', 'action'=>'edit', $value['Role']['id']), array('escape'=>false)));
				?>
				<?php 
					echo ($this->Html->link($this->Html->image('admin/cross.png', array('title'=>'Delete','alt'=>'Delete')), array('controller'=>'roles', 'action'=>'delete', $value['Role']['id'],'token'=>$this->params['_Token']['key']), array('escape'=>false, 'onclick'=>'javascript:return confirm_delete(this)')));
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