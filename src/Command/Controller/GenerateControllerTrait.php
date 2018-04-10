<?php

namespace Candrianarijaona\Command\Controller;

/**
 * Trait GenerateControllerTrait
 *
 * @package     SlimCommand
 * @author      Claude Andrianarijaona
 * @licence     MIT
 * @copyright   (c) 2018, Claude Andrianarijaona
 */
trait GenerateControllerTrait
{
    /**
     * Generate the controller file
     *
     * @param string $rootDirectory     The root directory of the app
     * @param string $resourceDirectory The root directory of the app
     * @param string $namespace         The namespace for the controller
     * @param string $controller        The controller name
     * @param string $action            The action name
     *
     * @return string
     */
    protected function generate($rootDirectory, $resourceDirectory, $namespace, $controller, $action)
    {
        $content = file_get_contents($resourceDirectory . '/controller/controller.php.stub');
        $content = sprintf($content, $namespace, $controller, $namespace, $controller, $action);

        if (!file_exists($fileName = $rootDirectory . '/Controller/' . $controller . '.php')) {
            $file = fopen($fileName, 'w');
            $message = sprintf('%s is generated successfully', $controller);
            if (fputs($file, $content) === false) {
                $message = sprintf('Error on generating the controller %s', $controller);
            }
            fclose($file);

            return $message;
        }

        return sprintf("The controller %s already exists", $controller);
    }
}
