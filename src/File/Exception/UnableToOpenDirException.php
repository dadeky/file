<?php
namespace File\Exception;

class UnableToOpenDirException extends DomainException{
	
	private $defaultMessage = 'Unable to open directory: %1$s';
	
	public function __construct($path, $message=null)
	{
		parent::__construct((null === $message) ? $this->defaultMessage : $message, [$path]);
	}
}