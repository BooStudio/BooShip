<?php

namespace BooStudio\BooShip;

/**
 * Class BooShip
 *
 * @author  Scotty Knows <scott@Boostudio.com.au>
 */
class BooShip
{

    /**
     * @var  \BooStudio\BooShip\Config
     */
    private $config;

    /**
     * BooShip constructor.
     *
     * @param \BooStudio\BooShip\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     *
     * @return  string
     */
    public function sayHello($name)
    {
        $greeting = $this->config->get('greeting');

        return $greeting . ' ' . $name;
    }
}
