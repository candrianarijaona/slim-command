<?php

namespace Candrianarijaona\Command\Controller;

use Exception;
use Slim\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class GenerateControllerCommand
 *
 * @package     SlimCommand
 * @author      Claude Andrianarijaona
 * @licence     MIT
 * @copyright   (c) 2018, Claude Andrianarijaona
 */
class GenerateControllerCommand extends Command
{
    const NAME = 'generate:controller';

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
            ->setDescription('Generate a new controller')
            ->setHelp('This command allow you to create a new controller')
            ->setDefinition(array(
                new InputOption('controller', '', InputOption::VALUE_REQUIRED, 'The name of the controller to create'),
                new InputOption('namespace', '', InputOption::VALUE_REQUIRED, 'The controller namespace', 'App\Controller'),
                new InputOption('directory', '', InputOption::VALUE_REQUIRED, '', 'app'),
                new InputOption('action', '', InputOption::VALUE_REQUIRED, 'The action in the controller', 'IndexAction'),
            ))
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $controller     = $helper->ask($input, $output, $this->getControllerQuestion());
        $namespace      = $helper->ask($input, $output, $this->getNamespaceQuestion($input));
        $rootDirectory  = $helper->ask($input, $output, $this->getRootDirectoryQuestion($input));
        $action         = $helper->ask($input, $output, $this->getActionQuestion($input));

        var_dump($rootDirectory, $controller, $namespace, $action);
    }

    protected function getRootDirectoryQuestion(InputInterface $input)
    {
        $default = $input->getOption('directory');
        $question = new Question($this->getQuestion('Root directory of your application', $default), $default);

        return $question;
    }

    protected function getControllerQuestion()
    {
        $question = new Question($this->getQuestion('Please enter the name of the controller', ''));
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new Exception('Controller name cannot be empty');
            }

            return $value;
        });

        return $question;
    }

    protected function getNamespaceQuestion(InputInterface $input)
    {
        $default = $input->getOption('namespace');
        $question = new Question($this->getQuestion('Please provide a namespace for your controller', $default), $default);

        return $question;
    }

    protected function getActionQuestion($input)
    {
        $default = $input->getOption('action');
        $question = new Question($this->getQuestion('Add new action in your controller', $default), $default);

        return $question;
    }

    protected function getQuestion($question, $default, $sep = ':')
    {
        return $default
            ? sprintf('<info>%s</info> [<comment>%s</comment>]%s ', $question, $default, $sep)
            : sprintf('<info>%s</info>%s ', $question, $sep);
    }
}
