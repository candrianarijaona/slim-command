<?php

namespace Candrianarijaona\Command\Container;

use Exception;
use ReflectionClass;
use Slim\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DebugCommand
 * @package Candrianarijaona\Command
 */
class DebugCommand extends Command
{
    /** @var Container */
    protected $container;

    /**
     * RouteCommand constructor.
     * @param Container $container The PSR container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:debug-container')
            ->setDescription('Display container services')
            ->setHelp('This command allow you to display the registered services in your application');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table
            ->setHeaders(array('ID', 'Name'))
            ->setRows($this->getFormatedOutput());

        $table->render();
    }

    /**
     * @return array
     * @throws \Interop\Container\Exception\ContainerException
     */
    protected function getFormatedOutput()
    {
        $tableOutput = [];

        $keys = $this->container->keys();
        $raws = $this->container->raw('errorHandler');
        var_dump($raws);
        sort($keys);

        foreach ($keys as $key) {
            $callable = $this->container->get($key);
            if (!is_object($callable)) {
                continue;
            }

            $tableOutput[] = [
                $key,
                $this->getCallableClassName($callable),
            ];
        }

        return $tableOutput;
    }

    /**
     * Get a printable class name
     *
     * @param callable $callable    The service callable
     * @return string
     */
    protected function getCallableClassName($callable)
    {
        if (is_callable($callable)) {
            return get_class($callable);
        }

        try {
            $class = new ReflectionClass($callable);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $class->getName();
    }
}
