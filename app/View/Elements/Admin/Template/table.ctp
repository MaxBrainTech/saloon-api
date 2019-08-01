<?php	echo ($this->Html->script(array('ckeditor/ckeditor')));?>
<?php if(!empty($data)){
		$this->ExPaginator->options = array('url' => $this->passedArgs);?>
<table>
				
	<thead>
		<tr>
			<th><?php echo ($this->ExPaginator->sort('Template.name', 'Template Name'))?></th>
			<th><?php echo ($this->ExPaginator->sort('Template.subject', 'Email Subject'))?></th>
			<th><?php echo ($this->ExPaginator->sort('Template.modified', 'Updated'))?></th>
			<th width="16px">Action</th>
		</tr>
		
	</thead>
 
	<tfoot>
		<tr>
			<td colspan="4">
				<div class="bulk-actions align-left">
				</div>
				<?php $this->Paginator->options(
				array(
					 'url' => $this->passedArgs,
				));
				echo $this->element('Admin/admin_pagination', array("paging_model_name"=>"Template", "total_title"=>"Templates")); ?>
			</td>
		</tr>
	</tfoot>
 
	<tbody>
		<?php
			$alt=0;
			foreach($data as $value){		 
		 ?>
		<tr <?php echo ($alt==0)?'class="alt-row"':''; $alt=!$alt;?>>
			<td><b>
			<?php echo ($this->Html->link($value['Template']['name'],Router::url(array('action'=>'display', $value['Template']['id']), true) ,array('title'=>'View Template', 'class'=>'view', 'target'=>'_blank','rel'=>'model')));?>
			</b></td>			
			<td><?php echo ($value['Template']['subject']);?></td>		
			<td><?php echo ($this->Time->niceShort($value['Template']['modified']));?></td>			
			<td>
				<!-- Icons -->
				 <?php 
				if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
				{
					echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title'=>'Edit','alt'=>'Edit')), array('action'=>'edit', $value['Template']['id']), array('escape'=>false)));
				}	
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
<?php
/* if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
{ */
?>
				
<!--<div class="content-box">
	
	<div class="content-box-header">
		
		<h3 style="cursor: s-resize;">Send Email</h3>
		
		<ul class="content-box-tabs">
			<li>* required fields</li> 
			
		</ul>
		
		<div class="clear"></div>
		
	</div>	
	<div class="content-box-content">
		<div id="error_div" style="color:red;"></div>
		<div style="display: block;" class="" id="tab2">
			
		<?php 
			//echo $this->Form->create('Template', 	array('url' => array('controller' => 'templates', 'action' => 'send_email')));?>	
		<fieldset> 
			 <p>
				<?php 
				
				//echo ($this->Form->input('user_id', array('type' => 'select', 'options' => $users,'empty'=>'----Select User----', "class" => "text-input medium-input", 'div' => false, 'label' => 'User*'))); ?>
				
			</p>
			<p>
				<?php  //echo ($this->Form->input('subject', array('div'=>false, 'label'=>'Email Subject*', "class" => "text-input large-input")));?> 
				
			</p>
			<p>&nbsp;</p>
			<p>
				<?php // echo ($this->Form->input('content', array('div'=>false, 'label'=>'Content*', "class" => "text-input text-area ckeditor", 'rows'=>'30')));?> 
				
			</p>
			
			
			
			
			<p>
				<?php 
					
				//echo ($this->Form->submit('Submit', array('class' => 'button', "div"=>false)));?>
				
				<?php //echo $this->Html->link("Cancel", array('admin'=>true, 'controller'=>'templates', 'action'=>'index'), array("class"=>"button", "escape"=>false)); ?>
					
			</p>
			
		</fieldset>
		<?php 
			//echo $this->Form->end();
		?>
		<div class="clear"></div>
			
		<?php
			//echo ($this->Form->end());
		?>				
		</div> 	
	</div> 	
</div>--> 
<?php
/* } */
?>
<div id="templateDialog"></div>
<script type="text/javascript">
	$('a.view').facebox();
</script>