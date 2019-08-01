<?php
ini_set('memory_limit', '-1');
class VideoUploadComponent extends Component {
	public $name='VideoUpload';
	/**
		videoUpload for uploading the videos on the cloud server
    */
    public function videoUpload($file) {
		$message = array();
		//FOLDER CREATION
		if(!is_dir(WWW_ROOT.'/uploads/videos')){
			mkdir(WWW_ROOT.'/uploads/videos',0777);
		}
		if(!is_dir(WWW_ROOT.'/uploads/videos/thumbnail')){
			mkdir(WWW_ROOT.'/uploads/videos/thumbnail',0777);
		}
		if(!is_dir(WWW_ROOT.'/uploads/videos/original')){
			mkdir(WWW_ROOT.'/uploads/videos/original',0777);
		}
		if(!is_dir(WWW_ROOT.'/uploads/videos/watermark')){
			mkdir(WWW_ROOT.'/uploads/videos/watermark',0777);
		}
		$path_info = pathinfo($file['name']);
		$fileNameWithOutExtension = $path_info['filename']."_".time();
		$videoFileName= $fileNameWithOutExtension.".".$path_info['extension'];
		$fileFullPath = $file['tmp_name'];
		$ffmpeg = 'ffmpeg';
		$interval = 1;
		/*CODE START FOR DURATION OF VIDEO FILE IN SECONDS*/
		$durationCmd = "$ffmpeg -i $fileFullPath 2>&1";
		$duration = shell_exec($durationCmd);
		preg_match("/Duration: (\d{2}:\d{2}:\d{2}\.\d{2})/",$duration,$matches);
		$time = explode(':',$matches[1]);
		$hour = $time[0];
		$minutes = $time[1];
		$seconds = round($time[2]);
		$total_seconds = 0;
		$total_seconds += 60 * 60 * $hour;
		$total_seconds += 60 * $minutes;
		$total_seconds += $seconds;
		$message['duration'] = $total_seconds;
		/*CODE END FOR DURATION OF VIDEO FILE IN SECONDS*/
		/****** START THUMBNAIL CREATION *****/
		$thumbImage = $fileNameWithOutExtension.'_thumb.jpg';
		$thumbImagePath = WWW_ROOT .'uploads'. DS . 'videos'. DS .'thumbnail' . DS .$thumbImage;
		$size = '100x100';  
		$cmd = "$ffmpeg -i $fileFullPath -deinterlace -an -ss $interval -f mjpeg -t 1 -r 1 -y -s $size $thumbImagePath 2>&1";
		exec($cmd);
		/****** END THUMBNAIL CREATION *****/
		/****** START ORIGINAL CREATION *****/
		$origImage = $fileNameWithOutExtension.'_orig.jpg';		
		$origImagePath = WWW_ROOT . 'uploads'. DS . 'videos'. DS .'original' . DS .$origImage;
		$size = '640x480';  
		$cmd = "$ffmpeg -i $fileFullPath -deinterlace -an -ss $interval -f mjpeg -t 1 -r 1 -y -s $size $origImagePath 2>&1";
		exec($cmd);
		/****** END ORIGINAL CREATION *****/
		/****** START WATERMARK CREATION *****/
		$watermarkImage = $fileNameWithOutExtension.'_watermark.jpg';		
		$watermarkImagePath = WWW_ROOT . 'uploads'. DS . 'videos'. DS .'watermark' . DS .$watermarkImage;
		$size = '200x200';  
		$cmd = "$ffmpeg -i $fileFullPath -deinterlace -an -ss $interval -f mjpeg -t 1 -r 1 -y -s $size $watermarkImagePath 2>&1";
		exec($cmd);
		/****** END WATERMARK CREATION *****/
		/******CODE FOR UPLOADING ON THE CDN****/
		App::import('Vendor', 'ObjectStorage', array('file' => 'ObjectStorage/ObjectStorage/Util.php'));
		$host = 'https://dal05.objectstorage.softlayer.net/auth/v1.0/';
		$username = 'SLOS359302-2:SL359302';
		$password = 'f737a3bdf32804e06ffea987118751855a8c6efbaf49b02d99c6564db02e6e70';
		$objectStorage = new ObjectStorage($host, $username, $password);
		try
		{
			$videoObject = $objectStorage->with('broadcast_video/'.$videoFileName)->setLocalFile($fileFullPath)->create();
			if(!empty($videoObject)){
				$message['video_url'] = $videoObject->getUrl();
			}
			
			$origObject = $objectStorage->with('broadcast_video/original/'.$origImage)->setLocalFile($origImagePath)->create();
			if(!empty($origObject)){
				$message['original_image_url'] = $origObject->getUrl();
			}
			
			$thumbObject = $objectStorage->with('broadcast_video/thumbnail/'.$thumbImage)->setLocalFile($thumbImagePath)->create();
			if(!empty($thumbObject)){
				$message['thumbnail_image_url'] = $thumbObject->getUrl();
			}
				
			$waterObject = $objectStorage->with('broadcast_video/watermark/'.$watermarkImage)->setLocalFile($watermarkImagePath)->create();
			if(!empty($waterObject)){
				$message['watermark_image_url'] = $waterObject->getUrl();
			}
			
			if(!empty($message)){
				if (is_file($origImagePath) && @unlink($origImagePath));
				if (is_file($thumbImagePath) && @unlink($thumbImagePath));
				if (is_file($watermarkImagePath) && @unlink($watermarkImagePath));
			}
		}
		catch(Exception $e)
		{
			$message['exception'] = $e->getMessage();
		}
		/******CODE END FOR UPLOADING ON THE CDN****/
		return $message;
	} 
	/**
		videoDelete from the cloud server
    */
	public function videoDelete($cloudLinkArray){
		App::import('Vendor', 'ObjectStorage', array('file' => 'ObjectStorage/ObjectStorage/Util.php'));
		$host = 'https://dal05.objectstorage.softlayer.net/auth/v1.0/';
		$username = 'SLOS359302-2:SL359302';
		$password = 'f737a3bdf32804e06ffea987118751855a8c6efbaf49b02d99c6564db02e6e70';
		$objectStorage = new ObjectStorage($host, $username, $password);
		$count = 0;
		if(!empty($cloudLinkArray)){
			foreach($cloudLinkArray as $key => $link){
				$result = $objectStorage->with($key.basename($link))->get()->delete();
				if($result){
					$count++;
				}
			}
		}
		if($count === count($cloudLinkArray)){
			return true;
		}else{
			return false;
		}
	}
}
?>