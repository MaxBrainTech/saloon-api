<?php

/**
 * General Helper class file.
 *
 * PHP 5
 *
 */
App::uses('AppHelper', 'View/Helper');

class GeneralHelper extends AppHelper {

    public $no_image = 'no_image.png';
    var $helpers = array('Html');

   

    public function user_show_pic($image, $type = 'MEDIUM', $title = null, $id = null, $cust_width = null, $cust_height = null) {
        /* echo $title;
		die; */
        switch ($type) {
            case 'LARGE':
                $dir = USER_LARGE_DIR;
                $width = USER_LARGE_WIDTH;
                $height = USER_LARGE_HEIGHT;
                break;

            case 'THUMB':
                $dir = USER_THUMB_DIR;
                $width = USER_THUMB_WIDTH;
                $height = USER_THUMB_HEIGHT;
                break;
				
            case 'UPDATEPROFILE':
                $dir = USER_LARGE_DIR;
                $width = "200";
                $height = "200";
                break;
				
			case 'UPDATECOVER':
                $dir = USER_COVER_THUMB_DIR;
                $width = "110";
                $height = "110";
                break;
            
            case 'SMALL':
                $dir = USER_TINY_DIR;
                $width = USER_TINY_WIDTH;
                $height = USER_TINY_HEIGHT;
                break;
				
			case 'ORIGINAL':
				$dir = USER_ORIGINAL_DIR;

			$width = '';
			$height = '';
			break;
        }

        if (empty($id)) {
            $id = $image;
        }

        if ($height) {
            $dimes = array('alt' => $title, 'width' => "" . $width . "", 'height' => "" . $cust_height . "", 'id' => $id);
        } else {
            $dimes = array('alt' => $title, 'width' => "" . $width . "", 'id' => $id);
        }

        if ($image == '' || !file_exists(WWW_ROOT . $dir . DS . $image)) {

            $image = $this->no_image;
            $dir = 'uploads/user';
            return $this->Html->image('/' . $dir . '/' . $image, $dimes);
        }

        return $this->Html->image('/' . $dir . '/' . $image, $dimes);
    }

	
    public function user_show_cover($image, $type = 'MEDIUM', $title = null, $id = null, $cust_width = null, $cust_height = null){
        /* echo $title;
		die; */
        switch ($type) {
            case 'LARGE':
                $dir = USER_COVER_LARGE_DIR;
                $width = USER_COVER_LARGE_WIDTH;
                $height = USER_COVER_LARGE_HEIGHT;
                break;

            case 'THUMB':
                $dir = USER_COVER_THUMB_DIR;
                $width = USER_COVER_THUMB_WIDTH;
                $height = USER_COVER_THUMB_HEIGHT;
                break;
				
            case 'UPDATEPROFILE':
                $dir = USER_COVER_LARGE_DIR;
                $width = "200";
                $height = "200";
                break;
				
			case 'UPDATECOVER':
                $dir = USER_COVER_THUMB_DIR;
                $width = "110";
                $height = "110";
                break;
            
            case 'SMALL':
                $dir = USER_COVER_TINY_DIR;
                $width = USER_COVER_TINY_WIDTH;
                $height = USER_COVER_TINY_HEIGHT;
                break;
				
			case 'ORIGINAL':
				$dir = USER_COVER_ORIGINAL_DIR;

			$width = '';
			$height = '';
			break;
        }

        if (empty($id)) {
            $id = $image;
        }

        if ($height) {
            $dimes = array('alt' => $title, 'width' => "" . $width . "", 'height' => "" . $cust_height . "", 'id' => $id);
        } else {
            $dimes = array('alt' => $title, 'width' => "" . $width . "", 'id' => $id);
        }

        if ($image == '' || !file_exists(WWW_ROOT . $dir . DS . $image)) {

            $image = $this->no_image;
            $dir = 'uploads/user';
            return $this->Html->image('/' . $dir . '/' . $image, $dimes);
        }

        return $this->Html->image('/' . $dir . '/' . $image, $dimes);
    }

	
	/* wrap long text */
    function wrap_long_txt($value = null, $start = null, $end = null) {
        $len = strlen($value);

        if ($len > $end) {
            $str_edit = mb_substr($value, $start, $end);
            return $str_edit . ' ...';
        } else {
            return $value;
        }
    }

