<?php

use Doctrine\ORM\EntityManagerInterface;

$container = require __DIR__ . "/app/bootstrap.php";

$entityManager = $container->getByType(EntityManagerInterface::class);
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
