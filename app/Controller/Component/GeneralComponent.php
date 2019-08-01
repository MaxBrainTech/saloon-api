<?php
App::uses('Model', 'Industry');
class GeneralComponent extends Component{

	/*create new slug*/
	public $name='General';
	
	public $components = array('Upload', 'Image','Session');
	var $apiKey  = 'ABQIAAAAajljRcThsc78CngUdQRlmRSyog2N6f_9PfArcdzTvsnJ2QEyXBQMxqi-1okSw0E7ZAwxQkR7_tE3cA';
	
	
	public function createSlug($string)
    {
		return strtolower(Inflector::slug($string, '-'));
	}
	
	public function dateToDMY(&$date){
		$date = date('d-m-Y',strtotime($date));
	}
	public function getFolder($project_id){
		return floor($project_id/30000)+1;
	}
	public function dateFromDMY(&$date){
		if(empty($date)){
			$date = '';
			return;
		}
		$a = explode('-',$date);
		
		$b[0]=$a[2];
		$b[1]=$a[1];
		$b[2]=$a[0];
		$date = implode('-',$b);
	}
			
	public function File_upload($file,$destination){
		$result='';
		$destination=WWW_ROOT.$destination.'/';	
		$extension_allowed=array('jpg','gif','png','jpeg','JPG','JPEG','PNG','GIF','TXT','DOC','DOCX','PDF','ODT','RTF','ZIP','txt','doc','docx','pdf','odt','rtf','zip');
		
		$name=$file['name'];
		$ext= strstr($name,'.');
		$result = $this->Upload->upload($file, $destination,null,null,$extension_allowed);
		return $name=$this->Upload->result;
	}
	
	public function File_attach_upload($file,$destination){
		$result='';
		$destination=WWW_ROOT.$destination.'/';	
		$extension_allowed=array('TXT','DOC','DOCX','PDF','ODT','RTF','ZIP','txt','doc','docx','pdf','odt','rtf','zip');
			
		$result = $this->Upload->upload($file, $destination,null,null,$extension_allowed);
		return $name=$this->Upload->result;
	}

	public function videoUpload($file,$temp_name,$other=null){
		$destination=WWW_ROOT . DS . HOWITWORK_DIR_VIDEO. DS;
		move_uploaded_file($file['tmp_name'],$destination.$file['name']);
		return $name=$file['name'];
	}
	
	public function Image_upload_multi($file,$image_name,$model,$destinations=array(),$width_array=array(),$other){
		 
		$extension_allowed=array('.jpg','.gif','.png','.jpeg','.JPG','JPEG','.PNG','.GIF');
		$name=$file['name'];
		$destination='';
		$result='';
		$count=count($destinations);
		$count_image=count($file['name']);
		for($o=0;$o<$count_image;$o++){
			$ext= strstr($name[$o],'.');
			if(in_array($ext,$extension_allowed)){
				$single_file =array('name'=>$file['name'][$o],'type'=>$file['type'][$o],'tmp_name'=>$file['tmp_name'][$o],'error'=>$file['error'][$o],'size'=> $file['size'][$o]);
			
				for($i=0;$i<$count;$i++){
					$destination=WWW_ROOT.$destinations[$i].'/';
					
					list($width, $height, $type, $attr) = getimagesize($file['tmp_name'][$o]);
					if($i < $count-1){
						if($width > $width_array[$i]){
							$result = $this->Upload->upload($single_file, $destination,null, array('type' => 'resizeWidth', 'size' => array($width_array[$i], $height), 'output' => 'jpg'));
						}else{
							$result = $this->Upload->upload($single_file, $destination,$this->Upload->result, array('type' => 'resizeWidth', 'size' => array($width, $height), 'output' => 'jpg'));
						}
					}else{
						$result = $this->Upload->upload($single_file, $destination, $this->Upload->result);
					}
					//if($other!=''){
						//unlink($destination.$other);
					//}
					//return $name=$file[''.$model.''][''.$image_name.''] = $this->Upload->result_0;
					
				}
				$up_name[]=$this->Upload->result;	
			}
			else{
				return $up_name='error';
			}
		
		}
			return $up_name;
	}
	
