<style>
    .column-left {
        width: 58% !important;
    }
</style>
<div class="content-box column-left"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">Customer Detail</h3>

        <ul class="content-box-tabs">
            <li></li>
        </ul>
        <?PHP //pr($Customer); ?>
        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

        <div style="display: none;" class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
            <?php //echo $this->General->user_picture($Customer['Customer']['id'], $Customer['Customer']['image'], 'LARGE');?>

            <table id="admins" class="wordwrap">

                <tfoot>
                    <tr>
                        <td colspan="2">
                            <div class="bulk-actions align-left">

                                <?php echo $this->Html->link("Back", array('action' => 'index'), array("class" => "button", "escape" => false)); ?>

                            </div>

                        </td>
                    </tr>
                </tfoot>
                <tbody>
				  <tr>
                        <td>Name</td>
                        <td><?php echo ($Customer['Customer']['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Kana</td>
                        <td><?php echo ($Customer['Customer']['kana']); ?></td>
                    </tr>
                    <tr>
                        <td>Service Name</td>
                        <td><?php echo ($Customer['Customer']['know_about_company']); ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo ($Customer['Customer']['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><?php echo ($Customer['Customer']['gender']); ?></td>
                    </tr>
                    <tr>
                        <td>Date of Birthday</td>
                        <td><?php echo ($Customer['Customer']['dob']); ?></td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td><?php echo ($Customer['Customer']['tel']); ?></td>
                    </tr>
                    <tr>
                        <td>Zip Code</td>
                        <td><?php echo ($Customer['Customer']['zip_code']); ?></td>
                    </tr>


                    <tr>
                        <td>Address</td>
                        <td><?php echo ($Customer['Customer']['address1']); ?></td>
                    </tr>
                    <tr>
                        <td>Subscription of News</td>
                        <td><?php echo ($Customer['Customer']['subscription_of_news']); ?></td>
                    </tr>
                    <tr>
                        <td>Job</td>
                        <td><?php echo ($Customer['Customer']['job']); ?></td>
                    </tr>
                    <tr>
                        <td>How know about company? </td>
                        <td><?php echo ($Customer['Customer']['know_about_company']); ?></td>
                    </tr>
                    <tr>
                        <td>How did you come? </td>
                        <td><?php echo ($Customer['Customer']['how_did_you_come']); ?></td>
                    </tr>

                     <tr>
                        <td>Service Name </td>
                        <td><?php echo ($Customer['Service']['name']); ?></td>
                    </tr>
                   
                    
                    <tr>
                        <td>Profile Created</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($Customer['Customer']['created']))); ?></td>
                    </tr>
                    <tr>
                        <td>Updated on</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($Customer['Customer']['modified']))); ?></td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

