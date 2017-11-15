<?php
namespace File;

interface MappingDtoInterface
{
    /**
     * Gets the setter name to be called in order to set the value.
     * For example if the $index argument is 3 the setter name which
     * corresponds to index 3 is returned and called in the ParserService to set the value in the DTO
     * @param integer $index an index of a setter
     * @return array
     */
    public function getMappingSetter($index);
}

