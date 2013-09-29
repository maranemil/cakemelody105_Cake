<?php

	/**
	 * Upload Component, responsible for uploading images .
	 * @package
	 */

	class UploadComponent extends Object
	{

		function uploadNewFile(){

			// get info about path
			$data		= $this->PbTempFile;
			$dest		= $this->PbDestinationDirFile;
			$destPath	= $this->PbDestinationDir;
			$output		= $this->PbNewFileName;

			$MAX_WIDTH = 150;
			$MAX_HEIGHT = 200;

			$pic_width = 150;
			$pic_height =180;

			if(!is_dir($destPath)){
				mkdir($destPath,0777);
			}

			$imagePath = substr($output,0,6)."/";
			$filename = $data;

			// get dimensions and calculate new dimensions
			list($width, $height) = getimagesize($filename);
			$scale = min($MAX_WIDTH/$width, $MAX_HEIGHT/$height);

			$new_width = round($scale*$width);
			$new_height = round($scale*$height);

			if($pic_width){$newwidth = $new_width;}else{$newwidth = 100;}
			if($pic_height){$newheight = $new_height;}else{$newheight = 70;}
			
			// create new image
			$thumb = imagecreatetruecolor($newwidth, $newheight);
			$source = imagecreatefromjpeg($filename);
			imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			imagejpeg($thumb,$dest,100);

			return true;
		}

	}
?>