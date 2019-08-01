			<div class="AccMidLeft">
              <div class="ChainBgLeft"></div>
              <ul class="AccMidLeftTop">
                <li><span class="AccLeTpIcon"><span class="glyphicon glyphicon-user"></span></span>
                  <label><a href="#" class="UserNameAccLe"><?php echo $user['User']['first_name']." ".$user['User']['last_name']?></a><span class="RedTxtAcc"><?php echo $user['User']['email'];?></span></label>
                </li>
                <li><span class="AccLeTpIcon"><span class="glyphicon glyphicon-home"></span></span>
                  <label>
                  <?php if(!empty($user['User']['address']) || !empty($user['User']['city']) || !empty($user['User']['state']) || !empty($user['User']['country	']) ) {
                  
                  	echo $user['User']['address'].", ".$user['User']['city'].", ".$user['User']['state'].", ".$user['User']['country'];
                    }else{
                  	echo "No Mailing Address";
                  	
                  	
                  }?>
                 <!--  Block 10 Ubi Crescent,
                  Unit 02-70 Lobby
                  D Ubi Techpark
                  Singapore 408564 -->
                  </label>
                </li>
              </ul>
              <ul class="AccNavLeft">
                <li class="active">
                <?php echo $this->Html->link('My Profile',array('controller'=>'users', 'action'=>'my_profile'), array('escape' => false));?>
                <!-- a href="#">My Profile</a -->
                  <ul class="AccSubNavLe">
                    <li class="active"><a href="#">Edit Profile</a></li>
                    <li><a href="#">Change Password</a></li>
                  </ul>
                </li>
                <li><a href="#">Voicemail / Call Logs</a></li>
                <li><a href="#">Upgrade Plan</a></li>
                <li><a href="#">Devices</a></li>
                <li>
                <?php echo $this->Html->link('Logout',array('controller'=>'users', 'action'=>'logout'), array('escape' => false));?>
                <!--a href="#">Logout</a-->
                </li>
              </ul>
            </div>