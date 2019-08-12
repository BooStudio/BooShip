<?php

namespace BooStudio\BooShip\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class BooShipFacadeAccessor
 *
 * @author  Scotty Knows <scott@Boostudio.com.au>
 */
class BooShipFacadeAccessor extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'BooShip';
    }
}
