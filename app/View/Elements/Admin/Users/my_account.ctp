<div id="Wrapper">
<div id="sidebar" style="border:1px solid #CCC;">
    <div class="mainNav">
        <!-- Responsive nav -->
        <!-- Main nav -->
        <ul class="nav">
<!--            <li class="none"><a href="dashboard.html">Dashboard</a></li>-->
<li class="">
                <a title="" href="javascript:void(0);">Category</a>
                <ul style="display: none;">
                    <li><a title="" href="https://www.girlforhire.com/admin/categories" class="">All Category</a></li>
                    <li><a title="" href="https://www.girlforhire.com/admin/categories/add" class="">Add Category</a></li>
                </ul>
            </li>                  
            <li class="none">
                <a title="" href="https://www.girlforhire.com/admin/ads">Ads</a>                  
            </li>                
            <li class="none"><a title="" href="https://www.girlforhire.com/admin/users">Users</a>
            </li>
        </ul>
    </div>
</div>
<?php $user = $userInfo['User']; ?>

	<div id="containerDiv">
		<!--<span class="noac">No account yet</span>-->
		<h3>My Account</h3>
		<div class="inner clearfix">
			<div class="">
						<fieldset>               
							<p>
								<span>Full Name : </span>
								<span><?php echo $user['display_name']; ?></span>
							</p>
							<p>
								<span>Username : </span>
								<span><?php echo $user['username']; ?></span>
							</p>
							<p>
								<span>Email : </span>
								<span><?php echo $user['email']; ?></span>
							</p>
							<p>
								<span>Gender : </span>
								<span><?php echo Configure::read('App.FrontSex.'.$user['gender']);  ?></span>
							</p>
							<p>
								<span>Status : </span>
								<span><?php echo ($this->Layout->status($user['status'])); ?></span>
							</p>
							<p>
								<span>Profile Created : </span>
								<span><?php echo ($this->Time->niceShort(strtotime($user['created']))); ?></span>
							</p>
							<p>
								<span>Updated On : </span>
								<span><?php echo ($this->Time->niceShort(strtotime($user['modified']))); ?></span>
							</p>
							
						</fieldset>
						<!-- End .clear -->
						
					</div> <!-- End #tab2 -->
			</div>
		
    </div>
<!-- end login -->
</div><div class="clear"></div>
