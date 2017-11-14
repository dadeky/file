<?php
namespace File\Exception;

use Dadeky\Ddd\Domain\Exception\DomainException;

class UnableToOpenFileException extends DomainException{
	
	private $defaultMessage = 'Unable to open file: %1$s';
	
	public function __construct($fileName, $message=null)
	{
	    parent::__construct((null === $message) ? $this->defaultMessage : $message, [$fileName]);
	}
}