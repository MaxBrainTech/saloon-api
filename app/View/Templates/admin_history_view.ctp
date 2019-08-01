<style>
    .column-left {
        width: 58% !important;
    }
</style>
<div class="content-box column-left"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">Email History Of User</h3>

        <ul class="content-box-tabs">
            <li></li>
        </ul>
        <?PHP //pr($history); ?>
        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

        <div style="display: none;" class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
            <?php //echo $this->General->user_picture($history['User']['id'], $history['User']['image'], 'LARGE');?>

            <table id="admins" class="wordwrap">

                <tfoot>
                    <tr>
                        <td colspan="2">
                            <div class="bulk-actions align-left">

                                <?php echo $this->Html->link("Back", array('action' => 'history', ucfirst(Configure::read('App.Roles.' . $history['User']['role_id']))), array("class" => "button", "escape" => false)); ?>

                            </div>

                        </td>
                    </tr>
                </tfoot>

                <tbody>


                    <tr>
						<?php //echo strtotime(date('Y-m-d H:i:s'));;?>
                        <td colspan="2">
						<?php

						/* if(!empty($history['User']['fb_id']) || !empty($history['User']['twitter_id']) || !empty($history['User']['linkdin_id']) )
						{
							if(!empty($history['User']['social_media_image_url']) && isset($history['User']['social_media_image_url']) )
							{
								echo "<img src='".$history['User']['social_media_image_url']."'  width='56' alt='".$history['User']['username']."' />";
							}
							else
							{
								
								echo $this->Html->image(DEFAULT_USER_IMAGE,array('alt'=>$history['User']['username'],'width'=>'56'));
							}
						}
						
						else
						{
								echo $this->General->user_show_pic($history['User']['profile_image'], 'THUMB', $history['User']['username'], '');
						}		
					 */
					 
					  if(!empty($history['User']['profile_image']) &&  file_exists(WWW_ROOT . USER_THUMB_DIR
					. DS .$history['User']['profile_image'] ))
						{
							echo $this->General->user_show_pic($history['User']['profile_image'], 'SMALL',$history['User']['username'], $history['User']['id']);
						}
						elseif(!empty($history['User']['profile_image']))
						{
							echo "<img src='".$history['User']['profile_image']."'  width='56' alt='".$history['User']['username']."' />";
						}
						else
						{
							echo "<img src='".SITE_URL.DEFAULT_USER_IMAGE."'  width='56' alt='".$history['User']['username']."' />";
						}



						?></td>
                    </tr>

                   
                    <tr>
                        <td>First Name</td>
                        <td><?php echo ($history['User']['first_name']) ?></td>
                    </tr>

                    <tr>
                        <td>Last Name</td>
                        <td><?php echo ($history['User']['last_name']); ?></td>
                    </tr>
            <!--	<tr>
                            <td>Sex</td>
                            <td><?php //echo (Configure::read('App.Sex.'.$user['User']['sex']));                   ?></td>
                    </tr> -->

                    <tr>
                        <td>Username</td>
                        <td><?php echo ($history['User']['username']); ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php 
						
						if(!empty($history['User']['email']))
							{
							echo ($history['User']['email']);
							}
							else{
								echo $history['TemplatesUser']['receiver_email_id'];
							}
							

						?></td>
                    </tr>
					<?php
					if($history['Template']['slug'] == 'user_registration')
					{
					?>
					<tr>
                        <td>Email Verified</td>
                        <td>
						<?php
						
							if(empty($history['User']['is_email_verified']))
							{
								echo "Not Verified";
							}
							else{
								echo "Verified";
							}
						?>
						</td>
                    </tr>
					<?php
					
					}

					?>
					<tr>
                        <td>Email Type</td>
                        <td><?php echo Configure::read('App.Email.History.'.$history['Template']['slug']);?></td>
                    </tr>
					
                </tbody>

            </table>

        </div>

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

