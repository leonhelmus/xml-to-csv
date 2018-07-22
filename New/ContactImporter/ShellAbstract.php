<?php
/**
 * Abstract class for shell script
 *
 * Created by PhpStorm.
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 14:40
 */

namespace ContactImporter;

abstract class ShellAbstract
{
    /**
     * Input arguments
     *
     * @var array
     */
    protected $args = [];

    public function __construct()
    {
        $this->parseArgs();
    }

    /**
     * Parse input arguments
     *
     * @return ShellAbstract
     */
    protected function parseArgs()
    {
        $current = null;
        foreach ($_SERVER['argv'] as $arg) {
            $match = array();
            if (preg_match('#^--([\w\d_-]{1,})$#', $arg, $match) || preg_match('#^-([\w\d_]{1,})$#', $arg, $match)) {
                $current = $match[1];
                $this->args[$current] = true;
            } else {
                if ($current) {
                    $this->args[$current] = $arg;
                } else if (preg_match('#^([\w\d_]{1,})$#', $arg, $match)) {
                    $this->args[$match[1]] = true;
                }
            }
        }
        return $this;
    }

    /**
     * run shell script
     */
    abstract public function run();

    /**
     * Retrieve argument value by name
     *
     * @param string $name the argument name
     * @return bool|string|array
     */
    public function getArg($name)
    {
        if (isset($this->args[$name])) {
            return $this->args[$name];
        }
        return false;
    }
}