	public function imageUpload($model_id,$model_name ,&$file,$image_name,$other=null){
		$extension_allowed=array('.jpg','.gif','.png','.jpeg','.JPG','JPEG','.PNG','.GIF');
		$folder = $model_id;
		if($model_name=='User'){
			$destinations[0] = USER_THUMB1_DIR ;
			$destinations[1] = USER_TINY_DIR;
			$destinations[2] = USER_THUMB_DIR ;
			$destinations[3] = USER_LARGE_DIR ;
			
			$width[0] = USER_LARGE_WIDTH;
			$width[1] = USER_TINY_WIDTH;
			$width[2] = USER_THUMB_WIDTH;
			$width[3] = USER_THUMB_WIDTH1;
			
			
		}
		
		

		$count=count($destinations);
		$ext= strstr($file['name'],'.');
		$model_id=str_replace("/","_",$model_id);
		$file['name'] = $model_id . $file['name'];
		
		if(in_array($ext,$extension_allowed)){
			for($i=0;$i<$count;$i++){
                            
				$destination=WWW_ROOT . $destinations[$i]. DS;
			
				if(!file_exists($destination)){
					if (!mkdir($destination)) {
						break;
						$this->Session->setFlash('Image could not be saved. Please, try again.','admin_flash_bad');
					} 
				}
				
				$this->Image->setImage($image_name); 
				$this->Image->setQuality(100);
				if(isset($width[$i])){					
					$this->Image->resize(array('type' => 'resizecrop', 'size' => $width[$i]));
				}
					
				if (!$this->Image->generate($destination . $file['name']) ){
					print_r($this->Image->errors);
				}
					
				if($other && file_exists($destination.$other)){
					unlink($destination.$other);
				}
			}	
		}
		else 
		{
			return 'Error';
		}
		return $file['name'];
	}
	
	
	public function projectfileUpload($project_id,$model_name ,&$file,$image_name,$other=null,$from=null,$user_id=null){
		$extension_allowed=array('.jpg','.gif','.png','.jpeg','.JPG','.JPEG','.PNG','.GIF','.TXT','.DOC','.DOCX','.PDF','.ODT','.RTF','.ZIP','.txt','.doc','.docx','.pdf','.odt','.rtf','.zip');
		//pr($file);
		if($model_name=='Project'){
				$folder = $project_id;
				$user_folder=$user_id;
				$destination = WWW_ROOT . PROJECT_ORG_DIR . DS . $user_folder . DS . $folder . DS ; 		 
				$form= WWW_ROOT . DS . TEMP_PROJECT_ORG_DIR . DS . $from . DS .$file['name'];
				$file['name'] = $project_id . $file['name'];
				
				$ext= strstr($file['name'],'.');		
				if(in_array($ext,$extension_allowed)){
			
					
				if(!file_exists($destination)){
					if (!mkdir($destination)) {
						break;
						$this->Session->setFlash('Image could not be saved. Please, try again.','admin_flash_bad');
					} 
				}
				
			copy($form,$destination.$file['name']);
					
				if($other && file_exists($destination.$other)){
					unlink($destination.$other);
				}
				
				}else {
					return 'Error';
				}
				
					
		}else if($model_name=='Temp'){
				$folder = $project_id;
			 	$destination = WWW_ROOT . TEMP_PROJECT_ORG_DIR . DS . $folder . DS ; 
				//$form=$file['tmp_name']; 
				$file['name'] = $file['name'];	
				
				
				$ext= strstr($file['name'],'.');		
				if(in_array($ext,$extension_allowed)){
			
				//$destination=WWW_ROOT . DS . $destinations[$i]. DS;			
				if(!file_exists($destination)){
					if (!mkdir($destination)) {
						break;
						$this->Session->setFlash('Image could not be saved. Please, try again.','admin_flash_bad');
					} 
				}
				
				move_uploaded_file($file['tmp_name'],$destination.$file['name']);
					
				if($other && file_exists($destination.$other)){
					unlink($destination.$other);
				}
				
				}else {
					return 'Error';
				}
						
				
				
		
		}	
		
		return $file['name'];
	}
	public function resumeUpload($user_id,$file,$others){
		$destination = WWW_ROOT . USER_RESUME_ORG_DIR . DS . $user_id;
		if(!is_dir($destination)){
			mkdir($destination);
		}
		move_uploaded_file($file['tmp_name'],$destination. DS .$file['name']);
		if($others && file_exists($destination. DS .$others)){
					unlink($destination. DS .$others);
		}
		
	}
	
