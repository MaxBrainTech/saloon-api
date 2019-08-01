<?php
/* 
Here we are define some configs variable for site uses.

***************** SITE SETTING *********************/

$siteFolder = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])));
$config['App.siteFolder'] = $siteFolder;

$config['App.SiteUrl'] 		= 'http://' . $_SERVER['HTTP_HOST'] . $siteFolder;
$config['Site.title'] ='JTSボード';
$site_url =  "https://" . $_SERVER['HTTP_HOST'] . $siteFolder;
/*
if(isset($_SERVER['HTTPS']))
{
    if ($_SERVER["HTTPS"] == "on") 
    {
		$config['App.SiteUrl'] 		= 'https://' . $_SERVER['HTTP_HOST'] . $siteFolder;
		$site_url =  "https://" . $_SERVER['HTTP_HOST'] . $siteFolder;
    }
} */
// echo $config['App.SiteUrl'];die;
define('SITE_URL', $site_url);
$config['App.AdminMail']  		= "do.not.reply@zedinternational.net";
$config['App.SITENAME']  		= "jtsboard.com";
$config['App.AdminCCMail']  	= "do.not.reply@zedinternational.net";
$config['App.Session.Time']  	= 1000000000;

/*FOR PAGING*/
$config['App.PageLimit']  	= 10;
$config['App.AdminPageLimit'] = 10;

/*FOR GENERAL STATUS*/
$config['App.Status.inactive'] = 0;
$config['App.Status.active'] = 1;
$config['App.Status.delete'] = 2;

$config['App.MaxFileSize'] = 1048576;
$config['App.Admin.role'] =1;
$config['App.Transcription.role'] =3;
$config['App.User.role'] =2;

define('UPLOADS_DIR', 'uploads/');

define('GALLERY_IMG_DIR', 'uploads/gallery/');
define('GALLERY_IMG_ORIGINAL_DIR', 'uploads/gallery/original/');
define('GALLERY_IMG_THUMB_DIR', 'uploads/gallery/thumb/');
define('GALLERY_THUMB_WIDTH', 180);
define('GALLERY_THUMB_HEIGHT', 180);


define('GALLERY_IMG_MEDIUM_DIR', 'uploads/gallery/medium/');
define('GALLERY_MEDIUM_WIDTH', 300);
define('GALLERY_MEDIUM_HEIGHT', 300);


define('GALLERY_IMG_VERTICAL_DIR', 'uploads/gallery/vertical/');
define('GALLERY_VERTICAL_WIDTH', 130);
define('GALLERY_VERTICAL_HEIGHT', 260);


define('NOTE_IMG_DIR', 'uploads/note_image/');
define('NOTE_IMG_ORIGINAL_DIR', 'uploads/note_image/original/');
define('NOTE_IMG_THUMB_DIR', 'uploads/note_image/thumb/');
define('NOTE_THUMB_WIDTH', 180);
define('NOTE_THUMB_HEIGHT', 180);


define('NOTE_IMG_MEDIUM_DIR', 'uploads/note_image/medium/');
define('NOTE_MEDIUM_WIDTH', 300);
define('NOTE_MEDIUM_HEIGHT', 300);


define('NOTE_IMG_VERTICAL_DIR', 'uploads/note_image/vertical/');
define('NOTE_VERTICAL_WIDTH', 130);
define('NOTE_VERTICAL_HEIGHT', 260);




define('MOBILE_MEDIA', 'uploads/media');
define('CUSTOMER_NOTE_IMAGE', 'uploads/note_image');
define('GALLERY_IMAGE', 'uploads/note_image');



define('MY_SHOP_IMG_ORIGINAL_DIR', 'uploads/my_shop/original/');

define('MY_SHOP_IMG_MEDIUM_DIR', 'uploads/my_shop/medium/');
define('MY_SHOP_MEDIUM_WIDTH', 300);
define('MY_SHOP_MEDIUM_HEIGHT', 300);


$config['Status']     = array(1=>'Active', 0=>'Inactive');

$config['plan_duration']     = array(1=>'1 Month', 2=>'2 Months', 3=>'3 Months', 6=>'6 Months', 12=>'1 Year', 24=>'2 Years', 36=>'3 Years', 60=>'5 Years' );

$config['plan_type']     = array(1=>'Monthly', 2=>'Yearly');
//$config['subscription_plan']     = array(0=>'Select Subscription Plan', 1=>'Free account for 14 days, after that disabled', 2=>'DDI account per month', 3=>'DDI account per year', 4=>'URI per month', 5=>'URI per year' );
$config['affilate_status']     = array(0=>'Leads', 1=>'Registered', 2=>'Used');
$config['feed_status']     = array(0=>'Inactive', 1=>'Approved', 2=>'Denied');

$config['Privacy.Status']          	= array(1=>'Public',2=>'Private');
$config['User.Login.Search']          	= array('last_week'=>'Last week','last_6_month'=>' Last 6 Month','last_month'=>'Last month','last_year'=>'Last year');

$config['App.PerPage']     	= array(10=>10, 15=>15, 20=>20, 30=>30, 50=>50);
										
/***************** CONFIG VARIABLES FOR USER **************/
$config['App.Role.Admin']      	= 1;
$config['App.Role.User']     	= 2;
$config['App.Default.Userid']     	= 143;

