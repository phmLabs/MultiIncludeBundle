<?php
/**
 * Created by PhpStorm.
 * User: nils.langner
 * Date: 10.05.16
 * Time: 07:25
 */

namespace phmLabs\MultiIncludeBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class MultiIncludeExtension extends \Twig_Extension
{
    private $bundles;

    public function __construct(Container $container)
    {
        $this->bundles = $container->getParameter('kernel.bundles');
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('multi_include', array($this, 'multiInclude'), array(
                    'is_safe' => array('html'),
                    'needs_environment' => true)
            ));
    }

    public function multiInclude(\Twig_Environment $twig, $filename, array $context)
    {
        $output = '<div id="multi_include_' . str_replace(DIRECTORY_SEPARATOR, '_', strtolower($filename))  . '">';

        foreach ($this->bundles as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            $templateFileName = dirname($reflection->getFileName()) . '/Resources/views/' . $filename;

            if (file_exists($templateFileName)) {
                $output .= '<div class="multi-include ' . $this->from_camel_case($reflection->getShortName()) . '">' . $twig->render($templateFileName, $context) . '</div>';
            }
        }
        $output .= '</div>';

        return $output;
    }

    public function getName()
    {
        return "multi_include";
    }

    private function from_camel_case($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }
}