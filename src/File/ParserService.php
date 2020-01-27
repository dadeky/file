<?php
namespace File;

use File\Exception\UnableToOpenFileException;
use File\Exception\MappingDtoDoesNotExistException;

class ParserService {
    
	private $incomingFolder;
	
	public function __construct(
			$incomingFolder
	){
		$this->incomingFolder = $incomingFolder;
	}
	
	public function parse(
	    $extension=false, 
	    $limit=null, 
	    $sort=null,
	    MappingDtoInterface $mappingDto=null,
	    $delimiter=CsvParser::DEFAULT_DELIMITER
	){
		$objects = [];
		$files = (new DirectoryCrawlerService())->directoryContent($this->incomingFolder, $extension, $limit, $sort);
		if(count($files) > 0){
			foreach($files as $file){
				
			    $extension = strtolower($extension);
				switch ($extension){
				    
				    case 'xml':
				        $fileString = file_get_contents($this->incomingFolder.$file);
				        $objects[$file] = simplexml_load_string($fileString);
				        break;
				    
				    case 'csv':
				        if (null === $mappingDto)
				            throw new MappingDtoDoesNotExistException();
				            
				        $fd = fopen($this->incomingFolder.$file,"r");
				        if(!$fd) {
				            throw new UnableToOpenFileException($file);
				        }else{
				            $lines = [];
				            while(!feof($fd)){
				                $lines[] = fgets($fd,500);
				            }
				            $objects[$file] = (new CsvParser())->parse($lines, $mappingDto, $delimiter);
				        }
				        fclose($fd);
				        break;
				}
			}
		}
		return $objects;
	}
}