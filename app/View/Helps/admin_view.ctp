<style>
    .column-left {
        width: 58% !important;
      
    }
</style>
<div class="content-box column-left"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">Help Question Detail</h3>

        <ul class="content-box-tabs">
            <li></li>
        </ul>
        <?PHP //pr($help_question); ?>
        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

        <div style="display: none;" class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
        

            <table id="admins" class="wordwrap">

                <tfoot>
                    <tr>
                        <td colspan="2">
                            <div class="bulk-actions align-left">

                                <?php echo $this->Html->link("Back", array('action' => 'index', ucfirst(Configure::read('App.Roles.' . $help_question['HelpQuestion']['role_id']))), array("class" => "button", "escape" => false)); ?>

                            </div>

                        </td>
                    </tr>
                </tfoot>
                <tbody>
				    <tr>
                        <td>Email</td>
                        <td><?php echo ($help_question['HelpQuestion']['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td><?php echo ($help_question['HelpQuestion']['company_name']); ?></td>
                    </tr>
                    <tr>
                        <td>Kana</td>
                        <td><?php echo ($help_question['HelpQuestion']['kana']); ?></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><?php echo ($help_question['HelpQuestion']['gender']); ?></td>
                    </tr>
                    <tr>
                        <td>DOB</td>
                        <td><?php echo ($help_question['HelpQuestion']['dob']); ?></td>
                    </tr>
                    <tr>
                        <td>Tel Number</td>
                        <td><?php echo ($help_question['HelpQuestion']['tel']); ?></td>
                    </tr>
                    <tr>
                        <td>Zip Code</td>
                        <td><?php echo ($help_question['HelpQuestion']['zip_code']); ?></td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td><?php echo ($help_question['HelpQuestion']['city']); ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><?php echo ($help_question['HelpQuestion']['address']); ?></td>
                    </tr>
                    <tr>
                        <td>Prefecture</td>
                        <td><?php echo ($help_question['HelpQuestion']['prefecture']); ?></td>
                    </tr>
                    <tr>
                        <td>Job</td>
                        <td><?php echo ($help_question['HelpQuestion']['job']); ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><?php echo ($this->Layout->status($help_question['HelpQuestion']['status'])); ?></td>
                    </tr>
                    <tr>
                        <td>Profile Created</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($help_question['HelpQuestion']['created']))); ?></td>
                    </tr>
                    <tr>
                        <td>Updated on</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($help_question['HelpQuestion']['modified']))); ?></td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

