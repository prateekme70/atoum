# *atoum*

## A simple, modern and intuitive unit testing framework for PHP !

Just like SimpleTest or PHPUnit, *atoum* is a unit testing framework specific to the [PHP](http://www.php.net) language.  
However, it has been designed from the start with the following ideas in mind :

* Can be implemented *rapidly* ;
* *Simplify* test development ;
* Allow for writing *reliable, readable, and clear* unit tests ;

To accomplish that, it massively uses capabilities provided by *PHP 5.3*, to give the developer *a whole new way* of writing unit tests.  
Therefore, it can be installed and integrated inside an existing project extremely easily, since it is only a *single PHAR archive*, which is the one and only entry point for the developper.  
Also, thanks to its *fluid interface*, it allows for writing unit tests in a fashion close to natural language.  
It also makes it easier to implement stubbing within tests, thanks to intelligent uses of *anonymous functions and closures*.  
*atoum* natively, and by default, performs the execution of each unit test within a separate [PHP](http://www.php.net) process, to warrant *isolation*.  
Of course, it can be used seamlessly for continuous integration, and given its design, it can be made to cope with specific needs extremely easily.  
*atoum* also accomplishes all of this without affecting performance, since it has been developped to boast a reduced memory footprint while allowing for hastened test execution.  
It can also generate unit test execution reports in the Xunit format, which makes it compatible with continuous integration tools such as [Jenkins](http://jenkins-ci.org/).  
*atoum* also generates code coverage reports, in order to make it possible to supervise unit tests.  
Finally, even though it is developped mainly on UNIX, it can also work on Windows.  

## Prerequisites to use *atoum*

*atoum* absolutely requires *PHP 5.3* or superior to work.  
Should you want to use *atou* using its PHAR archive, you also need [PHP](http://www.php.net] to be able to access the `phar` module, which is normally available by default.  
On UNIX, in order to check whether you have this module or not, you just need to run the following command in your terminal :

	# php -m | grep -i phar

If `Phar` or equivalent gets displayed, then the module is properly installed.  
Generating reports in the Xunit format requires the `xml` module.  
On UNIX, in order to check whether you have this module or not, you just need to run the following command in your terminal :

	# php -m | grep -i xml

If `Xml` or equivalent gets displayed, then the module is properly installed.--
Should you wish to monitor the coverage rate of your code by the unit tests, the [Xdebug](http://xdebug.org/) will be required.  
On UNIX, in order to check whether you have this module or not, you just need to run the following command in your terminal :

	# php -m | grep -i xdebug

If `Xdebug` or equivalent gets displayed, then the module is properly installed.

## A unit testing framework that can be made operational in 5 minutes !

### Step 1 : Install *atoum*

You just have to download [its PHAR archive](http://downloads.atoum.org/nightly/mageekguy.atoum.phar) and store it where you wish, for example under `/path/to/project/tests/mageekguy.atoum.phar`.  
This PHAR archive contains the latest development version to pass the totality of *atoum*'s unit tests.  
*atoum*'s source code is also available via [the github repository](https://github.com/mageekguy/atoum).

### Step 2 : Write your tests

Using your preferred text editor, create the file `path/to/project/tests/units/helloWorld.php` and add the following code :

``` php
<?php

namespace vendor\project\tests\units;

require_once 'path/to/mageekguy.atoum.phar';

include 'path/to/project/classes/helloWorld.php';

use \mageekguy\atoum;
use \vendor\project;

class helloWorld extends atoum\test
{
	public function testSay()
	{
		$helloWorld = new project\helloWorld();

		$this->assert
			->string($helloWorld->say())->isEqualTo('Hello World !')
		;
	}
}

?>
```

### Step 3 : Run your test with the commandline

Launch your terminal and run the following command :

	# php path/to/test/file[enter]

You should get the following result, or something equivalent :

	> Atoum version XXX by Frédéric Hardy.
	Error: Unattended exception: Tested class 'vendor\project\helloWorld' does not exist for test class 'vendor\project\tests\units\helloWorld'

### Step 4 : Write the class corresponding to you test

Using again your preferred text editor, create the file `path/to/project/classes/helloWorld.php` and add the following code :

``` php
<?php

namespace vendor\project;

class helloWorld
{
	public function say()
	{
		return 'Hello World !';
	}
}

?>
```

### Step 5 : Run your test once more

In the same terminal, run the following command once again :

	# php path/to/test/file[enter]

You should get the following result, or something equivalent :

	> Atoum version 288 by Frédéric Hardy.
	> Run vendor\project\tests\units\helloWorld...
	[S___________________________________________________________][1/1]
	=> Test duration: 0.00 second.
	=> Memory usage: 0.25 Mb.
	

### Step 6 : Complete your tests and restart the cycle from Step 3

``` php
<?php

namespace vendor\project\tests\units;

require_once 'path/to/mageekguy.atoum.phar';

include_once 'path/to/project/classes/helloWorld.php';

use mageekguy\atoum;
use vendor\project;

class helloWorld extends atoum\test
{
	public function test__construct()
	{
		$helloWorld = new project\helloWorld();

		$this->assert
			->string($helloWorld->say())->isEqualTo('Hello !')
			->string($helloWorld->say($name = 'Frédéric Hardy'))->isEqualTo('Hello ' . $name . ' !')
		;
	}
}

?>
```

## To go further

*atoum*'s documentation is still being written, and the only available resource right now is the current document.  
However, if you want to further explore immediately *atoum*'s possibilities, we recommend :

* Running in your terminal, either the command `php mageekguy.atoum.phar -h`, or the command `php scripts/runner.php -h` ;
* Exploring the contents of the `configurations` directory in *atoum*'s source, as it contains configuration file samples ;
* Read the [conference supports](http://www.slideshare.net/impossiblium/atoum-le-framework-de-tests-unitaires-pour-php-53-simple-moderne-et-intuitif) about it, available online ;
* Read the (french) [wiki](https://github.com/mageekguy/atoum/wiki) ;
* Join the IRC channel *##atoum* on the *freenode* network ; * Ask questions by e-mail at the address *support[AT]atoum(DOT)org* ;

## Troubleshooting

### *atoum*'s PHAR archive seems to not be working

In this case, the first thing you will want to do is confirm whether you have the latest version of the archive.  
You just need to [download](http://downloads.atoum.org/nightly/mageekguy.atoum.phar) it again.  
If it still doesn't work, run the following command in a terminal window :

	# php -n mageekguy.atoum.phar -v

If you get *atoum*'s version number, then the problem is coming from your PHP configuration.  
In most cases, the cause would be within extensions, that might be incompatible with the PHAR format, or that would prevent executing PHAR archives as a security measure.  
The `ioncube` extension for instance seems incompatible with PHAR archives, and you must therefore deactivate it if you are using it, by commenting the following line out of your `php.ini`, by prefixing it with the `;` character :

	zend_extension = /path/to/ioncube_loader*.*

The `suhosin` extension prevents executing PHAR archives, therefore its default configuration must be modified in order to be able to use *atoum*, by adding the following line in your `php.ini` file :

	suhosin.executor.include.whitelist="phar"

To fix the problem, you just need to set it to 0 by editing your `php.ini` file or by running *atoum* with the following command :

	# php -d detect_unicode=0 mageekguy.atoum.phar [options]

If these three operations do not allow *atoum* to work, we suggest you send an e-mail to the address *support[AT]atoum(DOT)org*, describing in detail your configuration and your problem.  
You can also ask for help from the *atoum* development staff on the IRC channel *##atoum* on the *freenode* network.

### Error: Constant __COMPILER_HALT_OFFSET__ already defined /path/to/mageekguy.atoum.phar

This error comes from the fact the *atoum* PHAR archive is included in at least one place within your code, using `include` or `require`.  
To fix this problem, you just need to include the archive by using only `include_once` or `require_once`, in order to ensure it is not included several times.
