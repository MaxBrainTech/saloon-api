<div class="RightAcc">
  <div class="MyDetailR">
	 <h3>My Profile</h3>
	 <ul class="MyDetailAc">
		<li>
		  <label>Name</label>
		  <span><?php echo ($USER['name']);?></span></li>
		<li>
		  <label>Company</label>
		  <span><?php echo ($USER['company']);?></span></li>
		<li>
		  <label>Address</label>
		  <span><?php echo ($USER['country_id']);?></span></li>
		<li>
		  <label>Phone</label>
		  <span><?php echo ($USER['phone']);?></span></li>
		<li>
		  <label>Email</label>
		  <span><?php echo ($USER['email']);?></span></li>
		<li><?php echo ($this->Html->link($this->Html->image('images/update_detail.png', array('alt'=>'UPDATE MY PROFILE')), array('controller'=>'users', 'action'=>'edit'), array('escape'=>false, 'title'=>'UPDATE MY PROFILE')));?></li>
	 </ul>
  </div>
  <div class="TalkPre">
	 <h3>Talk To A Person</h3>
	 <ul class="TalkPreCon">
		<li><span>Phone:UK +44 2071 933 644</span></li>
		<li><span>Phone:AUS +61 2 8011 3034</span></li>
		<li><span>Email: <a href="mailto:help@octalsoftware.com">help@octalsoftware.com</a></span></li>
	 </ul>
  </div>
</div>