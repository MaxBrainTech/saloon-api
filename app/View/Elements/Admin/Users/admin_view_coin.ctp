<style>
    .column-left {
        width: 58% !important;
    }
</style>
<div class="content-box column-left"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">User Coin Detail</h3>

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

                                <?php echo $this->Html->link("Back", array('action' => 'coin_balance_index', 'user_id'=>$data['UserActivity']['user_id']), array("class" => "button", "escape" => false)); ?>

                            </div>

                        </td>
                    </tr>
                </tfoot>
                <tbody>                 
                   
                    <tr>
                        <td>User Coin</td>
                        <td><?php echo ($data['UserActivity']['coins']) ?></td>
                    </tr>

                    <tr>
                        <td>Cost</td>
                        <td><?php echo ($data['UserActivity']['cost']); ?></td>
                    </tr>
           
                    <tr>
                        <td>Type</td>
                        <td><?php if($data['UserActivity']['type']==0){
									echo "Debit";
								}else{
									echo "Credit";
								} ?></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><?php echo ($data['UserActivity']['description']); ?></td>
                    </tr>                   
                  
                    <tr>
                        <td>Profile Created</td>
                        <td><?php 
						if(strtotime($data['UserActivity']['created']))
						{
							echo ($this->Time->niceShort(strtotime($data['UserActivity']['created']))); 
						}
						else
						{
							echo ($this->Time->niceShort(strtotime(DEFAULT_DATE)));
						}
						//echo ($this->Time->niceShort(strtotime($data['UserActivity']['created']))); ?></td>
                    </tr>
                    <tr>
                        <td>Updated on</td>
                        <td><?php 
						if(strtotime($data['UserActivity']['modified']))
						{
							echo ($this->Time->niceShort(strtotime($data['UserActivity']['modified']))); 
						}
						else
						{
							echo ($this->Time->niceShort(strtotime(DEFAULT_DATE)));
						}
						//echo ($this->Time->niceShort(strtotime($data['UserActivity']['modified']))); ?></td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

