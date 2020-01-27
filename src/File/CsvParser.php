<?php
namespace File;

class CsvParser
{
    const DEFAULT_DELIMITER = ";";
    
    public function parse(
        array $lines,
        MappingDtoInterface $mappingDto,
        $delimiter=self::DEFAULT_DELIMITER
    ){
        $objects = [];
        foreach ($lines as $line){
            if (!empty($line))
            {
                $lineDataArray = explode($delimiter,rtrim($line));
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
                    $objects[] = $object;
                }
            }
        }
        return $objects;
    }
}