	public function projectfileedit($project_id,$user_id,&$file,$image_name,$other=null){
		$extension_allowed=array('.jpg','.gif','.png','.jpeg','.JPG','.JPEG','.PNG','.GIF','.TXT','.DOC','.DOCX','.PDF','.ODT','.RTF','.ZIP','.txt','.doc','.docx','.pdf','.odt','.rtf','.zip');
		//pr($file);
				
				$folder = $project_id;
				$user_folder=$user_id;
				$destination = WWW_ROOT . PROJECT_ORG_DIR . DS . $user_folder . DS . $folder . DS ;
				//$destination = WWW_ROOT . PROJECT_ORG_DIR . DS . $folder . DS ; 		 
				
				$file['name'] = $project_id . $file['name'];
				
				$ext= strstr($file['name'],'.');		
				if(in_array($ext,$extension_allowed)){
				//echo 'sdas';	
				if(!file_exists($destination)){
					if (!mkdir($destination)) {
						break;
						$this->Session->setFlash('Image could not be saved. Please, try again.','admin_flash_bad');
					} 
				}
				//echo 'sds'; die;
			move_uploaded_file($file['tmp_name'],$destination.$file['name']);
					
				if($other && file_exists($destination.$other)){
					unlink($destination.$other);
				}
				
				}else {
					return 'Error';
				}
		
		return $file['name'];
	}
	
/* You tube code */
	function parse_youtube_url($url,$return='embed',$width='',$height='',$rel=0){
		$urls = parse_url($url);
		
		//expect url is http://youtu.be/abcd, where abcd is video iD
		if($urls['host'] == 'youtu.be'){ 
			$id = ltrim($urls['path'],'/');
		}
		//expect  url is http://www.youtube.com/embed/abcd
		else if(strpos($urls['path'],'embed') == 1){ 
			$id = end(explode('/',$urls['path']));
		}
		 //expect url is abcd only
		else if(strpos($url,'/')===false){
			$id = $url;
		}
		//expect url is http://www.youtube.com/watch?v=abcd
		else{
			parse_str($urls['query']);
			$id = $v;
		}
		//return embed iframe
		if($return == 'embed'){
			return '<iframe width="'.($width?$width:560).'" height="'.($height?$height:349).'" src="http://www.youtube.com/embed/'.$id.'?rel='.$rel.'" frameborder="0" allowfullscreen>';
		}
		//return normal thumb
		else if($return == 'thumb'){
			return 'http://i1.ytimg.com/vi/'.$id.'/default.jpg';
		}
		//return hqthumb
		else if($return == 'hqthumb'){
			return 'http://i1.ytimg.com/vi/'.$id.'/hqdefault.jpg';
		}
		// 0 size image
		 else if($return == 'zero'){
			return 'http://i1.ytimg.com/vi/'.$id.'/0.jpg';
		}
		// 1 step size image
		 else if($return == 'one'){
			return 'http://i1.ytimg.com/vi/'.$id.'/1.jpg';
		}
		// two step size image
		 else if($return == 'two'){
			return 'http://i1.ytimg.com/vi/'.$id.'/2.jpg';
		}
		// 3 step size image
		 else if($return == 'three'){
			return 'http://i1.ytimg.com/vi/'.$id.'/3.jpg';
		}
		
		// else return id
		else{
			return $id;
		}
	}
	
	/*wrap long text*/
	function wrap_long_txt($value=null,$start=null,$end=null){
		$len=strlen($value);
		if($len > $end){			
			$str_edit=mb_substr($value,$start,$end);
			return $str_edit.' ...';
			
		}else{
			return $value;
		}
	}	
	
