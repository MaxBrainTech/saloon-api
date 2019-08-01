<style>
    .column-left {
        width: 58% !important;
    }
</style>
<div class="content-box column-left"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">User Detail</h3>

        <ul class="content-box-tabs">
            <li></li>
        </ul>
        <?PHP //pr($user); ?>
        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

        <div style="display: none;" class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
            <?php //echo $this->General->user_picture($user['User']['id'], $user['User']['image'], 'LARGE');?>

            <table id="admins" class="wordwrap">

                <tfoot>
                    <tr>
                        <td colspan="2">
                            <div class="bulk-actions align-left">

                                <?php echo $this->Html->link("Back", array('action' => 'index', ucfirst(Configure::read('App.Roles.' . $user['User']['role_id']))), array("class" => "button", "escape" => false)); ?>

                            </div>

                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>						
                        <td colspan="2">
<?php		
		if(!empty($user['User']['profile_image']) &&  file_exists(WWW_ROOT . USER_THUMB_DIR
	. DS .$user['User']['profile_image']) )
		{
			echo $this->General->user_show_pic($user['User']['profile_image'], 'SMALL',$user['User']['username'], 'main_profile_photo');
		}
		elseif(!empty($user['User']['social_media_image_url']))
		{
			echo "<img src='".$user['User']['social_media_image_url']."' width='60' height='60'  alt='".$user['User']['username']."' id='main_profile_photo' />";
		}
		else
		{
			echo $this->Html->image('no-picture.gif', array('alt'=>'img'  ,'id'=>"main_profile_photo", 'width'=>'60', 'height'=>'60'));
		}
?>				
						</td>
                    </tr>
					<?php /* ?>
                    <tr>
                        <td>First Name</td>
                        <td><?php echo ($user['User']['first_name']) ?></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><?php echo ($user['User']['last_name']); ?></td>
                    </tr>                   
                    <tr>
                        <td>Gender</td>
                        <td><?php echo Configure::read('App.Sex.'.$user['User']['gender']);  ?></td>
                    </tr>
					<tr>
                        <td>Last Login</td>
                        <td>
						<?php
						if(!empty($user['User']['last_login']))
						{
							echo date('jS F Y G:ia',strtotime($user['User']['last_login'])); 
						}
						?>
						</td>
                    </tr>
					<?php  */?>
                    
                   
                    <tr>
                        <td>Email</td>
                        <td><?php echo ($user['User']['email']); ?></td>
                    </tr>
                     <tr>
                        <td>Alternate Email</td>
                        <td><?php echo ($user['User']['alternate_email']); ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><?php echo ($this->Layout->status($user['User']['status'])); ?></td>
                    </tr>
                    <tr>
                        <td>Profile Created</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($user['User']['created']))); ?></td>
                    </tr>
                    <tr>
                        <td>Updated on</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($user['User']['modified']))); ?></td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

