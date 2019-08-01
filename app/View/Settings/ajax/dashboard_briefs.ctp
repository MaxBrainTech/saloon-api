<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		<tr>
		  <th align="left" valign="middle">Setting</th>
		  <th align="center" valign="middle">Status</th>
		  <th align="center" valign="middle">Action</th>
		</tr>
		
		<tr>
		  <td align="left" valign="middle">Password</td>
		  <td align="center" valign="middle">-</td>
		  <td align="center" valign="middle">
		  <?php echo ($this->Html->link(__('Change Password',true), array('controller'=>'users', 'action'=>'change_password'), array('escape'=>false, 'title'=>'Change Password')));?>
		  </td>
		</tr>
		
		<tr>
		  <td align="left" valign="middle">Blog Alerts</td>
		  <td align="center" valign="middle"><?php echo $USER['blog_alerts']?'Active':'Inactive' ?></td>
		  <td align="center" valign="middle"><?php echo ($this->Html->link(__('Change',true), array('controller'=>'posts','action'=>'blog_subscribe','dashboard'), array('escape'=>false, 'title'=>'Change')));?></td>
		</tr>
		
		<tr>
		  <td align="left" valign="middle">Activity Alerts</td>
		  <td align="center" valign="middle"><?php echo $USER['alerts']?'Active':'Inactive' ?></td>
		  <td align="center" valign="middle"><?php echo ($this->Html->link(__('Change',true), array('controller'=>'users','action'=>'alerts_subscribe',$USER['id']), array('escape'=>false, 'title'=>'Change')));?></td>
		</tr>
		
	 </table>