	public function bannerUpload(&$file,$image_name,$other=null){
		$extension_allowed=array('.jpg','.gif','.png','.jpeg','.JPG','JPEG','.PNG','.GIF');
	
		
			$destinations[0] = BANNER_LARGE_DIR ;
			$destinations[1] = BANNER_MEDIUM_DIR ;
			$destinations[2] = BANNER_SMALL_DIR ;
			
			
			$width[0] = BANNER_LARGE_WIDTH;
			$width[1] = BANNER_MEDIUM_WIDTH;
			$width[2] = BANNER_SMALL_WIDTH;	
			
			$height[0] = BANNER_LARGE_HEIGHT;
			$height[1] = BANNER_MEDIUM_HEIGHT;
			$height[2] = BANNER_SMALL_HEIGHT;				
		
		$count=count($destinations);
		$ext= strstr($file['name'],'.');
		$file['name'] = $file['name'];
		
		if(in_array($ext,$extension_allowed)){
			for($i=0;$i<$count;$i++){
				$destination=WWW_ROOT . DS . $destinations[$i]. DS;
			
				if(!file_exists($destination)){
					if (!mkdir($destination)) {
						break;
						$this->Session->setFlash('Image could not be saved. Please, try again.','admin_flash_bad');
					} 
				}
				
				$this->Image->setImage($image_name); 
				$this->Image->setQuality(100);
				if(isset($width[$i])){					
					$this->Image->resize(array('type' => 'resizecrop', 'size' =>array('0'=>$width[$i],'1'=>$height[$i])));
				}
					
				if (!$this->Image->generate($destination . $file['name']) ){
					print_r($this->Image->errors);
				}
					
				if($other && file_exists($destination.$other)){
					unlink($destination.$other);
				}
			}	
		}else {
			return 'Error';
		}
		return $file['name'];
	}
	/* Get Project For*/
	function project_for($value){
		$project_for=Configure::read('Project.Time');
		return $project_for[$value];
	}	
	
	public function postimageUpload($post_id, &$file,$image_name,$other=null){
		
	
				$folder = $post_id;
				//$destination[0]=DS . USER_DIR . DS . $folder;
				$destinations[0] = POST_LARGE_DIR . DS . $folder;
				$destinations[3] = POST_EX_LARGE_DIR . DS . $folder;
				$destinations[1] = POST_SMALL_DIR . DS . $folder;
				$destinations[2] = POST_ORIGINAL_DIR . DS . $folder;
                $destinations[4] = POST_VERY_SMALL_DIR . DS . $folder;
				
				$width[0] = POST_LARGE_WIDTH;
				$width[1] = POST_SMALL_WIDTH;				
				$width[3] = POST_EX_LARGE_WIDTH;				
				$width[4] = POST_VERY_SMALL_WIDTH;
                                
				$height[0] = POST_LARGE_HEIGHT;
				$height[1] = POST_SMALL_HEIGHT;				
				$height[3] = POST_EX_LARGE_HEIGHT;				
				$height[4] = POST_VERY_SMALL_HEIGHT;	
				
				$count=count($destinations);
				$file['name'] = $post_id . $file['name'];
				for($i=0;$i<$count;$i++){
					$destination=WWW_ROOT . DS . $destinations[$i]. DS;
					
					if(!file_exists($destination)){
						if (!mkdir($destination)) {
							break;
							$this->Session->setFlash('Image could not be saved. Please, try again.', 'admin_flash_bad');
						} 
					}
					$file_ext=pathinfo($file['name']);
					$this->Image->setImage($image_name,$file_ext['extension']);
					$this->Image->setQuality(100);
					
					if(isset($width[$i])){					
					$this->Image->resize(array('type' => 'resizecrop', 'size' => array('0'=>$width[$i],'1'=>$height[$i])));
					}
					
					if ( !$this->Image->generate($destination . $file['name']) ){
						print_r($this->Image->errors);
					}	
						if($other && file_exists($destination.$other)){
							unlink($destination.$other);
						}
				}
				return $file['name'];
	}
	
	public function Message_upload($file,$project_id){
			$result='';
			$folder = floor($project_id/30000)+1;
			$destination=WWW_ROOT . DS . MESSAGE_ORG_DIR . DS .$project_id . DS;	
			if(!file_exists($destination)){
						if (!mkdir($destination)) {
							break;
							$this->Session->setFlash('Image could not be saved. Please, try again.', 'admin_flash_bad');
						} 
					}
			$extension_allowed=array('jpg','gif','png','jpeg','JPG','JPEG','PNG','GIF','TXT','DOC','DOCX','PDF','ODT','RTF','ZIP','txt','doc','docx','pdf','odt','rtf','zip','sql');
			
			$name=$file['name'];
			$ext= strstr($name,'.');
			$result = $this->Upload->upload($file, $destination,null,null,$extension_allowed);
			return $name=$this->Upload->result;
		}
		
