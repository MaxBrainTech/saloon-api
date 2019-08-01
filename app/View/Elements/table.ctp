<div class="AccMidRight">
    <ul class="AccTabMenuTop">
		<li><?php
            echo $this->Html->link("Active", array('controller' => 'user_ads', 'action' => 'index','active'), array("class" => "", "escape" => false));
        ?></li>
		<li><?php
            echo $this->Html->link("Pending", array('controller' => 'user_ads', 'action' => 'index','pending'), array("class" => "", "escape" => false));
        ?></li>
		<li>
		<?php
            echo $this->Html->link("Rejected", array('controller' => 'user_ads', 'action' => 'index','rejected'), array("class" => "", "escape" => false));
        ?>
		
		</li>
		<li>
		<?php
            echo $this->Html->link("Inactive", array('controller' => 'user_ads', 'action' => 'index','inactive'), array("class" => "", "escape" => false));
        ?>
		</li>
		<li>
		<?php
            echo $this->Html->link("Finished", array('controller' => 'user_ads', 'action' => 'index','finished'), array("class" => "", "escape" => false));
        ?>
		</li>
		<li class="FloatRight">
		
		<?php
            echo $this->Html->link("Post New Ad", array('controller' => 'user_ads', 'action' => 'add'), array("class" => "PostAd", "escape" => false));
        ?>
		
		</li>
    </ul>
	<?php  $this->Layout->sessionFlash(); ?>
    <div class="TabAccConBox">
		<div class="AccListTab"> 
			<?php
			if (!empty($data)) 
			{
				$this->ExPaginator->options = array('url' => $this->passedArgs);
				?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th><?php echo ($this->ExPaginator->sort('UserAd.title', 'Title')); ?></th>
					<th><?php echo ($this->ExPaginator->sort('Category.name', 'Category')); ?></th>
					<th><?php echo ($this->ExPaginator->sort('UserAd.ad_type', 'Ad Type')); ?></th>
					<th><?php echo ($this->ExPaginator->sort('UserAd.created', 'Created')) ?></th>
					<th><?php echo ($this->ExPaginator->sort('UserAd.status', 'Status')) ?></th>
					<th>Action</th>
				</tr>
				<?php
            $alt = 0;
            //pr($data);die;
            foreach ($data as $value) 
			{
                ?>
                <tr>

                    <td>
					<?php 
					echo $value['UserAd']['title']."<br>";
					
					if(!empty($value['UserAd']['user_photo']) &&  file_exists(WWW_ROOT . USERADS_THUMB_DIR
				. DS .$value['UserAd']['user_photo'] ))
					{	
						echo $this->General->user_ads_pic($value['UserAd']['user_photo'], 'SMALL','', $value['UserAd']['id']);
					}elseif(!empty($value['User']['profile_image']) &&  file_exists(WWW_ROOT . USER_THUMB_DIR . DS . $value['User']['profile_image']))
					{ 
						echo $this->General->user_show_pic($value['User']['profile_image'], 'SMALL',$value['User']['username'], $value['User']['id']);
					}
					elseif(!empty($value['User']['social_media_image_url']))
					{
						echo "<img src='".$value['User']['social_media_image_url']."' width='56'  alt='".$value['User']['username']."' />";
					}
					else
					{
						echo "<img src='".SITE_URL.DEFAULT_USER_IMAGE."'  width='56' alt='".$value['UserAd']['user_photo']."' />";
					}					
					?></td>
                    <td>
					<?php echo ($value['Category']['name']); ?>
					<?php if(!empty($value['SubCategory']['name'])){?>,
					<?php echo ($value['SubCategory']['name']); ?>
					<?php }?>
					</td>                   
                    <td><?php echo $this->Layout->adType($value['UserAd']['ad_type']); ?></td>

                    <td>
					<?php 
					
					echo date("M d, Y",strtotime($value['UserAd']['created']));  
					?>
					</td>

                    <td>
					<?php 
					echo $this->Layout->adStatus($value['UserAd']['status']);
					if(($value['UserAd']['status']=='2')&&($value['UserAd']['reason']!="")){
					echo "<br>".$this->Html->link("<span style='font-size:10px;'>View Reason</span>","#",array('onclick'=>"window.open('".SITE_URL."/user_ads/view_reason/".$value['UserAd']['id']."','Window1','menubar=no, width=400, height=300, toolbar=no, scrollbars=yes'); return false;", 'class' =>'in', 'escape' =>false)); 				
					}
					?>					
                    </td>
                    <td>
                        <!-- Icons -->
                        <?php
                        echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('controller' => 'user_ads', 'action' => 'edit', $value['UserAd']['id']), array('escape' => false)));
                        ?>
                        <?php
							echo ($this->Html->link($this->Html->image('admin/cross.png', array('title' => 'Delete', 'alt' => 'Delete')), array('controller' => 'user_ads', 'action' => 'delete', $value['UserAd']['id']), array('escape' => false, 'onclick' => 'javascript:return confirm_delete(this)')));						
                        ?>
                        <?php
						
                        //echo ($this->Html->link($this->Html->image('admin/view.jpg', array('title' => 'View', 'alt' => 'View')), array('controller' => 'user_ads', 'action' => 'view', $value['UserAd']['id']), array('escape' => false)));
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
			
					
			</table>
			  <?php
			} 
			else 
			{
				echo ($this->element('admin_flash_info', array('message' => 'NO RESULTS FOUND')));
			}
			?>
			<div>
			<?php			
				$this->Paginator->options(array(
					'url' => $this->passedArgs,
				));
				echo $this->element('front_pagination', array("paging_model_name" => "UserAd", "total_title" => "UserAds"));
            ?>
			</div>
		</div>
    </div>
</div>    
<div class="clear"></div>