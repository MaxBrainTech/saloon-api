<div class="AccMidRight">
              <ul class="PlanTypeTop">
                <li style="width:32%">Plan Type: <span class="RedColor"><?php echo $user['SubscriptionPlan']['plan_name']?></span></li>
                <li style="width:23%" >Plan Expire: <span class="RedColor">4 days</span></li>
                <li style="width:20%">Total Affilates: <span class="RedColor"><?php echo 1024?></span></li>
                <li>Last Login: <span class="RedColor"><?php echo $user['User']['last_login']?></span></li>
              </ul>
              <div class="AccMidRiCon">
                <h3>MY PROFILE</h3>
                <div class="AccRiConIn">
                  <ul class="EditProfFrm EditProfTxtRow">
                    <li >
                      <label>First Name:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['first_name']?></span></li>
                    <li>
                      <label>Last Name:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['last_name']?></span></li>
                    <li>
                      <label>Email Address:</label>
                      <span class="EditProfTxtRi"><a href="#"><?php echo $user['User']['email']?></a></span></li>
                   <?php /* <li>
                      <label>Password:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['password']?></span></li>
                  */?>
                  </ul>
                </div>
              </div>
            </div>