    /* You tube code */

    function parse_youtube_url($url, $return = 'embed', $width = '', $height = '', $rel = 0) {
        $urls = parse_url($url);
        ///pr($urls);
        //expect url is http://youtu.be/abcd, where abcd is video iD
        if ($urls['host'] == 'youtu.be') {
            $id = ltrim($urls['path'], '/');
        }
        //expect  url is http://www.youtube.com/embed/abcd
        else if (strpos($urls['path'], 'embed') == 1) {
            $id = end(explode('/', $urls['path']));
        }
        //expect url is abcd only
        else if (strpos($url, '/') === false) {
            $id = $url;
        }
        //expect url is http://www.youtube.com/watch?v=abcd
        else {
            parse_str($urls['query']);
            $id = $v;
        }
        //return embed iframe
        if ($return == 'embed') {
            return '<iframe width="' . ($width ? $width : 560) . '" height="' . ($height ? $height : 360) . '" src="http://www.youtube.com/embed/' . $id . '?rel=' . $rel . '"  frameborder="0" allowfullscreen></iframe>';
        }
        //return normal thumb
        else if ($return == 'thumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/default.jpg';
        }
        //return hqthumb
        else if ($return == 'hqthumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/hqdefault.jpg';
        }
        // 0 size image
        else if ($return == 'zero') {
            return 'http://i1.ytimg.com/vi/' . $id . '/0.jpg';
        }
        // 1 step size image
        else if ($return == 'one') {
            return 'http://i1.ytimg.com/vi/' . $id . '/1.jpg';
        }
        // two step size image
        else if ($return == 'two') {
            return 'http://i1.ytimg.com/vi/' . $id . '/2.jpg';
        }
        // 3 step size image
        else if ($return == 'three') {
            return 'http://i1.ytimg.com/vi/' . $id . '/3.jpg';
        }

        // else return id
        else {
            return $id;
        }
    }

    /* You tube code for home page */

    function parse_youtube_url_home($url, $return = 'embed', $width = '', $height = '', $rel = 0) {
        $urls = parse_url($url);

        //expect url is http://youtu.be/abcd, where abcd is video iD
        if ($urls['host'] == 'youtu.be') {
            $id = ltrim($urls['path'], '/');
        }
        //expect  url is http://www.youtube.com/embed/abcd
        else if (strpos($urls['path'], 'embed') == 1) {
            $id = end(explode('/', $urls['path']));
        }
        //expect url is abcd only
        else if (strpos($url, '/') === false) {
            $id = $url;
        }
        //expect url is http://www.youtube.com/watch?v=abcd
        else {
            parse_str($urls['query']);
            $id = $v;
        }
        //return embed iframe
        if ($return == 'embed') {
            return '<iframe width="' . ($width ? $width : 475) . '" height="' . ($height ? $height : 340) . '" src="http://www.youtube.com/embed/' . $id . '?rel=' . $rel . '"  frameborder="0" allowfullscreen></iframe>';
        }
        //return normal thumb
        else if ($return == 'thumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/default.jpg';
        }
        //return hqthumb
        else if ($return == 'hqthumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/hqdefault.jpg';
        }
        // 0 size image
        else if ($return == 'zero') {
            return 'http://i1.ytimg.com/vi/' . $id . '/0.jpg';
        }
        // 1 step size image
        else if ($return == 'one') {
            return 'http://i1.ytimg.com/vi/' . $id . '/1.jpg';
        }
        // two step size image
        else if ($return == 'two') {
            return 'http://i1.ytimg.com/vi/' . $id . '/2.jpg';
        }
        // 3 step size image
        else if ($return == 'three') {
            return 'http://i1.ytimg.com/vi/' . $id . '/3.jpg';
        }

        // else return id
        else {
            return $id;
        }
    }


    /* wrap long text */

    function wrap_loong_txt($value = null, $start = null, $end = null) {
        $len = strlen($value);
        if ($len > $end) {

            $str_edit = mb_substr($value, $start, $end);
            $num = strrpos($str_edit, ' ');
            if (substr($str_edit, -1) == '<') {
                return mb_substr($value, $start, $num - 1) . ' ...';
            } else {
                $str_edit = mb_substr($value, $start, $num);
                return $str_edit . ' ...';
            }
        } else {
            return $value;
        }
    }
	
	
	

    
	

}
