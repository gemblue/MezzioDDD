<?php

declare(strict_types=1);

chdir(__DIR__ . '/../');

use DoctrineORMModule\CliConfigurator;
use Symfony\Component\Console\Application;

require_once 'vendor/autoload.php';

if (! isset($container)) {
    $container = require_once 'config/container.php';
}

if (! isset($application)) {
    $application = new Application();
}

$cliConfiguration = new CliConfigurator($container);
$cliConfiguration->configure($application);

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(
	function ($className) {
		return class_exists($className);
	}
);

$application->run();
