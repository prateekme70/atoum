<?php

namespace mageekguy\atoum\tests\units\scripts\builder\vcs;

use
	\mageekguy\atoum,
	\mageekguy\atoum\mock,
	\mageekguy\atoum\scripts\builder\vcs
;

require_once(__DIR__ . '/../../../../runner.php');

class svn extends atoum\test
{
	public function beforeTestMethod($testMethod)
	{
		if (extension_loaded('svn') === false)
		{
			define('SVN_REVISION_HEAD', -1);
			define('PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS', 1);
			define('SVN_AUTH_PARAM_DEFAULT_USERNAME', 2);
			define('SVN_AUTH_PARAM_DEFAULT_PASSWORD', 3);
		}
	}

	public function testClass()
	{
		$this->assert
			->testedClass->isSubclassOf('\mageekguy\atoum\adapter\aggregator')
		;
	}

	public function test__construct()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = false;

		$this->assert
			->exception(function() use ($adapter) {
						new vcs\svn($adapter);
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\runtime')
				->hasMessage('PHP extension svn is not available, please install it')
		;

		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->object($svn->getAdapter())->isIdenticalTo($adapter)
			->variable($svn->getRepositoryUrl())->isNull()
		;
	}

	public function testSetAdapter()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->object($svn->setAdapter($adapter = new atoum\adapter()))->isIdenticalTo($svn)
			->object($svn->getAdapter())->isIdenticalTo($adapter)
		;
	}