		public function advtimage(&$file,$image_name,$other=null){
		
	
				//$folder = floor($post_id/30000)+1;
				//$destination[0]=DS . USER_DIR . DS . $folder;
				$destinations[0] = ADVT_LARGE_DIR ;
				$destinations[1] = ADVT_ORIGINAL_DIR;
				
				$width[0] = ADVT_LARGE_WIDTH;
				$height[0] = ADVT_LARGE_HEIGHT;
						
				
				//echo $image_name;
				$count=count($destinations);
				$file['name'] = time().$file['name'];
				for($i=0;$i<$count;$i++){
					$destination=WWW_ROOT . $destinations[$i]. DS;
					
					if(!file_exists($destination)){
						if (!mkdir($destination)) {
							break;
							$this->Session->setFlash('Image could not be saved. Please, try again.', 'admin_flash_bad');
						} 
					}
					$file_ext=pathinfo($file['name']);
					$this->Image->setImage($image_name,$file_ext['extension']);
					$this->Image->setQuality(100);
					
					if(isset($width[$i])){					
					$this->Image->resize(array('type' => 'resizecrop', 'size' => array('0'=>$width[$i],'1'=>$height[$i])));
					}
					
					if ( !$this->Image->generate($destination . $file['name']) ){
						print_r($this->Image->errors);
					}	
						if($other && file_exists($destination.$other)){
							unlink($destination.$other);
						}
				}
				return $file['name'];
	}
	
                public function companylogoimage(&$file,$image_name,$other=null){
		
	
				//$folder = floor($post_id/30000)+1;
				//$destination[0]=DS . USER_DIR . DS . $folder;
                                $destinations[0] = USER_COM_ORG_DIR;
				$destinations[1] = USER_COM_LARGE_DIR ;
				$destinations[2] = USER_COM_MEDIUM_DIR;
                                $destinations[3] = USER_COM_LOGO_DIR;
                                $destinations[4] = USER_COM_SMALL_DIR;
                                $destinations[5] = USER_COM_EXTRA_SMALL_DIR;
                                
                                
                                
				$width[1] = USER_COM_LARGE_WIDTH;
				$width[2] = USER_COM_MEDIUM_WIDTH;
                                $width[3] = USER_COM_LOGO_WIDTH;
				$width[4] = USER_COM_SMALL_WIDTH;
				$width[5] = USER_COM_EXTRA_SMALL_WIDTH;
				
				
						
				
				//echo $image_name;
				$count=count($destinations);
				$file['name'] = time().$file['name'];
				for($i=0;$i<$count;$i++){
					$destination=WWW_ROOT . $destinations[$i]. DS;
					
					if(!file_exists($destination)){
						if (!mkdir($destination)) {
							break;
							$this->Session->setFlash('Image could not be saved. Please, try again.', 'admin_flash_bad');
						} 
					}
					$file_ext=pathinfo($file['name']);
					$this->Image->setImage($image_name,$file_ext['extension']);
					$this->Image->setQuality(100);
					
					if(isset($width[$i])){					
					$this->Image->resize(array('type' => 'resizecrop', 'size' => array('0'=>$width[$i])));
					}
					
					if ( !$this->Image->generate($destination . $file['name']) ){
						print_r($this->Image->errors);
					}	
						if($other && file_exists($destination.$other)){
							unlink($destination.$other);
						}
				}
				return $file['name'];
	}
	
        
        
