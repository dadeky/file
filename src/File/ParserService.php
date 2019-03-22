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
	    MappingDtoInterface $mappingDto=null
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
				            while(!feof($fd)){
				                $line =fgets($fd,500);
				                if (!empty($line))
				                {
				                    $lineDataArray = explode(";",rtrim($line));
				                    if(count($lineDataArray) > 0){
				                        $object = clone $mappingDto;
				                        foreach ($lineDataArray as $key => $value)
				                        {
				                            $methodName = $mappingDto->getMappingSetter($key);
				                            if (method_exists($object, $methodName))
				                            {
				                                $object->{ $methodName }($value);
				                            }
				                        }
				                        $objects[$file][] = $object;
				                    }
				                }
				            }
				        }
				        break;
				}
				fclose($fd);
			}
		}
		return $objects;
	}
}