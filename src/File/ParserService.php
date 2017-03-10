<?php
namespace File;

class ParserService {
	
	private $incomingFolder;
	
	public function __construct(
			$incomingFolder
	){
		$this->incomingFolder = $incomingFolder;
	}
	
	public function parse($extension=false, $limit=null, $sort=null){
		$objects = array();
		$files = (new DirectoryCrawlerService())->directoryContent($this->incomingFolder, $extension, $limit, $sort);
		if(count($files) > 0){
			foreach($files as $file){
				$fileString = file_get_contents($this->incomingFolder.$file);
				$objects[$file] = simplexml_load_string($fileString);
			}
		}
		return $objects;
	}
}