<?php

namespace Candrianarijaona\Command\Route;

use Slim\Route;
use Slim\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RouteCommand
 * @package Candrianarijaona\Command
 */
class DebugCommand extends Command
{
    /** @var Router */
    protected $router;

    /**
     * RouteCommand constructor.
     * @param Router $router    The slim router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:route')
            ->setDescription('Display route information')
            ->setHelp('This command allow you to display routes on your application')
        ;
    }

    /**
     *@inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table
            ->setHeaders(array('ID', 'Name', 'Pattern', 'Callable', 'Methods'))
            ->setRows($this->getFormatedRoutes())
        ;

        $table->render();
    }

    /**
     * Format the route output for the table
     *
     * @return array
     */
    protected function getFormatedRoutes()
    {
        $tableOutput = [];
        /** @var Route $route */
        foreach ($this->router->getRoutes() as $route) {
            $tableOutput[] = [
                $route->getIdentifier(),
                $route->getName(),
                $route->getPattern(),
                $this->getPrintableCallable($route->getCallable()),
                implode(',', $route->getMethods())
            ];
        }

        return $tableOutput;
    }

    /**
     * Get the description of the callable
     *
     * @param string|callable $callable     The route callable
     * @return string
     */
    protected function getPrintableCallable($callable)
    {
        if (is_callable($callable)) {
            return "Anonymous function";
        }

        return $callable;
    }
}
