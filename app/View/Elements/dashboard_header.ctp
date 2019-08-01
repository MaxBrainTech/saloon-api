<ul class="MidAccTop">
	<li class="Pic">
		<?php echo ($this->Html->link($this->General->user_picture($USER['id'], $USER['image'], 'SMALL'),'#picture', array('escape'=>false, 'title'=>'Change Picture')));?>
	</li>
	<li>
	  <h3>My Briefs</h3>
	  <p>
		 <label>Launched</label>
		 <span><?php echo ($USER['brief_count']);?></span></p>
	  <p>
		 <label>Awarded</label>
		 <span>90</span></p>
	</li>
	<li class="Credit">
	  <h3>My Credit</h3>
	  <p>$100</p>
	</li>
	<li class="AccBtn"><a href="#"><?php echo ($this->Html->link($this->Html->image('images/create_a_brief.png', array('alt'=>'CREATE A BRIEF')), array('controller'=>'briefs', 'action'=>'add'), array('escape'=>false, 'title'=>'CREATE A BRIEF')));?></a></li>
</ul>