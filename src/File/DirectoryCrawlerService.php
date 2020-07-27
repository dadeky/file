<?php
namespace File;

use File\Exception\UnableToOpenDirException;

class DirectoryCrawlerService {
	
	private $filesFound;
	
	public function directoryContent($path, $extension=false, $limit=null, $sort=null){
		
		$filesArr = array();		
		
		if(!$extension){
			if ($handle = opendir($path)) {
				while (false !== ($file = readdir($handle))) {
					if($file != '.' && $file != '..'){
						$filename = $path.$file;
						$time = filemtime($filename);
						$filesArr[ $time."-".$file ] = $file;
					}
				}
				closedir($handle);
					
			}
		}else{
			//get directory listing into an array
			if ($handle = opendir($path)) {
				while (false !== ($file = readdir($handle))) {
					if($file != '.' && $file != '..'){
						if(substr($file,-4) == ".".$extension){
							$filename = $path.$file;
							$time = filemtime($filename);
							$filesArr[ $time."-".$file ] = $file;
						}
					}
				}
				closedir($handle);
				$this->filesFound = $filesArr;
			}else{
				throw new UnableToOpenDirException($path);
			}
		}
			
		if ($sort == 'desc') {
			krsort($filesArr);
		}elseif ($sort) {
			ksort($filesArr);
		}
			
		// limit the number of files
		if ($limit!=null) {
			$filesArr = array_slice($filesArr, 0, $limit);
		}
			
		return $filesArr;		
	}
}