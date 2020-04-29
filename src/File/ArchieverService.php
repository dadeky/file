<?php
namespace File;

class ArchieverService {
	
    /** @var string */
	private $archiveFolder;
	
	/** @var string */
	private $sourceFolder;
	
	/**
	 * 
	 * @param string $sourceFolder
	 * @param string $archiveFolder
	 */
	public function __construct($sourceFolder, $archiveFolder)
	{
		$this->sourceFolder = $sourceFolder;
		$this->archiveFolder = $archiveFolder;
	}
	
	/**
	 * 
	 * @param string $file
	 * @param string $destinationFileName
	 */
	public function archiveFile($file, $destinationFileName = null)
	{
	    $destinationFileName = $destinationFileName ? $destinationFileName : $file;
	    
	    rename($this->sourceFolder.$file, $this->archiveFolder.$destinationFileName);
	}
	
}