                public $languages = array(
		"1"=>"Aboriginal Dialects",
		"2"=>"Afrikaans",
		"93"=>"Ainu",
		"94"=>"Akkadian",
		"155"=>"Albanian",
		"95"=>"Alurian",
		"3"=>"American Sign Language",
		"167"=>"Amharic",
		"4"=>"Ancient Greek",
		"5"=>"Arabic",
		"96"=>"Arkian",
		"164"=>"Armenian",
		"6"=>"Assamese",
		"97"=>"Assyrian",
		"98"=>"Asturian",
		"7"=>"Australian Sign Language",
		"99"=>"Aymara",
		"170"=>"Azerbaijani",
		"8"=>"Bahasa Indonesia",
		"9"=>"Basque",
		"100"=>"Basque Language-Euskara",
		"166"=>"Belarusian",
		"10"=>"Bengali",
		"101"=>"Berber",
		"160"=>"Bosnian",
		"11"=>"Brazilian Portuguese",
		"12"=>"Breton",
		"13"=>"British Sign Language",
		"102"=>"Buhi",
		"14"=>"Bulgarian",
		"15"=>"Burmese",
		"16"=>"Catalan",
		"103"=>"Cherokee",
		"104"=>"Chichewa",
		"17"=>"Chinese",
		"18"=>"Chinese - Cantonese",
		"19"=>"Chinese - Mandarin",
		"20"=>"Chinese - Taiwanese",
		"176"=>"Chinese-Shanghai Dialect",
		"177"=>"Chinese-Southern Fujian Dialect",
		"105"=>"Church Slavonic",
		"106"=>"Cornish",
		"21"=>"Corsican",
		"22"=>"Croatian",
		"23"=>"Czech",
		"107"=>"Dakota",
		"24"=>"Danish",
		"108"=>"Degaspregos",
		"109"=>"Dilhok",
		"110"=>"Dongxiang",
		"25"=>"Dutch",
		"26"=>"Egyptian",
		"27"=>"English",
		"111"=>"Esperanto",
		"28"=>"Estonian",
		"112"=>"Eurolang",
		"113"=>"Faroese",
		"29"=>"Farsi",
		"171"=>"Fijian",
		"30"=>"Finnish",
		"32"=>"French",
		"33"=>"Frisian",
		"114"=>"Friulian",
		"34"=>"Gaelic",
		"115"=>"Galician",
		"116"=>"Georgian",
		"35"=>"German",
		"37"=>"Greenlandic",
		"117"=>"Guarani",
		"38"=>"Gujarati",
		"118"=>"Haponish",
		"119"=>"Hausa",
		"120"=>"Hawaiian",
		"121"=>"Hawaiian Pidgin English",
		"39"=>"Hebrew",
		"40"=>"Hindi",
		"41"=>"Hindustan",
		"42"=>"Hmong",
		"43"=>"Hungarian",
		"44"=>"Icelandic",
		"122"=>"Ido",
		"123"=>"Ingush",
		"45"=>"Irish",
		"46"=>"Irish Gaelic",
		"47"=>"Italian",
		"124"=>"Jameld",
		"48"=>"Japanese",
		"125"=>"Kankonian",
		"49"=>"Kannada",
		"50"=>"Kashmiri",
		"169"=>"Kazakh",
		"126"=>"Khmer",
		"127"=>"Kiswahili",
		"128"=>"Konkani",
		"51"=>"Korean",
		"52"=>"Kurdish",
		"168"=>"Kyrgyz",
		"129"=>"Ladin",
		"130"=>"Ladino",
		"131"=>"Lakhota",
		"175"=>"Laotian",
		"53"=>"Latin",
		"54"=>"Latvian",
		"55"=>"Lithuanian",
		"132"=>"Loglan",
		"133"=>"Low Saxon",
		"156"=>"Luxembourgish",
		"165"=>"Macedonian",
		"134"=>"Malat",
		"56"=>"Malay",
		"57"=>"Malayalam",
		"58"=>"Manipuri",
		"59"=>"Manx Gaelic",
		"60"=>"Maori",
		"61"=>"Marathi",
		"135"=>"Mongolian",
		"136"=>"Neelan",
		"62"=>"Nepali",
		"63"=>"Norwegian",
		"137"=>"Novial",
		"138"=>"Occitan",
		"139"=>"Ojibwe",
		"64"=>"Oriya",
		"65"=>"Other",
		"140"=>"Pashto",
		"141"=>"Pidgin",
		"161"=>"Pidgin Signed English (PSE) or Signed English",
		"66"=>"Polish",
		"67"=>"Portuguese",
		"68"=>"Prakrit",
		"69"=>"Punjabi",
		"142"=>"Quechua",
		"143"=>"Rhaeto -Romance",
		"70"=>"Romanian",
		"144"=>"Romany",
		"71"=>"Russian",
		"157"=>"Sami",
		"172"=>"Samoan",
		"72"=>"Sanskrit",
		"73"=>"Scots",
		"74"=>"Scots Gaelic",
		"75"=>"Serbian",
		"145"=>"Shiyeyi",
		"76"=>"Sicilian",
		"162"=>"Signing Exact English (SEE)",
		"146"=>"Sindhi",
		"147"=>"Sinhalese",
		"77"=>"Slovak",
		"78"=>"Slovene",
		"79"=>"Spanish",
		"148"=>"Swabian",
		"80"=>"Swahili",
		"81"=>"Swedish",
		"163"=>"Tactile Sign Language",
		"82"=>"Tagalog",
		"83"=>"Tamil",
		"84"=>"Telugu",
		"149"=>"Tengwar",
		"85"=>"Thai",
		"86"=>"Tibetan",
		"150"=>"Tok Pisin",
		"173"=>"Tongan",
		"87"=>"Turkish",
		"88"=>"Ukrainian",
		"89"=>"Urdu",
		"151"=>"Uzbek",
		"90"=>"Vietnamese",
		"152"=>"Vogu",
		"91"=>"Welsh",
		"153"=>"Xhamagas",
		"158"=>"Xhosa",
		"92"=>"Yiddish",
		"154"=>"Yoruba",
		"159"=>"Zulu"
	);
	
