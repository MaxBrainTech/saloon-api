<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo (Configure::read('Site.title'));?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
</head>
<body>
<div style="padding: 10px; width: 720px; margin: auto;">
<div style="vertical-align:middle; width: 220px; text-align:center; padding:25px;  color:#fff;">
			<img src="<?php echo (Configure::read('App.SiteUrl'));?>/img/home-logo.png" alt="<?php echo (Configure::read('Site.title'));?>" style="color:#fff; font-size:20px; font-weight:bold;"/>					
		</div>
<div style="width: 670px; margin: 5px 0pt; padding: 20px; background-repeat: no-repeat;">
	
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
	<tr>
		
		<td style="vertical-align:middle; padding: 20px 30px 5px; font-family: 'Trebuchet MS';font-size: 15px; background:#fff;">
		<?php  echo ($content_for_layout);?> </td>
	</tr>
	
</table></div>


<table width="720px" border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td>
            <p style="text-align: center;">Delivered by <a href="<?php echo Configure::read('App.SiteUrl'); ?>" style="color: rgb(9, 129, 190);" title="<?php echo Configure::read('Site.title'); ?>" target="_blank"><?php echo Configure::read('Site.title'); ?></a></p>
            </td>
        </tr>
    </tbody>
</table>
</div>
</body>
</html>
<?php //die;?>