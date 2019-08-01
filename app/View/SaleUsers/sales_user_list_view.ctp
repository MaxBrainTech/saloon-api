<style type="text/css">
	.mb-20 {
    margin-bottom: 20px;
}
</style>
<div class="row mb-20">
  <div class="col-md-4"><strong>Name</strong></div>
  <div class="col-md-8"><?php echo $data['kana']." ".$data['kanji']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Email</strong></div>
  <div class="col-md-8"><?php echo $data['email']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Phone Number</strong></div>
  <div class="col-md-8"><?php echo $data['tel']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Date of Birth</strong></div>
  <div class="col-md-8"><?php echo date('Y年m月d日', strtotime($data['dob'])); ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Zip Code</strong></div>
  <div class="col-md-8"><?php echo $data['zip_code']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Address</strong></div>
  <div class="col-md-8"><?php echo $data['address']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>User Sales Code</strong></div>
  <div class="col-md-8"><?php echo $data['unique_sales_code']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Master Sales Code</strong></div>
  <div class="col-md-8"><?php echo $data['master_sales_code']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Bank Name</strong></div>
  <div class="col-md-8"><?php echo $data['bank_name']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Bank Name Kana</strong></div>
  <div class="col-md-8"><?php echo $data['bank_name_kana']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Bank Number</strong></div>
  <div class="col-md-8"><?php echo $data['bank_number']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Branch</strong></div>
  <div class="col-md-8"><?php echo $data['branch']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Branch Kana</strong></div>
  <div class="col-md-8"><?php echo $data['branch_kana']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Branch Code</strong></div>
  <div class="col-md-8"><?php echo $data['branch_code']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>What Kind Of Bank</strong></div>
  <div class="col-md-8"><?php echo $data['what_kind_of_bank']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Account Number</strong></div>
  <div class="col-md-8"><?php echo $data['account_number']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Account Holder Name Kana</strong></div>
  <div class="col-md-8"><?php echo $data['account_holder_name_kana']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-4"><strong>Account Holder Name</strong></div>
  <div class="col-md-8"><?php echo $data['account_holder_name']; ?></div>
</div>
<div class="row mb-20">
  <div class="col-md-6">
  	<strong>DL Front Image</strong>
  	<p>
  		<img src="/uploads/dl/front/<?php echo $data['dl_front_img']; ?>" width="250" height="300" /><br>
  		<a class="kitchen_list_table_link" href="/uploads/dl/front/<?php echo $data['dl_front_img']; ?>" download="DL_Front">Download File</a>
  	</p>
  </div>
  <div class="col-md-6">
  	<strong>DL Back Image</strong>
  	<p>
  		<img src="/uploads/dl/back/<?php echo $data['dl_back_img']; ?>" width="250" height="300" /><br>
  		<a class="kitchen_list_table_link" href="/uploads/dl/back/<?php echo $data['dl_back_img']; ?>" download="DL_Back">Download File</a>
  	</p>
  </div>
</div>



<a class="good-bitee-button" href="/sale_users/sales_user_list">Back to list</a>