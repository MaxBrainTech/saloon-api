<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		
		<h3 style="cursor: s-resize;">Admin Profile</h3>
		
		<ul class="content-box-tabs">
			<li></li>
		</ul>
		
		<div class="clear"></div>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<div style="display: none;" class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
			
			<?php
				//$this->Layout->sessionFlash();			  
			?>
			<table id="admins">
				
				<thead>
					<tr>
						<td>First Name</td>
						<td><?php echo ($user['User']['first_name'])?></td>
					</tr>
					
				</thead>
			 
				<tfoot>
					<tr>
						<td colspan="2">
							<div class="bulk-actions align-left">
								
								<?php echo $this->Html->link("Back", array('action'=>'index','Admin'), array("class"=>"button", "escape"=>false)); ?>
								
							</div>
							
						</td>
					</tr>
				</tfoot>
			 
				<tbody>
					<tr class="alt-row">
						<td>Last Name</td>
						<td><?php echo ($user['User']['last_name']);?></td>
					</tr>
					<tr>
						<td>Username</td>
						<td><?php echo ($user['User']['username']);?></td>
					</tr>
					<tr class="alt-row">
						<td>Email</td>
						<td><?php echo ($user['User']['email']);?></td>
					</tr>
					<tr>
						<td>Status</td>
						<td><?php echo ($this->Layout->status($user['User']['status']));?></td>
					</tr>
					<tr class="alt-row">
						<td>Created on</td>
						<td><?php echo ($this->Time->niceShort(strtotime($user['User']['created'])));?></td>
					</tr>
					<tr>
						<td>Modified on</td>
						<td><?php echo ($this->Time->niceShort(strtotime($user['User']['modified'])));?></td>
					</tr>
				</tbody>
				
			</table>
			
		</div> 
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
