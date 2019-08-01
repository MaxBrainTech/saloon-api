<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo (Configure::read('Site.title'));?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
</head>
<body>
	<div id="rxmail" style="border-radius:10px;  overflow:hidden; margin:auto; width:650px;">
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
	<tr>
		<td style="vertical-align:middle; width: 220px; text-align:center; padding:25px; background:#333; color:#fff;">
			<img src="<?php echo (Configure::read('App.SiteUrl'));?>/img/logo.png" alt="<?php echo (Configure::read('Site.title'));?>" style="color:#fff; font-size:20px; font-weight:bold;"/>					
		</td>
		<td style="vertical-align:middle; padding: 10px;font-family: 'Trebuchet MS';font-size: 15px; background:#e7e7e7;">
		<?php  echo ($content_for_layout);?> </td>
	</tr>
	<tr>
		<td colspan="2">
		<?php echo (Configure::read('footers'));?>
		</td>
	</tr>
</table>	
</div>
</body>
</html>
<?php //die;?>