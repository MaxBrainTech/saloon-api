<?php if(!empty($data)){
		$this->ExPaginator->options = array('url' => $this->passedArgs);?>
<table>
				
	<thead>
		<tr>
			<th><?php echo ($this->ExPaginator->sort('Page.slug', 'Page Name'))?></th>
			<th><?php echo ($this->ExPaginator->sort('Page.title', 'Page Title'))?></th>
			<th><?php echo ($this->ExPaginator->sort('Page.modified', 'Updated'))?></th>
			<th width="16px">Action</th>
		</tr>
		
	</thead>
 
	<tfoot>
		<tr>
			<td colspan="4">
				<div class="bulk-actions align-left">
					
					
				</div>
				<?php 
				
				$this->Paginator->options(array(
					
					 'url' => $this->passedArgs,
					 
				));
				echo $this->element('Admin/admin_pagination', array("paging_model_name"=>"Page", "total_title"=>"Pages")); ?>
				
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
				<?php //echo ($this->Html->link($value['Page']['slug'],array('action'=>'display',$value['Page']['slug']),array('title'=>'View Page','target'=>'blank')));
					echo $this->General->wrap_long_txt($value['Page']['slug'],0,50);
				?>
				
			</b></td>			
			<td><?php echo ($value['Page']['title']);?></td>		
			<td><?php echo ($this->Time->niceShort($value['Page']['modified']));?></td>			
			<td>
				<!-- Icons -->
				 <?php 
					echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title'=>'Edit','alt'=>'Edit')), array('action'=>'edit', $value['Page']['id']), array('escape'=>false)));
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