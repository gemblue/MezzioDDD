<?php

declare(strict_types=1);

namespace AppTest;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

use function class_exists;
use function getenv;
use function shell_exec;

class DatabaseTestCase extends TestCase
{
	protected $app;
	private $container;

	protected function setUp(): void
	{
		$container = require 'config/container.php';

		if (getenv('CI_PAGES_DOMAIN')) {
			shell_exec('php bin/doctrine.php -q orm:schema-tool:create');
			AnnotationRegistry::registerLoader(
				function($className) {
					return class_exists($className);
				}
			);
		} else {
			$allowOverride = $container->getAllowOverride();
			$container->setAllowOverride(true);
			$config                                          = $container->get('config');
			$config['doctrine']['connection']['orm_default'] =
				(require 'config/autoload/local.php.test')['doctrine']['connection']['orm_default'];
			$container->setService('config', $config);
			$container->setAllowOverride($allowOverride);
			$this->container = $container;

			$argv            = $_SERVER['argv'];
			$_SERVER['argv'] = [
				'bin/doctrine.php',
				'-q',
				'orm:schema-tool:create',
			];

			$application = new SymfonyConsoleApplication();
			$application->setAutoExit(false);
			require 'bin/doctrine.php';
			$_SERVER['argv'] = $argv;
		}

		$app     = $container->get(Application::class);
		$factory = $container->get(MiddlewareFactory::class);

		(require 'config/pipeline.php')($app, $factory, $container);
		(require 'config/routes.php')($app, $factory, $container);
	
		$this->app = $app;
	}

	protected function tearDown(): void
	{
		if (getenv('CI_PAGES_DOMAIN')) {
			shell_exec('php bin/doctrine.php -q orm:schema-tool:drop --full-database --force');
			return;
		}

		$container = $this->container;

		$argv            = $_SERVER['argv'];
		$_SERVER['argv'] = [
			'bin/doctrine.php',
			'-q',
			'orm:schema-tool:drop',
			'--full-database',
			'--force',
		];

		$application = new SymfonyConsoleApplication();
		$application->setAutoExit(false);
		require 'bin/doctrine.php';
		$_SERVER['argv'] = $argv;
	}
}