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
	    $extension = false, 
	    $limit = null, 
	    $sort = null,
	    MappingDtoInterface $mappingDto = null,
	    $delimiter = CsvParser::DEFAULT_DELIMITER,
        $linesFromEnd = 0
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
				        if (null === $mappingDto) {
				            throw new MappingDtoDoesNotExistException();
				        }
				        
				        $fd = fopen($this->incomingFolder.$file,"r");
				        if(!$fd) {
				            throw new UnableToOpenFileException($file);
				        }else{
				            $lines = [];
		                            if($linesFromEnd == 0)
                		            {
                                		while(!feof($fd)){
		                                    $lines[] = fgets($fd, 600);
                		                }
		                            }
		                            else
                		            {
                                		fseek($fd, -1, SEEK_END);
		                                $pos = ftell($fd);
                		                $lastLine = "";

		                                // Loop backword until we have our lines or we reach the start
                		                while($pos > 0 && count($lines) < $linesFromEnd) {

                                		    $C = fgetc($fd);
		                                    if($C == "\n") {
                		                        // skip empty lines
		                                        if(trim($lastLine) != "") {
                		                            $lines[] = $lastLine;
                                		        }
		                                        $lastLine = '';
                		                    } else {
		                                        $lastLine = $C.$lastLine;
                		                    }
                                		    fseek($fd, $pos--);
		                                }

		                                $lines = array_reverse($lines);
                		            }
				            $objects[$file] = (new CsvParser())->parse($lines, $mappingDto, $delimiter);
				        }
				        fclose($fd);
				        break;
				        
				    case 'txt':
				        if (null === $mappingDto) {
				            throw new MappingDtoDoesNotExistException();
				        }
				        
				        $fd = fopen($this->incomingFolder.$file,"r");
				        if(!$fd) {
				            throw new UnableToOpenFileException($file);
				        }else{
				            $lines = [];
		                            if($linesFromEnd == 0)
		                            {
		                                while(!feof($fd)){
		                                    $lines[] = fgets($fd, 600);
		                               }
		                            }
		                            else
		                            {
		                                fseek($fd, -1, SEEK_END);
		                                $pos = ftell($fd);
		                                $lastLine = "";

		                                // Loop backword until we have our lines or we reach the start
		                                while($pos > 0 && count($lines) < $linesFromEnd) {

		                                    $C = fgetc($fd);
		                                    if($C == "\n") {
		                                        // skip empty lines
		                                        if(trim($lastLine) != "") {
		                                            $lines[] = $lastLine;
		                                        }
		                                        $lastLine = '';
		                                    } else {
		                                        $lastLine = $C.$lastLine;
		                                    }
		                                    fseek($fd, $pos--);
		                                }

		                                $lines = array_reverse($lines);
		                            }
				            $objects[$file] = (new TxtParser())->parse($lines, $mappingDto, $delimiter);
				        }
				        fclose($fd);
				        break;
				}
			}
		}
		return $objects;
	}
}
