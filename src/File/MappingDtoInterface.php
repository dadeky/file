<?php
namespace File;

interface MappingDtoInterface
{
    /**
     * Gets the setter name to be called in order to set the value
     * @param integer $index an index of a setter
     * @return array
     */
    public function getMappingSetter($index);
}

