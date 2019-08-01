<?php if(!empty($data)){
		$this->ExPaginator->options = array('url' => $this->passedArgs);?>
<table>
				
	<thead>
		<tr>
			<th width="20px"><input name="chkbox_n" id="chkbox_id" type="checkbox" value="" class="check-all"/></th>
			<th><?php echo ($this->ExPaginator->sort('Service.name', 'Service Name'))?></th>		<th><?php echo ($this->ExPaginator->sort('Service.status', 'Status'))?></th>
			<th width="50px">Action</th>
		</tr>
		
	</thead>
 
	<tfoot>
		<tr>
			<td colspan="7">
				<div class="bulk-actions align-left">
					<select name="data['Service']['action']" id="ServiceAction<?php echo ($defaultTab);?>">
						<option selected="selected" value="">Choose an action...</option>
						<option value="activate">Activate</option>
						<option value="deactivate">Deactivate</option>
						<option value="delete">Delete</option>
					</select>
					<?php echo ($this->Form->submit('Apply to selected', array('name'=>'activate', 'class'=>'button','div'=>false, 'type'=>'button', "onclick" => "javascript:return validateChk('Service','ServiceAction{$defaultTab}');")));?>
					
				</div>				
				<?php 
				
				$this->Paginator->options(array('url' => $this->passedArgs));
				echo $this->element('Admin/admin_pagination', array("paging_model_name"=>"Service", "total_title"=>"Services")); ?>
				
			</td>
		</tr>
	</tfoot>
 
	<tbody>
			
		<?php
			$alt=0;
			foreach($data as $value){		 
		 ?>
		<tr <?php echo ($alt==0)?'class="alt-row"':''; $alt=!$alt;?>>
			<td><?php echo ($this->Form->checkbox('Service.id.', array('value'=>$value['Service']['id'], 'hiddenField'=>false ))); ?></td>
			<td><b><?php echo ($value['Service']['name']);?></b></td>
			<td>
			<?php echo ($this->Html->link($this->Layout->Status($value['Service']['status']), array('action'=>'status',$value['Service']['id'],'token'=>$this->params['_Token']['key']), array('title'=>$value['Service']['status']==1?'deactivate':'activate')));?></td>
			<td>
				<!-- Icons -->
				 <?php 
					echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title'=>'Edit','alt'=>'Edit')), array('controller'=>'services', 'action'=>'edit', $value['Service']['id']), array('escape'=>false)));
				?>
				<?php 
					echo ($this->Html->link($this->Html->image('admin/cross.png', array('title'=>'Delete','alt'=>'Delete')), array('controller'=>'services', 'action'=>'delete', $value['Service']['id'],'token'=>$this->params['_Token']['key']), array('escape'=>false, 'onclick'=>'javascript:return confirm_delete(this)')));
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