	public $industries;

	public $skill_levels = array(
		"1"=>"Beginner",
		"2"=>"Intermediate",
		"3"=>"Advanced",
		"4"=>"Fluent",
	);
	
	public $military_served = array(
		"1"=>"None",
		"2"=>"Unspecified",
		"3"=>"Active Duty",
		"4"=>"Retired Military",
		"5"=>"Veteran/Prior Service",
		"6"=>"Reservist (drilling)",
		"7"=>"National Guard",
		"8"=>"Inactive Reserve/Guard (not drilling)",
		"9"=>"Service Academy",
		"10"=>"ROTC",
		"11"=>"Other Military Program",
		"12"=>"Government Employee",
		"13"=>"Defense Contractor",
		"14"=>"Considering Joining",
		"15"=>"Military Spouse",
		"16"=>"Spouse of a Veteran",
		"17"=>"Other Military Family Member",
	);
	
	public $security_clearance = array(
		"1"=>"None",
		"3"=>"Active Confidential",
		"4"=>"Active Secret",
		"5"=>"Active Top Secret",
		"6"=>"Active Top Secret/SCI",
		"19"=>"Active TS/SCI-CI Polygraph 
		",
		"20"=>"Active TS/SCI-FS Polygraph 
		",
		"18"=>"Inactive Clearance 
		",
		"21"=>"Other Active Clearance ",
	);
	
	public $citizenship = array(
		"1"=>"None",
		"1"=>"US Citizen",
		"2"=>"Permanent Resident",
		"3"=>"Other",
	);
	
	public $when_start = array(
		"1"=>"Immediately",
		"2"=>"Within 2 weeks",
		"3"=>"Within one month",
		"4"=>"From 1 to 3 months",
		"5"=>"More than 3 months",
		"6"=>"Negotiable",
	);
	
	public $salary_in = array(
		"1"=>"USD",
		"2"=>"EUR",
		"3"=>"ARS",
		"4"=>"AUD",
		"6"=>"BRL",
		"7"=>"CAD",
		"8"=>"CHF",
		"9"=>"CNY",
		"10"=>"CZK",
		"13"=>"FJD",
		"15"=>"GBP",
		"17"=>"HKD",
		"18"=>"HUF",
		"19"=>"IDR",
		"21"=>"ILS",
		"22"=>"INR",
		"24"=>"JPY",
		"25"=>"KRW",
		"26"=>"MXN",
		"27"=>"MYR",
		"29"=>"NZD",
		"30"=>"PLN",
		"31"=>"RUB",
		"32"=>"SEK",
		"33"=>"SGD",
		"34"=>"TWD",
		"35"=>"ZAR",
		"37"=>"NOK",
		"38"=>"DKK",
		"41"=>"RON",
		"42"=>"IQD",
		"43"=>"PHP",
		"44"=>"SAR",
		"45"=>"THB",
		"46"=>"AED",
		"47"=>"CLP",
		"48"=>"COP",
		"49"=>"GTQ",
		"50"=>"PEN",
		"51"=>"TND",
		"52"=>"TRY",
		"53"=>"TTD",
		"54"=>"UYU",
		"55"=>"VEB",
		"56"=>"PKR",
		"57"=>"EGP",
		"58"=>"PGK",
		"59"=>"BOB",
		"60"=>"PYG",
	);
	
