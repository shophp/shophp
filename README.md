ShoPHP
======

*Simple PHP shop structure built on Nette Framework*

Installation
------------

- create your configuration file based on [app/config.template.neon](app/config.template.neon)

- create `bootstrap.php`:

```php
require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode([]);
$configurator->enableDebugger(__DIR__ . '/../log');
$configurator->setTempDirectory(__DIR__ . '/../temp');

\ShoPHP\ShoPHP::initialize($configurator);

$configurator->addConfig(__DIR__ . '/path/to/your/config/config.neon');

return $configurator->createContainer();
```

- create `cli-config.php` in root folder:

```php
use Doctrine\ORM\EntityManagerInterface;

$container = require __DIR__ . "/path/to/your/bootstrap.php";

$entityManager = $container->getByType(EntityManagerInterface::class);
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
```

- execute `vendor/bin/shophp-db-update.bat f`