$config['App.Sex']         	= array('0'=>'','1'=>'Male','2'=>'Female');
$config['App.FrontSex']         	= array(''=>'Gender','1'=>'Male','2'=>'Female');
$config['App.Card.Type'] = array('visa'=>'Visa','mastercard'=>'MasterCard','discover'=>'Discover','amex'=>'American Express');
$config['App.Weekday'] = array('月曜'=>'月曜','火曜日'=>'火曜日','水曜日'=>'水曜日','木曜日'=>'木曜日','金曜日'=>'金曜日','土曜日'=>'土曜日','日曜日'=>'日曜日');
$config['App.Payment.Type'] = array('現金'=>'現金','カード'=>'カード');
$config['App.Channel'] = array('ホットペッパー'=>'ホットペッパー','iSpot'=>'iSpot','EPark'=>'EPark','ウェブサイト'=>'ウェブサイト','紹介'=>'紹介','In Person'=>'In Person','電話'=>'電話');

/* Android firebase api key */
// $config['App.Firebase.apikey']      	= 'AAAAZ7uaMes:APA91bEXH95N3_9Btg4E59Q3E6G5nk7PSXfEZQ7Qw63B9KldLCtXFDB3Hp2sdZHHVe1ROeJKXWRQ9rw0furJXxve2cdK056yAJ8rd9Oo8dwN8iyysAyxXGOk1dyhhAfbm_GckuL9Suqv';
// $config['App.Firebase.apikey']      	= 'AAAAZY2BWuY:APA91bGxMmtJAcOXjFzP-RAOrECi4zc9b24hDyLstpiJWsDngmlT0W6wawZwpczqUeSPplzsZlxCK-XO5WpM1ZptafkTJtQXnqSOhIOnMjmJgToGu1OxXswjOASfnocNMNoIVxBOTpv2';
$config['App.Firebase.apikey']      	= 'AAAAdJ8zZ3Q:APA91bFjEQPN1Xb7HPB6PJ_G066Kf1KIJ2s9Rk_tFMqm5i7TFfBsWKbbYV9SgZPnAi13i2UFI7BT6JpV6Mw7cKMg8aPW4zbPgMYXlGeZs2oUtEnGEHRxlHgS3ezH3LCuNRDANE9Ogw4D';

/* japanese keywords */
$config['App.Reservation.morning']      	= '午前';
$config['App.Reservation.afternoon']      	= '午後';
$config['App.Reservation.staff']      		= 'スタッフ';
$config['App.Reservation.event']      		= 'イベント';

// Api parameter for flight api

$config['App.Flight.App.Id']      	= '4ce9e1b1';
$config['App.Flight.App.Key']     	= '63c972162b903141c006b3c8f43afacc';


// For block user
$config['App.Block.User'] = 1;
$config['App.Unblock.User'] = 0;
// End

// Set page length
$config['App.Page.length'] = 20;
//End

$config['App.Host'] = $_SERVER['HTTP_HOST'];
$config['App.open_fire_host'] = "115.112.57.155";
 
define('CURRENCY_IMAGE', "uploads/currency_image"); 
define('CATEGORY_IMAGE_DIR', "uploads/all_category/"); 

define('UPLOAD_FOLDER', "uploads");

define('PROFILE_PICS', "uploads/user");
define('PROFILE_COVER_PICS', "uploads/user_cover");

define('USER_DIR', "uploads/user"); 
define('USER_THUMB1_DIR', "uploads/user/thumb"); 
define('USER_TINY_DIR', "uploads/user/thumb"); 
define('USER_THUMB_DIR', "uploads/user/thumb");
define('USER_LARGE_DIR', "uploads/user/large"); 
define('USER_ORIGINAL_DIR', "uploads/user/original");

define('USER_COVER_DIR', "uploads/user_cover"); 
define('USER_COVER_THUMB1_DIR', "uploads/user_cover/thumb"); 
define('USER_COVER_TINY_DIR', "uploads/user_cover/thumb"); 
define('USER_COVER_THUMB_DIR', "uploads/user_cover/thumb");
define('USER_COVER_LARGE_DIR', "uploads/user_cover/large"); 
define('USER_COVER_ORIGINAL_DIR', "uploads/user_cover/original");

define('EMPLOYEE_IMAGE', "uploads/note_image/original"); 

define('DEFAULT_USER_IMG', "no_image.jpeg"); 
define('DEFAULT_USER_IMAGE', "/".USER_DIR."/no_image.png");
 
define('USER_TINY_WIDTH', '50');
define('USER_TINY_HEIGHT', '50');
 
define('USER_THUMB_WIDTH', '100');
define('USER_THUMB_HEIGHT', '100');

define('USER_THUMB_WIDTH1', '150');
define('USER_THUMB_HEIGHT1', '150');

define('USER_LARGE_WIDTH', '250');
define('USER_LARGE_HEIGHT', '250');


define('DL_FRONT_DIR', "uploads/dl/front/");
define('DL_BACK_DIR', "uploads/dl/back/");


define('QR_TMP_SERVERPATH', 'uploads/qr_code/');



/****************Stripe Payment gateway*******************/

// for test

$config['App.PublishableKey']   = 'pk_test_4qcV1TV6SX0SfqSGeYm30sea';
$config['App.ScreteKey']    	= 'sk_test_NS4bCjK97mkNlwYO0SPVGtaU';
$config['App.Stripe.mode']    	= 'Stripe.TestSecret';
$config['App.Mode']      		= 'Test';
$config['App.Currency']      	= 'jpy';

//for live

// $config['App.PublishableKey']    	= 'pk_live_RBKutCJQ4KE4veXfCJnWdqQ2';
// $config['App.ScreteKey']    		= 'sk_live_PoPgbGgFLAXbuUX8KyvWsMQk';
// $config['App.Stripe.mode']    		= 'Stripe.LiveSecret';
// $config['App.Mode']      			= 'Live';
// $config['App.Currency']      		= 'jpy';



$config['stripe_plan_id']     		= 'jts-board-plan';
// $config['stripe_plan_amount']    	= '15000';
$config['stripe_plan_amount']    	= '50';