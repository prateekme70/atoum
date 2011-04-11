<?php

namespace mageekguy\atoum\report\fields\runner\errors;

use
	\mageekguy\atoum,
	\mageekguy\atoum\report
;

class string extends report\fields\runner\errors
{
	const defaultTitlePrompt = '> ';
	const defaultMethodPrompt = '=> ';
	const defaultErrorPrompt = '==> ';

	protected $titlePrompt = '';
	protected $methodPrompt = '';
	protected $errorPrompt = '';

	public function __construct(atoum\locale $locale = null, $titlePrompt = null, $methodPrompt = null, $errorPrompt = null)
	{
		parent::__construct($locale);

		if ($titlePrompt === null)
		{
			$titlePrompt = static::defaultTitlePrompt;
		}

		if ($methodPrompt === null)
		{
			$methodPrompt = static::defaultMethodPrompt;
		}

		if ($errorPrompt === null)
		{
			$errorPrompt = static::defaultErrorPrompt;
		}

		$this
			->setTitlePrompt($titlePrompt)
			->setMethodPrompt($methodPrompt)
			->setErrorPrompt($errorPrompt)
		;
	}

	public function setTitlePrompt($prompt)
	{
		return $this->setPrompt($this->titlePrompt, $prompt);
	}

	public function getTitlePrompt()
	{
		return $this->titlePrompt;
	}

	public function setMethodPrompt($prompt)
	{
		return $this->setPrompt($this->methodPrompt, $prompt);
	}

	public function getMethodPrompt()
	{
		return $this->methodPrompt;
	}

	public function setErrorPrompt($prompt)
	{
		return $this->setPrompt($this->errorPrompt, $prompt);
	}

	public function getErrorPrompt()
	{
		return $this->errorPrompt;
	}

	public function __toString()
	{
		$string = '';

		if ($this->runner !== null)
		{
			$errors = $this->runner->getScore()->getErrors();

			$sizeOfErrors = sizeof($errors);

			if ($sizeOfErrors > 0)
			{
				$string .= $this->titlePrompt . sprintf($this->locale->__('There is %d error:', 'There are %d errors:', $sizeOfErrors), $sizeOfErrors) . PHP_EOL;

				$class = null;
				$method = null;

				foreach ($errors as $error)
				{
					if ($error['class'] !== $class || $error['method'] !== $method)
					{
						$string .= $this->methodPrompt . $error['class'] . '::' . $error['method'] . '():' . PHP_EOL;

						$class = $error['class'];
						$method = $error['method'];
					}

					$string .= $this->errorPrompt;

					$type = self::getType($error['type']);

					if ($error['case'] === null)
					{
						switch (true)
						{
							case $error['file'] === null:
								switch (true)
								{
									case $error['errorFile'] === null:
										$string .= sprintf($this->locale->_('Error %s in unknown file on unknown line, generated by unknown file:'), $type);
										break;

									case $error['errorLine'] === null:
										$string .= sprintf($this->locale->_('Error %s in unknown file on unknown line, generated by file %s:'), $type, $error['errorFile']);
										break;

									case $error['errorLine'] !== null:
										$string .= sprintf($this->locale->_('Error %s in unknown file on unknown line, generated by file %s on line %d:'), $type, $error['errorFile'], $error['errorLine']);
										break;
								}
								break;

							case $error['line'] === null:
								switch (true)
								{
									case $error['errorFile'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on unknown line, generated by unknown file:'), $type, $error['file']);
										break;

									case $error['errorLine'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on unknown line, generated by file %s:'), $type, $error['file'], $error['errorFile']);
										break;

									case $error['errorLine'] !== null:
										$string .= sprintf($this->locale->_('Error %s in %s on unknown line, generated by file %s on line %d:'), $type, $error['file'], $error['errorFile'], $error['errorLine']);
										break;
								}
								break;

							default:
								switch (true)
								{
									case $error['errorFile'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on line %d, generated by unknown file:'), $type, $error['file'], $error['line']);
										break;

									case $error['errorLine'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on line %d, generated by file %s:'), $type, $error['file'], $error['line'], $error['errorFile']);
										break;

									case $error['errorLine'] !== null:
										$string .= sprintf($this->locale->_('Error %s in %s on line %d, generated by file %s on line %d:'), $type, $error['file'], $error['line'], $error['errorFile'], $error['errorLine']);
										break;
								}
								break;
						}
					}
					else
					{
						switch (true)
						{
							case $error['file'] === null:
								switch (true)
								{
									case $error['errorFile'] === null:
										$string .= sprintf($this->locale->_('Error %s in unknown file on unknown line in case \'%s\', generated by unknown file:'), $type, $error['case']);
										break;

									case $error['errorLine'] === null:
										$string .= sprintf($this->locale->_('Error %s in unknown file on unknown line, generated by file %s in case \'%s\':'), $type, $error['errorFile'], $error['case']);
										break;

									case $error['errorLine'] !== null:
										$string .= sprintf($this->locale->_('Error %s in unknown file on unknown line, generated by file %s on line %d in case \'%s\':'), $type, $error['errorFile'], $error['errorLine'], $error['case']);
										break;
								}
								break;

							case $error['line'] === null:
								switch (true)
								{
									case $error['errorFile'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on unknown line, generated by unknown file in case \'%s\' in case \'%s\':'), $type, $error['file'], $error['case']);
										break;

									case $error['errorLine'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on unknown line, generated by file %s in case \'%s\':'), $type, $error['file'], $error['errorFile'], $error['case']);
										break;

									case $error['errorLine'] !== null:
										$string .= sprintf($this->locale->_('Error %s in %s on unknown line, generated by file %s on line %d in case \'%s\':'), $type, $error['file'], $error['errorFile'], $error['errorLine'], $error['case']);
										break;
								}
								break;

							default:
								switch (true)
								{
									case $error['errorFile'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on line %d, generated by unknown file in case \'%s\':'), $type, $error['file'], $error['line'], $error['case']);
										break;

									case $error['errorLine'] === null:
										$string .= sprintf($this->locale->_('Error %s in %s on line %d, generated by file %s in case \'%s\':'), $type, $error['file'], $error['line'], $error['errorFile'], $error['case']);
										break;

									case $error['errorLine'] !== null:
										$string .= sprintf($this->locale->_('Error %s in %s on line %d, generated by file %s on line %d in case \'%s\':'), $type, $error['file'], $error['line'], $error['errorFile'], $error['errorLine'], $error['case']);
										break;
								}
								break;
						}
					}

					$string .= PHP_EOL;

					foreach (explode(PHP_EOL, $error['message']) as $line)
					{
						$string .= $line . PHP_EOL;
					}
				}
			}
		}

		return $string;
	}

	protected function setPrompt(& $property, $prompt)
	{
		$property = (string) $prompt;

		return $this;
	}
}

?>
