<?php


function send_mailgun($email, $body) {
	$body = '<div style="padding: 10px; width: 720px; margin: auto;">test data</div>';

	$domain = "mg.jtsboard.com";
    $config = array();
    $config['api_key'] = "457b1d1a0372e162d6336f675d1a69c6-de7062c6-a83103f2";
    $config['api_url'] = "https://api.mailgun.net/v3/" . $domain . "/messages";
    $message = array();
    $message['from'] = "JTSBoard User <mailgun@mg.jtsboard.com>";
    $message['to'] = $email;
    $message['subject'] = $subject;
    $message['html'] = $body;
    // print_r($message);die;
    // $message = json_encode($message);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $config['api_url']);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "api:{$config['api_key']}");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
    $result = curl_exec($curl);
    pr($result);die;
    curl_close($curl);
    return $result;


}
echo send_mailgun("zedtech.cloud@gmail.com", "Body of the message here!");
die('test');

/*

$apiKey = '457b1d1a0372e162d6336f675d1a69c6-de7062c6-a83103f2';
$url = 'https://api.mailgun.net/v3/mg.jtsboard.com/messages';
$data['from'] = 'Excited User <mailgun@mg.jtsboard.com>';
$data['to'] = 'YOU@mg.jtsboard.com';
$data['to'] = 'mahen.zed@hotmail.com';
$data['subject'] = 'test mail';
$data['text'] = 'test mail message.';

$data = json_encode($data);

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HEADER, true);

curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

curl_setopt($curl, CURLOPT_USERPWD, 'api:'.$apiKey); 

curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

$result = curl_exec($ch);
print_r($result);die;

$last = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);
return array($result,$last);




$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  'APIKEY: $apiKey',
  'Content-Type: application/json',
));

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

$result = curl_exec($curl);
print_r($result);die;
if(!$result){die("Connection Failure");}
curl_close($curl);
die('test');

pr($result);die;
?>*/