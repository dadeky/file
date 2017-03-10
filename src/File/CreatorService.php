<?php
namespace File;

use Symfony\Component\Filesystem\Filesystem;

class CreatorService 
{
	/** @var string */
	private $path;
	
	public function __construct(
			$path
	){
		$this->path = $path;
	}
	
	public function createFile($fileName,$fileContent)
	{
		// first store to tmp
		$fullFileName = $this->path.$fileName;
		(new Filesystem())->dumpFile($fullFileName.".tmp", $fileContent);
		
		// then to xml
		(new Filesystem())->rename(
				$fullFileName.".tmp",
				$fullFileName.".xml");
	}
}