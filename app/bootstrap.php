<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode([]);
$configurator->enableDebugger(__DIR__ . '/../log');
$configurator->setTempDirectory(__DIR__ . '/../temp');

\ShoPHP\ShoPHP::initialize($configurator);

$configurator->addConfig(__DIR__ . '/config/config.neon');

return $configurator->createContainer();