	public $salary_type = array(
		"1"=>"Per Year",
		"2"=>"Per Hour",
		"3"=>"Per Week",
		"4"=>"Per Month",
		"5"=>"Per Biweekly",
		"6"=>"Per Day",
	);
        
        
public function convert_time($date)
	{
		
			$date2 = date('Y-m-d H:i:s');//"2008-11-01 22:45:00";
	
		 
			$diff = abs(strtotime($date2) - strtotime($date)); 
			$years   = floor($diff / (365*60*60*24)); 
			$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    
			$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 

			$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 

		//if($years>=1 || $months>=1)
		if($years>=1)
		{
			return date('d M Y',strtotime($date));
		}
		/* else if($days>7)
		{
			return date('d M Y',strtotime($date));
		} */
		else if($months<=12 && $months>0)
		{
			return $months ."M";
		}
		else if($days<=30 && $days>0)
		{
			return $days ."D";
		}
			else if($days<=0 && $hours>0)
		{
			return $hours ."h";
		}
			else if($minuts>0 && $hours<=0)
		{
			return $minuts ."m";
		}
		else
		{
			return "Now";
		}

	}
	
	function getmonthName($number = null){
		$monthName = array(
		1=>'Jan',
		2=>'Feb',
		3=>'Mar',
		4=>'Apr',
		5=>'May',
		6=>'Jun',
		7=>'Jul',
		8=>'Aug',
		9=>'Sep',
		10=>'Oct',
		11=>'Nov',
		12=>'Dec',
		);
		return $monthName[$number];
	}

	 public function get_category_name($id) {
        $cat_nane = '';
        App::import('Model', 'Category');
        $category = new Category;
        $category_name = $category->find('first', array('conditions' => array('Category.id' => $id), 'fields' => array('Category.name')));
        $name = !empty($category_name['Category']['name']) ? $category_name['Category']['name'] : '';
        return $name;
    }
    public function getSubCategoryName($id, $type) {

        $data = $this->find($type, array(
            'conditions' => array('Category.status' => Configure::read('App.Status.active'), 'Category.id' => $id),
            'order' => array('Category.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }
		
	public function curl_get_file_contents($URL)
    {
      $c = curl_init();
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($c, CURLOPT_URL, $URL);
      $contents = curl_exec($c);
      curl_close($c);

      if ($contents) return $contents;
      else return FALSE;
    }

  public function getLatLang($address=null)
	{
    if(empty($address)) return false;
    $foundAddress = null; 
    if(is_array($address)){ 
     // $keys =  array("country","state","city","address","postcode");
      $keys =  array("city", "state", "country");
      $values = array();
      foreach($address as $key=>$value){
        if(in_array($key,$keys) && !empty($value)){
          $values[] = $value;
        }
      }
      $foundAddress .= implode(",",$values);
    } 
    else{
      $foundAddress = $address;
    }
		//echo $foundAddress;die;
		 $foundAddress = "Thailand, Bangkok, Bang Rak";
    $key = $this->apiKey;
		$res = null;
    $tempVar = "http://maps.google.com/maps/geo?q=".urlencode($foundAddress)."&output=csv&sensor=false&key=".$key;
	echo $tempVar;die;
    if($foundAddress){
      $res = $this->curl_get_file_contents($tempVar);
    }
    if($res){
      $map_parameter = split(',',$res);
      $return = array();
      $return['latitude'] = $map_parameter[2];  
      $return['longitude'] = $map_parameter[3]; 
      return $return;
     }
    else 
      return false;
	}
	
	function get_lat_longs($address = null){
		//$address = '201 S. Division St., Ann Arbor, MI 48104'; // Google HQ
		$prepAddr = str_replace(' ','+',$address);		 
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');		 
		$output= json_decode($geocode);		 
		$latLongArr = array();
		$lat = 0;
		$long = 0;
		if(!empty($output->results[0]->geometry->location->lat)){
			$lat = $output->results[0]->geometry->location->lat;
			$long = $output->results[0]->geometry->location->lng;
		}
		$latLongArr['lat'] = $lat;
		$latLongArr['long'] = $long;	 
		return $latLongArr;
	}
	
}
?>