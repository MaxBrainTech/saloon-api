<style>
    .column-left {
        width: 100% !important;
    }
</style>
<div class="content-box column-left"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">Service Detail</h3>

        <ul class="content-box-tabs">
            <li></li>
        </ul>
        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

        <div style="display: none;" class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
          
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

                <?php if(isset($data['Esthe']) && !empty($data['Esthe'])){  ?>


                    <tr>
                        <td>Service Name</td>
                        <td><?php echo $service_name; ?></td>
                    </tr>  
                    <tr>
                        <td>Staus</td>
                        <td><?php echo $data['Esthe']['status']; ?></td>
                    </tr>
                    <?php if(ucfirst($data['Esthe']['status'])=="Bad" || $data['Esthe']['status']=="悪い" ) { ?>
                    <tr>
                        <td>Status Information</td>
                        <td><?php echo $data['Esthe']['status_text']; ?></td>
                    </tr>
                    <?php } ?>   
                    <tr>
                        <td>Allegy</td>
                        <td><?php echo ucfirst($data['Esthe']['allegy']); ?></td>
                    </tr>
                    <?php if($data['Esthe']['allegy']=="yes" || $data['Esthe']['allegy']=="Yes") { ?>
                    <tr>
                        <td>Allegy Information</td>
                        <td><?php echo $data['Esthe']['allegy_text']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Medicine</td>
                        <td><?php echo ucfirst($data['Esthe']['medicine']); ?></td>
                    </tr>
                    <?php if($data['Esthe']['medicine']=="yes" || $data['Esthe']['medicine']=="Yes") { ?>
                    <tr>
                        <td>Medicine Information</td>
                        <td><?php echo $data['Esthe']['medicine_text']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Hospital</td>
                        <td><?php echo ucfirst($data['Esthe']['hospital']); ?></td>
                    </tr>
                    <?php if($data['Esthe']['hospital']=="yes" || $data['Esthe']['hospital']=="Yes") { ?>
                    <tr>
                        <td>Hospital Information</td>
                        <td><?php echo $data['Esthe']['hospital_text']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Medical History</td>
                        <td><?php echo ucfirst($data['Esthe']['medical_history']); ?></td>
                    </tr>
                    <?php if($data['Esthe']['medical_history']=="yes" || $data['Esthe']['medical_history']=="Yes") { ?>
                    <tr>
                        <td>Medical History Information</td>
                        <td><?php echo $data['Esthe']['medical_history_text']; ?></td>
                    </tr>
                    <?php } ?>


                    <tr>
                        <td>Period</td>
                        <td><?php echo ucfirst($data['Esthe']['period']); ?></td>
                    </tr>
                    <tr>
                        <td>Sleep Start Time</td>
                        <td><?php echo $data['Esthe']['sleep_start_time']; ?></td>
                    </tr>
                    <tr>
                        <td>Sleep Average Time</td>
                        <td><?php echo $data['Esthe']['sleep_time_avg']; ?></td>
                    </tr>
                    <tr>
                        <td>Concern</td>
                        <td><?php echo ucfirst($data['Esthe']['concern']); ?></td>
                    </tr>
                    <tr>
                        <td>Concern Extra</td>
                        <td><?php echo ucfirst($data['Esthe']['concern_extra']); ?></td>
                    </tr>
                    <tr>
                        <td>Concern Date</td>
                        <td><?php  echo  ($this->Time->niceShort(strtotime($data['Esthe']['concern_date']))); ?></td>
                    </tr>         
                    <tr>
                        <td>Itchy</td>
                        <td><?php echo ucfirst($data['Esthe']['itchy']); ?></td>
                    </tr>


                    <?php if($data['Esthe']['itchy']=="yes" || $data['Esthe']['itchy']=="Yes") { ?>
                    <tr>
                        <td>Itchy Information</td>
                        <td><?php echo $data['Esthe']['itchy_text']; ?></td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td>Cosmetic Name</td>
                        <td><?php echo ucfirst($data['Esthe']['cosmetic_name']); ?></td>
                    </tr>    
                    <tr>
                        <td>How to Maintain</td>
                        <?php 
                      
                        
                        ?>
                        <td><?php 
                         $how_to_maintain = json_decode($data['Esthe']['how_to_maintain'], true);
                         $json = json_sort($how_to_maintain, true);
                        foreach($json as $key => $value){
                            echo $key." : ".$value."<br/>";
                        }
                        ?></td>


                    </tr>    



                    <tr>
                        <td>Treatment</td>
                        <td><?php echo ucfirst($data['Esthe']['treatment']); ?></td>
                    </tr>
                    <tr>
                        <td>Esthe Experience</td>
                        <td><?php echo ucfirst($data['Esthe']['esthe_experience']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Esthe']['esthe_experience'])=="Yes") { ?>
                    <tr>
                        <td>Esthe Experience Information</td>
                        <td><?php echo $data['Esthe']['esthe_experience_text']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Surgery</td>
                        <td><?php echo ucfirst($data['Esthe']['surgery']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Esthe']['surgery'])=="Yes") { ?>
                    <tr>
                        <td>Surgery Information</td>
                        <td><?php echo $data['Esthe']['surgery_text']; ?></td>
                    </tr>
                    <?php } ?>    
                    <tr>
                        <td>Contact Lense</td>
                        <td><?php echo ucfirst($data['Esthe']['contact_lense']); ?></td>
                    </tr>
                  <tr>
                        <td>Profile Created</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($data['Esthe']['created']))); ?></td>
                    </tr>
                    <tr>
                        <td>Updated on</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($data['Esthe']['modified']))); ?></td>
                    </tr>
                <?php } ?>        

                  <?php if(isset($data['Eyelush']) && !empty($data['Eyelush'])){  ?>


                    <tr>
                        <td>Service Name</td>
                        <td><?php echo $service_name; ?></td>
                    </tr>  
                    <tr>
                        <td>Contact Lense</td>
                        <td><?php echo ucfirst($data['Eyelush']['contact_lense']); ?></td>
                    </tr>
                    <tr>
                        <td>Dry Eye</td>
                        <td><?php echo ucfirst($data['Eyelush']['dry_eye']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Eyelush']['dry_eye'])=="Yes") { ?>
                    <tr>
                        <td>Dry Eye Information</td>
                        <td><?php echo $data['Eyelush']['dry_eye_text']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Sick Eye</td>
                        <td><?php echo ucfirst($data['Eyelush']['sick_eye']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Eyelush']['sick_eye'])=="Yes") { ?>
                    <tr>
                        <td>Sick Eye Information</td>
                        <td><?php echo $data['Eyelush']['sick_eye_text']; ?></td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td>Congestion</td>
                        <td><?php echo ucfirst($data['Eyelush']['congestion']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Eyelush']['congestion'])=="Yes") { ?>
                    <tr>
                        <td>Congestion Information</td>
                        <td><?php echo $data['Eyelush']['congestion_text']; ?></td>
                    </tr>
                    <?php } ?>  

                    <tr>
                        <td>Surgery</td>
                        <td><?php echo ucfirst($data['Eyelush']['surgery']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Eyelush']['surgery'])=="Yes") { ?>
                    <tr>
                        <td>Surgery Information</td>
                        <td><?php echo $data['Eyelush']['surgery_text']; ?></td>
                    </tr>
                    <?php } ?>  
                    <tr>
                        <td>Lasik</td>
                        <td><?php echo ucfirst($data['Eyelush']['lasik']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Eyelush']['lasik'])=="Yes") { ?>
                    <tr>
                        <td>Lasik Information</td>
                        <td><?php echo $data['Eyelush']['lasik_text']; ?></td>
                    </tr>
                    <?php } ?> 

                     



                     <tr>
                        <td>Eye Perm</td>
                        <td><?php echo ucfirst($data['Eyelush']['eye_perm']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Eyelush']['eye_perm'])=="Yes") { ?>
                    <tr>
                        <td>Eye Perm Information</td>
                        <td><?php echo $data['Eyelush']['eye_perm_text']; ?></td>
                    </tr>
                    <?php } ?> 


                     <tr>
                        <td>Allegy</td>
                        <td><?php echo ucfirst($data['Eyelush']['allegy']); ?></td>
                    </tr>
                    <?php if(ucfirst($data['Eyelush']['allegy'])=="Yes") { ?>
                    <tr>
                        <td>Allegy Information</td>
                        <td><?php echo $data['Eyelush']['allegy_text']; ?></td>
                    </tr>
                    <?php } ?> 
                    <tr>
                        <td>Pregnancy</td>
                        <td><?php echo ucfirst($data['Eyelush']['pregnancy']); ?></td>
                      </tr>      
                     <tr>
                        <td>Cleansing</td>
                        <td><?php echo ucfirst($data['Eyelush']['cleansing']); ?></td>
                      </tr>
                      <tr>
                        <td>Agreement</td>
                        <td><?php echo ucfirst($data['Eyelush']['agreement']); ?></td>
                      </tr>
                   

                    
                    <tr>
                        <td>Profile Created</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($data['Eyelush']['created']))); ?></td>
                    </tr>
                    <tr>
                        <td>Updated on</td>
                        <td><?php echo ($this->Time->niceShort(strtotime($data['Eyelush']['modified']))); ?></td>
                    </tr>
                    <?php } ?>            

                    <?php if(isset($data['Body']) && !empty($data['Body'])){  ?>


                        <tr>
                            <td>Service Name</td>
                            <td><?php echo $service_name; ?></td>
                        </tr>  
                        <tr>
                            <td>Staus</td>
                            <td><?php echo $data['Body']['status']; ?></td>
                        </tr>
                        <?php if(ucfirst($data['Body']['status'])=="Bad" || $data['Body']['status']=="悪い" ) { ?>
                        <tr>
                            <td>Status Information</td>
                            <td><?php echo $data['Body']['status_text']; ?></td>
                        </tr>
                        <?php } ?>  
                        <tr>
                            <td>Allegy</td>
                            <td><?php echo ucfirst($data['Body']['allegy']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['Body']['allegy'])=="Yes") { ?>
                        <tr>
                            <td>Allegy Information</td>
                            <td><?php echo $data['Body']['allegy_text']; ?></td>
                        </tr>
                        <?php } ?>     


                         <tr>
                            <td>Medicine</td>
                            <td><?php echo ucfirst($data['Body']['medicine']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['Body']['medicine'])=="Yes") { ?>
                        <tr>
                            <td>Medicine Information</td>
                            <td><?php echo $data['Body']['medicine_text']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td>Hospital</td>
                            <td><?php echo ucfirst($data['Body']['hospital']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['Body']['hospital'])=="Yes") { ?>
                        <tr>
                            <td>Hospital Information</td>
                            <td><?php echo $data['Body']['hospital_text']; ?></td>
                        </tr>
                        <?php } ?>

                         <tr>
                            <td>Medical History</td>
                            <td><?php echo ucfirst($data['Body']['medical_history']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['Body']['medical_history'])=="Yes") { ?>
                        <tr>
                            <td>Medical History Information</td>
                            <td><?php echo $data['Body']['medical_history_text']; ?></td>
                        </tr>
                        <?php } ?>

                         <tr>
                            <td>Period</td>
                            <td><?php echo ucfirst($data['Body']['period']); ?></td>
                        </tr>
                        <tr>
                            <td>Sleep Start Time</td>
                            <td><?php echo $data['Body']['sleep_start_time']; ?></td>
                        </tr>
                        <tr>
                            <td>Sleep Average Time</td>
                            <td><?php echo $data['Body']['sleep_time_avg']; ?></td>
                        </tr>   

                        <tr>
                            <td>Profile Created</td>
                            <td><?php echo ($this->Time->niceShort(strtotime($data['Body']['created']))); ?></td>
                        </tr>
                        <tr>
                            <td>Updated on</td>
                            <td><?php echo ($this->Time->niceShort(strtotime($data['Body']['modified']))); ?></td>
                        </tr>
                        <?php } ?>    





                         <?php if(isset($data['HairRemoval']) && !empty($data['HairRemoval'])){  ?>


                        <tr>
                            <td>Service Name</td>
                            <td><?php echo $service_name; ?></td>
                        </tr>  
                        
                        <tr>
                            <td>Allegy</td>
                            <td><?php echo ucfirst($data['HairRemoval']['allegy']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['HairRemoval']['allegy'])=="Yes") { ?>
                        <tr>
                            <td>Allegy Information</td>
                            <td><?php echo $data['HairRemoval']['allegy_text']; ?></td>
                        </tr>
                        <?php } ?>     


                         <tr>
                            <td>Medicine</td>
                            <td><?php echo ucfirst($data['HairRemoval']['medicine']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['HairRemoval']['medicine'])=="Yes") { ?>
                        <tr>
                            <td>Medicine Information</td>
                            <td><?php echo $data['HairRemoval']['medicine_text']; ?></td>
                        </tr>
                        <?php } ?>
                        
                         <tr>
                            <td>Medical History</td>
                            <td><?php echo ucfirst($data['HairRemoval']['medical_history']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['HairRemoval']['medical_history'])=="Yes") { ?>
                        <tr>
                            <td>Medical History Information</td>
                            <td><?php echo $data['HairRemoval']['medical_history_text']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td>Concern</td>
                            <td><?php echo ucfirst($data['HairRemoval']['concern']); ?></td>
                        </tr>
                         <tr>
                            <td>Period</td>
                            <td><?php echo ucfirst($data['HairRemoval']['period']); ?></td>
                        </tr>
                        <tr>
                            <td>Esthe Experience</td>
                            <td><?php echo ucfirst($data['HairRemoval']['esthe_experience']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['HairRemoval']['esthe_experience'])=="Yes") { ?>
                        <tr>
                            <td>Esthe Experience Information</td>
                            <td><?php echo $data['HairRemoval']['esthe_experience_text']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td>Surgery</td>
                            <td><?php echo ucfirst($data['HairRemoval']['surgery']); ?></td>
                        </tr>
                        <?php if(ucfirst($data['HairRemoval']['surgery'])=="Yes") { ?>
                        <tr>
                            <td>Surgery Information</td>
                            <td><?php echo $data['HairRemoval']['surgery_text']; ?></td>
                        </tr>
                        <?php } ?>  

                        <tr>
                            <td>Profile Created</td>
                            <td><?php  echo ($this->Time->niceShort(strtotime($data['HairRemoval']['created']))); ?></td>
                        </tr>
                        <tr>
                            <td>Updated on</td>
                            <td><?php echo ($this->Time->niceShort(strtotime($data['HairRemoval']['modified']))); ?></td>
                        </tr>
                        <?php } ?>       



                </tbody>

            </table>

        </div>

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

<?php 
 function json_sort(&$json, $ascending = true) {
    $names = [];
    
    // Creating a named array for sorting
    foreach($json AS $name => $value) {
        $names[] = $name;
    }
    
    if($ascending) {
        asort($names);
    } else {
        arsort($names);
    }
    
    $result = [];
    
    foreach($names AS $index => $name) {
        // Sorting Sub-Data
        if(is_array($json[$name]) || is_object($json[$name])) {
            json_sort($json[$name], $ascending);
        }
        
        $result[$name] = $json[$name];
    }
   
    return $result;
}

?>