	public function testSetRepositoryUrl()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->object($svn->setRepositoryUrl($url = uniqid()))->isIdenticalTo($svn)
			->string($svn->getRepositoryUrl())->isEqualTo($url)
			->object($svn->setRepositoryUrl($url = rand(- PHP_INT_MAX, PHP_INT_MAX)))->isIdenticalTo($svn)
			->string($svn->getRepositoryUrl())->isEqualTo((string) $url)
		;
	}

	public function testSetRevision()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->object($svn->setRevision($revisionNumber = rand(1, PHP_INT_MAX)))->isIdenticalTo($svn)
			->integer($svn->getRevision())->isEqualTo($revisionNumber)
			->object($svn->setRevision($revisionNumber = uniqid()))->isIdenticalTo($svn)
			->integer($svn->getRevision())->isEqualTo((int) $revisionNumber)
		;
	}

	public function testSetUsername()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->object($svn->setUsername($username = uniqid()))->isIdenticalTo($svn)
			->string($svn->getUsername())->isEqualTo($username)
			->object($svn->setUsername($username = rand(- PHP_INT_MAX, PHP_INT_MAX)))->isIdenticalTo($svn)
			->string($svn->getUsername())->isEqualTo((string) $username)
		;
	}

	public function testSetPassword()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->object($svn->setPassword($password = uniqid()))->isIdenticalTo($svn)
			->string($svn->getPassword())->isEqualTo($password)
			->object($svn->setPassword($password = rand(- PHP_INT_MAX, PHP_INT_MAX)))->isIdenticalTo($svn)
			->string($svn->getPassword())->isEqualTo((string) $password)
		;
	}

	public function testSetDirectoryIteratorInjector()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->exception(function() use ($svn) {
					$svn->setDirectoryIteratorInjector(function() {});
				}
			)
				->isInstanceOf('\mageekguy\atoum\exceptions\logic')
				->hasMessage('Directory iterator injector must take one argument')
		;

		$directoryIterator = new \directoryIterator(__DIR__);

		$this->assert
			->object($svn->setDirectoryIteratorInjector($directoryIteratorInjector = function($directory) use ($directoryIterator) { return $directoryIterator; }))->isIdenticalTo($svn)
			->object($svn->getDirectoryIterator(uniqid()))->isIdenticalTo($directoryIterator)
		;
	}

	public function testGetDirectoryIterator()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->object($svn->getDirectoryIterator(__DIR__))->isInstanceOf('\directoryIterator')
		;
	}

	public function testGetNextRevisions()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$adapter->svn_auth_set_parameter = function() {};

		$this->assert
			->exception(function() use ($svn) {
						$svn->getNextRevisions();
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\runtime')
				->hasMessage('Unable to get logs, repository url is undefined')
			->adapter($adapter)
				->notCall('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->notCall('svn_log')
		;

		$svn->setRepositoryUrl($repositoryUrl = uniqid());

		$adapter->svn_auth_set_parameter = function() {};
		$adapter->svn_log = array();
		$adapter->resetCalls();

		$this->assert
			->array($svn->getNextRevisions())->isEmpty()
			->adapter($adapter)
				->call('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->call('svn_log', array($repositoryUrl, null, SVN_REVISION_HEAD))
		;

		$svn->setRevision($revision = rand(1, PHP_INT_MAX));

		$adapter->resetCalls();

		$this->assert
			->array($svn->getNextRevisions())->isEmpty()
			->adapter($adapter)
				->call('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->call('svn_log', array($repositoryUrl, $revision, SVN_REVISION_HEAD))
		;

		$adapter->resetCalls();

		$adapter->svn_log = array(uniqid() => uniqid());

		$this->assert
			->array($svn->getNextRevisions())->isEmpty()
			->adapter($adapter)->call('svn_log', array($repositoryUrl, $revision, SVN_REVISION_HEAD))
		;

		$adapter->resetCalls();

		$adapter->svn_log = array(
			array('rev' => $revision1 = uniqid()),
			array('rev' => $revision2 = uniqid()),
			array('rev' => $revision3 = uniqid())
		);

		$this->assert
			->array($svn->getNextRevisions())->isEqualTo(array($revision1, $revision2, $revision3))
			->adapter($adapter)->call('svn_log', array($repositoryUrl, $revision, SVN_REVISION_HEAD))
		;
	}

	public function testExportRepository()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;

		$svn = new vcs\svn($adapter);

		$this->assert
			->exception(function() use ($svn) {
						$svn->exportRepository(uniqid());
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\runtime')
				->hasMessage('Unable to export repository, repository url is undefined')
			->adapter($adapter)
				->notCall('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->notCall('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_USERNAME, $svn->getUsername()))
				->notCall('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $svn->getPassword()))
				->notCall('svn_checkout')
		;

		$svn->setRepositoryUrl($repositoryUrl = uniqid());

		$adapter->resetCalls();
		$adapter->svn_checkout = false;
		$adapter->svn_auth_set_parameter = function() {};

		$this->assert
			->exception(function() use ($svn, & $directory) {
						$svn->exportRepository($directory = uniqid());
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\runtime')
				->hasMessage('Unable to checkout repository \'' . $repositoryUrl . '\' in directory \'' . $directory . '\'')
			->adapter($adapter)
				->call('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->notCall('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_USERNAME, $svn->getUsername()))
				->notCall('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $svn->getPassword()))
				->call('svn_checkout', array($svn->getRepositoryUrl(), $directory, $svn->getRevision()))
		;

		$svn->setUsername(uniqid());

		$adapter->resetCalls();

		$this->assert
			->exception(function() use ($svn, & $directory) {
						$svn->exportRepository($directory = uniqid());
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\runtime')
				->hasMessage('Unable to checkout repository \'' . $repositoryUrl . '\' in directory \'' . $directory . '\'')
			->adapter($adapter)
				->call('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->call('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_USERNAME, $svn->getUsername()))
				->notCall('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $svn->getPassword()))
				->call('svn_checkout', array($svn->getRepositoryUrl(), $directory, $svn->getRevision()))
		;

		$svn->setPassword(uniqid());

		$adapter->resetCalls();

		$this->assert
			->exception(function() use ($svn, & $directory) {
						$svn->exportRepository($directory = uniqid());
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\runtime')
				->hasMessage('Unable to checkout repository \'' . $repositoryUrl . '\' in directory \'' . $directory . '\'')
			->adapter($adapter)
				->call('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->call('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_USERNAME, $svn->getUsername()))
				->call('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $svn->getPassword()))
				->call('svn_checkout', array($svn->getRepositoryUrl(), $directory, $svn->getRevision()))
		;

		$adapter->svn_checkout = true;

		$adapter->resetCalls();

		$this->assert
			->object($svn->exportRepository($directory = uniqid()))->isIdenticalTo($svn)
			->adapter($adapter)
				->call('svn_auth_set_parameter', array(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true))
				->call('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_USERNAME, $svn->getUsername()))
				->call('svn_auth_set_parameter', array(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $svn->getPassword()))
				->call('svn_checkout', array($svn->getRepositoryUrl(), $directory, $svn->getRevision()))
		;
	}

	public function testDeleteDirectoryContents()
	{
		$adapter = new atoum\test\adapter();
		$adapter->extension_loaded = true;
		$adapter->unlink = function() {};
		$adapter->rmdir = function() {};

		$this
			->mock('\mageekguy\atoum\scripts\builder\vcs\svn')
			->mock('\directoryIterator')
		;

		$svn = new mock\mageekguy\atoum\scripts\builder\vcs\svn($adapter, $svnController = new mock\controller());

		$directoryFile1Controller = new mock\controller();
		$directoryFile1Controller->__construct = function() {};
		$directoryFile1Controller->getPathname = $directoryFile1Path = uniqid();
		$directoryFile1Controller->isDir = false;
		$directoryFile1Controller->isDot = false;

		$directoryFile1 = new mock\directoryIterator($directoryFile1Path, $directoryFile1Controller);

		$directoryFile2Controller = new mock\controller();
		$directoryFile2Controller->__construct = function() {};
		$directoryFile2Controller->getPathname = $directoryFile2Path = uniqid();
		$directoryFile2Controller->isDir = false;
		$directoryFile2Controller->isDot = false;

		$directoryFile2 = new mock\directoryIterator($directoryFile2Path, $directoryFile2Controller);

		$directoryController = new mock\controller();
		$directoryController->__construct = function() {};
		$directoryController->next = function() {};
		$directoryController->rewind = function() {};
		$directoryController->isDir = true;
		$directoryController->isDot = false;
		$directoryController->getPathname = $directoryPath = uniqid();
		$directoryController->valid = false;
		$directoryController->valid[1] = true;
		$directoryController->current[1] = $directoryFile1;
		$directoryController->valid[2] = true;
		$directoryController->current[2] = $directoryFile2;

		$directory = new mock\directoryIterator($directoryPath, $directoryController);

		$file1Controller = new mock\controller();
		$file1Controller->__construct = function() {};
		$file1Controller->getPathname = $file1Path = uniqid();
		$file1Controller->isDir = false;
		$file1Controller->isDot = false;

		$file1 = new mock\directoryIterator(uniqid(), $file1Controller);

		$file2Controller = new mock\controller();
		$file2Controller->__construct = function() {};
		$file2Controller->getPathname = $file2Path = uniqid();
		$file2Controller->isDir = false;
		$file2Controller->isDot = false;

		$file2 = new mock\directoryIterator(uniqid(), $file2Controller);

		$rootController = new mock\controller();
		$rootController->__construct = function() {};
		$rootController->getPathname = $rootPath = uniqid();
		$rootController->next = function() {};
		$rootController->rewind = function() {};
		$rootController->valid = false;
		$rootController->valid[1] = true;
		$rootController->current[1] = $file1;
		$rootController->valid[2] = true;
		$rootController->current[2] = $directory;
		$rootController->valid[3] = true;
		$rootController->current[3] = $file2;

		$root = new mock\directoryIterator(uniqid(), $rootController);

		$svnController->getDirectoryIterator[1] = $root;
		$svnController->getDirectoryIterator[2] = $directory;

		$this->assert
			->object($svn->deleteDirectoryContents($rootPath))->isIdenticalTo($svn)
			->mock($svn)
				->call('getDirectoryIterator', array($rootPath))
				->call('getDirectoryIterator', array($directoryPath))
			->adapter($adapter)
				->call('unlink', array($directoryFile1Path))
				->call('unlink', array($directoryFile2Path))
				->call('unlink', array($file1Path))
				->call('unlink', array($file2Path))
				->call('rmdir', array($directoryPath))
				->notCall('rmdir', array($rootPath))
		;
	}
}

?>