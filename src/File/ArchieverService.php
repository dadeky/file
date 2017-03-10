<?php
namespace File;

class ArchieverService {
	/** @var string */
	private $archiveFolder;
	/** @var string */
	private $sourceFolder;
	
	public function __construct($sourceFolder, $archiveFolder)
	{
		$this->sourceFolder = $sourceFolder;
		$this->archiveFolder = $archiveFolder;
	}
	
	public function archiveFile($file)
	{
		rename($this->sourceFolder.$file, $this->archiveFolder.$file);
	}
	
}