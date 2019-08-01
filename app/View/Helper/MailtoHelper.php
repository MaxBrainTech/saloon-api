 <?php 
class MailtoHelper extends Helper {
    
    function createLink($addr, $link_content, $class=null) {

        //build the mailto link
        $unencrypted_link = '<a href="mailto:'.$addr.'" class="'.$class.'">'.$link_content.'</a>';
        //build this for people with js turned off
        //$noscript_link = '<noscript><span style="unicode-bidi:bidi-override;direction:rtl;">'.strrev($link_content.' > '.$addr.' <').'</span></noscript>';
        //put them together and encrypt
        //$encrypted_link = '<script type="text/javascript">Rot13.write(\''.str_rot13($unencrypted_link).'\');</script>'.$noscript_link;
	//echo $encrypted_link;die;
        return $unencrypted_link;
    }
}
?> 