<?php
namespace File\Exception;

use Ddd\Exception\DomainException;

class NoFilesAvailableException extends DomainException
{
	private $defaultMessage = 'There are no files available in this directory directory: %1$s';
	
	public function __construct($path, $message=null)
	{
		parent::__construct((null === $message) ? $this->defaultMessage : $message, [$path]);
	}
}