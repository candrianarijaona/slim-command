# Slim Framework Command Line
This repository contains a set of useful command lines for slim application. It uses the [symfony console component](https://symfony.com/doc/current/components/console.html).

## Install

Via composer

``` bash
$ composer require candrianarijaona/slim-command
```

Requires Slim 3.0.0 or newer.

## Usage

First, you need to create a PHP script to define the console application.
In this case, let's put it under the directory /bin.
Make you sure that your console has an access to your slim app.

```php
#!/usr/bin/env php
<?php

require __DIR__.'/../bootstrap.php'; //The bootstrap file where you init your slim app

use Symfony\Component\Console\Application;

$application = new Application();
$container = $app->getContainer();

// ... register commands

$application->run();
```

You can register additionnal command using add().

## Available commands

* [Container](#container)
* [Controller](#controller)
* [Router](#router)

### Container

Display the registered services for an application.

```php
<?php

use Candrianarijaona\Command\Container\DebugContainerCommand;

$application->add(new DebugContainerCommand($container));
```

Executing the commmand:

```bash
php bin/console debug:container
```

### Controller

Generate new controller for an application 

```php
<?php

use Candrianarijaona\Command\Controller\GenerateControllerCommand;

$baseDir  = __DIR__ . '/../app/Example';

$application->add(
    new GenerateControllerCommand($baseDir)
);
```

Executing the commmand:

```bash
php bin/console generate:controller
```


### Router

Display routes for an application.

```php
<?php

use Candrianarijaona\Command\Router\DebugRouterCommand;

$application->add(new DebugRouterCommand($container->router));
```

Executing the commmand:

```bash
php bin/console debug:router
```


