<?php

namespace Candrianarijaona\Command;

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
class RouteCommand extends Command
{
    /** @var Router */
    protected $router;

    /**
     * RouteCommand constructor.
     * @param Router $router
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
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
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
                $route->getCallable(),
                implode(',', $route->getMethods())
            ];
        }

        return $tableOutput;
    }
}
