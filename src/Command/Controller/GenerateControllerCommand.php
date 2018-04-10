<?php

namespace Candrianarijaona\Command\Controller;

use Exception;
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
    use GenerateControllerTrait;

    const NAME = 'generate:controller';

    /** @var string The base directory of the application */
    protected $baseDir;

    protected $resourceDir;

    /**
     * DebugContainerCommand constructor.
     * @param string $baseDir   The application base directory
     */
    public function __construct($baseDir)
    {
        $this->baseDir      = $baseDir;
        $this->resourceDir  = __DIR__ . '/../../Resources';

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
                new InputOption('controller', '', InputOption::VALUE_REQUIRED, 'The name of the controller to create', 'IndexController'),
                new InputOption('namespace', '', InputOption::VALUE_REQUIRED, 'The controller namespace', 'App\Controller'),
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

        $controller     = $helper->ask($input, $output, $this->getControllerQuestion($input));
        $namespace      = $helper->ask($input, $output, $this->getNamespaceQuestion($input));
        $action         = $helper->ask($input, $output, $this->getActionQuestion($input));

        $message = $this->generate(
            $this->baseDir,
            $this->resourceDir,
            $namespace,
            $controller,
            $action
        );

        $output->writeln($message);
    }

    /**
     * Get the question for controller
     *
     * @param InputInterface $input
     * @return Question
     */
    protected function getControllerQuestion(InputInterface $input)
    {
        $default = $input->getOption('controller');
        $question = new Question($this->getQuestion('Please enter the name of the controller', $default), $default);
        $question->setValidator($this->getClassMethodValidator());

        return $question;
    }

    /**
     * Get the question for namespace
     *
     * @param InputInterface $input
     * @return Question
     */
    protected function getNamespaceQuestion(InputInterface $input)
    {
        $default = $input->getOption('namespace');
        $question = new Question($this->getQuestion('Please provide a namespace for your controller', $default), $default);

        return $question;
    }

    /**
     * Get the question for action
     *
     * @param InputInterface $input
     * @return Question
     */
    protected function getActionQuestion(InputInterface $input)
    {
        $default = $input->getOption('action');
        $question = new Question($this->getQuestion('Add new action in your controller', $default), $default);
        $question->setValidator($this->getClassMethodValidator());

        return $question;
    }

    /**
     * Format a question
     *
     * @param string $question
     * @param string $default
     * @param string $sep
     *
     * @return string
     */
    protected function getQuestion($question, $default, $sep = ':')
    {
        return $default
            ? sprintf('<info>%s</info> [<comment>%s</comment>]%s ', $question, $default, $sep)
            : sprintf('<info>%s</info>%s ', $question, $sep);
    }

    /**
     * @return callable
     */
    protected function getClassMethodValidator()
    {
        return function ($value) {
            if (0 === preg_match('#^[a-zA-Z]+$#', $value, $match)) {
                throw new Exception('The format is not correct');
            }
            return $value;
        };
    }
}
