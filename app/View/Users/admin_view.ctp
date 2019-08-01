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
                        <td>Email</td>
                        <td><?php echo ($user['User']['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td><?php echo ($user['User']['company_name']); ?></td>
                    </tr>
                    <tr>
                        <td>Kana</td>
                        <td><?php echo ($user['User']['kana']); ?></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><?php echo ($user['User']['gender']); ?></td>
                    </tr>
                    <tr>
                        <td>DOB</td>
                        <td><?php echo ($user['User']['dob']); ?></td>
                    </tr>
                    <tr>
                        <td>Tel Number</td>
                        <td><?php echo ($user['User']['tel']); ?></td>
                    </tr>
                    <tr>
                        <td>Zip Code</td>
                        <td><?php echo ($user['User']['zip_code']); ?></td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td><?php echo ($user['User']['city']); ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><?php echo ($user['User']['address']); ?></td>
                    </tr>
                    <tr>
                        <td>Prefecture</td>
                        <td><?php echo ($user['User']['prefecture']); ?></td>
                    </tr>
                    <tr>
                        <td>Job</td>
                        <td><?php echo ($user['User']['job']); ?></td>
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

