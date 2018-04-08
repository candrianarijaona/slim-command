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
 * Class DebugContainerCommand
 *
 * @package     SlimCommand
 * @author      Claude Andrianarijaona
 * @licence    MIT
 * @copyright   (c) 2018, Claude Andrianarijaona
 */
class DebugContainerCommand extends Command
{
    const NAME = 'debug:container';

    /** @var Container */
    protected $container;

    /**
     * DebugContainerCommand constructor.
     * @param Container $container The slim container
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
            ->setName(self::NAME)
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
            ->setHeaders(array('Service ID', 'Classe Name'))
            ->setRows($this->getFormatedOutput());

        $table->render();
    }

    /**
     * Format the list of the services of the container
     *
     * @return array
     * @throws \Interop\Container\Exception\ContainerException
     */
    protected function getFormatedOutput()
    {
        $keys = $this->container->keys();
        sort($keys);

        $tableOutput = [];
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
        try {
            $class = new ReflectionClass($callable);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $class->getName();
    }
}
