<?php

namespace BooStudio\BooShip\Tests;

use BooStudio\BooShip\Config;
use BooStudio\BooShip\BooShip;
use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{

    public function testSayHello()
    {
        $config = new Config();
        $sample = new BooShip($config);

        $name = 'Mahmoud Zalt';

        $result = $sample->sayHello($name);

        $expected = $config->get('greeting') . ' ' . $name;

        $this->assertEquals($result, $expected);
    }
}
